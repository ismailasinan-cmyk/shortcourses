<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Payment;
use App\Services\RemitaService;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $remita;

    public function __construct(RemitaService $remita)
    {
        $this->remita = $remita;
    }

    public function instruction($ref)
    {
        $application = Application::where('application_ref', $ref)->firstOrFail();
        
        if ($application->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this application.');
        }
        
        if ($application->payment_status == 'PAID') {
            return redirect()->route('applications.review', $application->application_ref)->with('success', 'Payment already completed.');
        }

        return view('payments.instruction', compact('application'));
    }

    public function confirmForm($ref)
    {
        $application = Application::where('application_ref', $ref)->firstOrFail();

        if ($application->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this application.');
        }

        if ($application->payment_status == 'PAID') {
            return redirect()->route('applications.review', $application->application_ref)->with('success', 'Payment already completed.');
        }

        return view('payments.confirm', compact('application'));
    }

    public function processConfirmation(Request $request, $ref)
    {
        $application = Application::where('application_ref', $ref)->firstOrFail();

        if ($application->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this application.');
        }

        if ($application->payment_status == 'PAID') {
             return redirect()->route('applications.review', $application->application_ref);
        }

        $request->validate([
            'rrr' => 'required|string|size:12', // Remita RRR is 12 digits
            'receipt' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120', // Max 5MB
        ]);

        // Upload Receipt
        $receiptPath = $request->file('receipt')->store('payment_receipts', 'private'); // Store privately

        // Verify with Remita
        // Rate limiting should be handled by middleware on route
        $verification = $this->remita->verifyPayment($request->rrr);

        Log::info('Payment Confirmation Attempt', [
            'app_ref' => $application->application_ref,
            'rrr' => $request->rrr,
            'verification' => $verification
        ]);

        $status = 'PENDING';
        $verifiedAt = null;
        $paymentStatus = 'PENDING';

        // Check verification response
        // Remita "00" or "01" means successful
        if ($verification && (isset($verification['status']) && in_array($verification['status'], ['00', '01']))) {
             if ($verification['amount'] >= $application->amount) {
                 $status = 'VERIFIED';
                 $verifiedAt = now();
                 $paymentStatus = 'PAID';
             } else {
                 $status = 'REJECTED'; // Amount mismatch
                 Log::warning('Payment verification amount mismatch', ['expected' => $application->amount, 'actual' => $verification['amount']]);
             }
        } else {
             // Verification failed or pending at Remita end
             // If completely invalid, maybe REJECTED? Or just keep PENDING
             // Ideally we should differentiate "Invalid RRR" from "Pending Payment"
             // For now, if API says fail, we keep as 'PENDING' for admin review or retry
        }

        // Create or Update Payment Record
        // We might have an existing payment init record from the "Pay Now" step if used
        $payment = Payment::updateOrCreate(
            ['remita_rrr' => $request->rrr],
            [
                'application_id' => $application->id,
                'amount' => $application->amount, // Use app amount or verification amount? Ideally app amount
                'status' => $status,
                'channel' => 'REMITA',
                'receipt_path' => $receiptPath,
                'response_payload' => json_encode($verification),
                'verified_at' => $verifiedAt,
            ]
        );

        if ($paymentStatus == 'PAID') {
            $application->update(['payment_status' => 'PAID', 'amount' => $application->amount]); // Ensure paid amount is set
            
            // Send Receipt Email
            try {
                \Illuminate\Support\Facades\Mail::to($application->email)->send(new \App\Mail\PaymentReceipt($application));
            } catch (\Exception $e) {
                Log::error('Email Error: ' . $e->getMessage());
            }

            return redirect()->route('applications.review', $application->application_ref)->with('success', 'Payment confirmed successfully!');
        }

        return redirect()->route('applications.review', $application->application_ref)->with('info', 'Payment submitted for verification. If you have paid, it will be updated shortly.');
    }

    // Keep the init method for the moment if we still want to support the "Pay Now" redirection flow?
    // User requested: "Applicant pays on Remita (outside the portal)"
    // But we can keep init if we want to facilitate that redirection from the instruction page.
    public function init(Request $request)
    {
        $application = Application::where('application_ref', $request->application_ref)->firstOrFail();
        
        if ($application->payment_status == 'PAID') {
            return redirect()->route('applications.review', $application->application_ref)->with('success', 'Payment already completed.');
        }

        // Check if we already have a pending payment for this application
        $existingPayment = Payment::where('application_id', $application->id)
                                  ->where('status', 'PENDING')
                                  ->latest()
                                  ->first();

        if ($existingPayment) {
            $rrr = $existingPayment->remita_rrr;
            $response = json_decode($existingPayment->response_payload, true);
        } else {
            $response = $this->remita->initializePayment($application);
        }

        if ($response && (isset($response['RRR']) || isset($rrr))) {
            $rrr = $rrr ?? $response['RRR'];
            
            if (!$existingPayment) {
                Payment::create([
                    'application_id' => $application->id,
                    'remita_rrr' => $rrr,
                    'amount' => $application->amount,
                    'status' => 'PENDING',
                    'channel' => 'REMITA',
                    'response_payload' => json_encode($response),
                ]);
            }

            $merchantId = config('services.remita.merchant_id');
            $apiKey = config('services.remita.api_key');
            $publicKey = config('services.remita.public_key');
            $hash = hash('sha512', $merchantId . $rrr . $apiKey);
            $responseUrl = route('payments.remita.callback');

            if (str_contains($responseUrl, '127.0.0.1') || str_contains($responseUrl, 'localhost')) {
                $responseUrl = "https://webhook.site/acetel-test-callback"; 
                Log::warning('Using dummy public URL for Remita callback due to localhost:', ['responseUrl' => $responseUrl]);
            }

            $paymentUrl = "https://demo.remita.net/remita/ecomm/finalize.reg";

            return view('payments.redirect', compact('paymentUrl', 'merchantId', 'hash', 'rrr', 'responseUrl', 'apiKey', 'publicKey', 'application'));
        }

        return back()->with('error', 'Failed to initialize payment. Please try again.');
    }

    public function callback(Request $request)
    {
        Log::info('Remita Callback:', $request->all());

        // Remita sends RRR and sometimes orderId in POST/GET
        $rrr = $request->input('RRR') ?? $request->input('rrr');
        
        if (!$rrr) {
             return redirect()->route('home')->with('error', 'Invalid Payment Callback');
        }

        $verification = $this->remita->verifyPayment($rrr);

        if ($verification && (isset($verification['status']) && in_array($verification['status'], ['00', '01']))) {
            // Payment Successful
            $payment = Payment::where('remita_rrr', $rrr)->first();
            
            if ($payment) {
                $payment->update([
                    'status' => 'VERIFIED',
                    'response_payload' => json_encode($verification),
                    'verified_at' => now(), // Add verified_at
                    'paid_at' => now(), // Add paid_at
                ]);

                $application = $payment->application;
                if ($application->payment_status != 'PAID') {
                    $application->update(['payment_status' => 'PAID', 'amount' => $application->amount]);
                    
                    // Send Email
                    try {
                        \Illuminate\Support\Facades\Mail::to($application->email)->send(new \App\Mail\PaymentReceipt($application));
                    } catch (\Exception $e) {
                        Log::error('Email Error: ' . $e->getMessage());
                    }
                }
                
                return redirect()->route('applications.review', $application->application_ref)->with('success', 'Payment Successful!');
            }
        }

        // If not successful
        return redirect()->route('home')->with('error', 'Payment Verification Failed.');
    }
    public function viewReceipt($ref)
    {
        $application = Application::where('application_ref', $ref)->firstOrFail();
        
        if ($application->user_id !== auth()->id() && !auth()->user()->is_admin) {
            abort(403);
        }
        
        // Find the pending payment with receipt
        $payment = Payment::where('application_id', $application->id)
                          ->where('status', 'PENDING')
                          ->whereNotNull('receipt_path')
                          ->latest()
                          ->firstOrFail();

        if (!\Illuminate\Support\Facades\Storage::disk('private')->exists($payment->receipt_path)) {
            abort(404);
        }

        return \Illuminate\Support\Facades\Storage::disk('private')->response($payment->receipt_path);
    }

    public function deleteReceipt($ref)
    {
        $application = Application::where('application_ref', $ref)->firstOrFail();

        if ($application->user_id !== auth()->id()) {
            abort(403);
        }

        if ($application->payment_status == 'PAID') {
            return back()->with('error', 'Cannot delete receipt for an already paid application.');
        }

        $payment = Payment::where('application_id', $application->id)
                          ->where('status', 'PENDING')
                          ->whereNotNull('receipt_path')
                          ->latest()
                          ->firstOrFail();

        // Delete file
        if (\Illuminate\Support\Facades\Storage::disk('private')->exists($payment->receipt_path)) {
            \Illuminate\Support\Facades\Storage::disk('private')->delete($payment->receipt_path);
        }

        // Clear path in DB
        $payment->receipt_path = null;
        $payment->save();

        return redirect()->route('applications.review', $application->application_ref)->with('success', 'Receipt deleted successfully. You can now upload a new one.');
    }
}
