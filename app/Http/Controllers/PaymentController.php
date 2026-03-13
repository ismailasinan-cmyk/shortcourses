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

        $request->validate([
            'rrr' => 'required|string|size:12',
            'payment_type' => 'required|string|in:APPLICATION_FEE,COURSE_FEE,BOTH',
            'receipt' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        $paymentType = $request->payment_type;
        $amountExpected = 0;
        if ($paymentType === 'APPLICATION_FEE') {
            $amountExpected = $application->application_fee_amount;
        } elseif ($paymentType === 'COURSE_FEE') {
            $amountExpected = $application->amount;
        } else {
            $amountExpected = $application->application_fee_amount + $application->amount;
        }

        // Upload Receipt
        $receiptPath = $request->file('receipt')->store('payment_receipts', 'private');

        // Verify with Remita
        $verification = $this->remita->verifyPayment($request->rrr);

        Log::info('Payment Confirmation Attempt', [
            'app_ref' => $application->application_ref,
            'rrr' => $request->rrr,
            'type' => $paymentType,
            'verification' => $verification
        ]);

        $status = 'PENDING';
        $verifiedAt = null;
        $isPaid = false;

        if ($verification && (isset($verification['status']) && in_array($verification['status'], ['00', '01']))) {
             if ($verification['amount'] >= $amountExpected) {
                 $status = 'VERIFIED';
                 $verifiedAt = now();
                 $isPaid = true;
             } else {
                 $status = 'REJECTED'; 
                 Log::warning('Payment verification amount mismatch', ['expected' => $amountExpected, 'actual' => $verification['amount']]);
             }
        }

        $payment = Payment::updateOrCreate(
            ['remita_rrr' => $request->rrr],
            [
                'application_id' => $application->id,
                'amount' => $amountExpected,
                'payment_type' => $paymentType,
                'status' => $status,
                'channel' => 'REMITA',
                'receipt_path' => $receiptPath,
                'response_payload' => json_encode($verification),
                'verified_at' => $verifiedAt,
            ]
        );

        if ($isPaid) {
            $this->updateApplicationFeeStatuses($payment);
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
        $paymentType = $request->input('payment_type', 'BOTH'); // APPLICATION_FEE, COURSE_FEE, BOTH

        if ($paymentType === 'APPLICATION_FEE' && $application->application_fee_status === 'PAID') {
            return back()->with('success', 'Application fee already paid.');
        }

        if ($paymentType === 'COURSE_FEE' && $application->course_fee_status === 'PAID') {
            return back()->with('success', 'Course fee already paid.');
        }

        if ($application->payment_status === 'PAID' && $paymentType === 'BOTH') {
            return redirect()->route('applications.review', $application->application_ref)->with('success', 'Payment already completed.');
        }

        // Calculate Amount based on payment type
        $amountToPay = 0;
        if ($paymentType === 'APPLICATION_FEE') {
            $amountToPay = $application->application_fee_amount;
        } elseif ($paymentType === 'COURSE_FEE') {
            $amountToPay = $application->amount;
        } else {
            $amountToPay = $application->application_fee_amount + $application->amount;
        }

        // Check if we already have a pending payment for this application and type
        $existingPayment = Payment::where('application_id', $application->id)
                                  ->where('payment_type', $paymentType)
                                  ->where('status', 'PENDING')
                                  ->latest()
                                  ->first();

        if ($existingPayment) {
            $rrr = $existingPayment->remita_rrr;
            $response = json_decode($existingPayment->response_payload, true);
        } else {
            // Temporary override application amount for Remita initialization
            $originalAmount = $application->amount;
            $application->amount = $amountToPay; 
            $response = $this->remita->initializePayment($application);
            $application->amount = $originalAmount; // Restore
        }

        if ($response && (isset($response['RRR']) || isset($rrr))) {
            $rrr = $rrr ?? $response['RRR'];
            
            if (!$existingPayment) {
                Payment::create([
                    'application_id' => $application->id,
                    'remita_rrr' => $rrr,
                    'amount' => $amountToPay,
                    'payment_type' => $paymentType,
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

            return view('payments.redirect', compact('paymentUrl', 'merchantId', 'hash', 'rrr', 'responseUrl', 'apiKey', 'publicKey', 'application', 'paymentType'));
        }

        return back()->with('error', 'Failed to initialize payment. Please try again.');
    }

    public function callback(Request $request)
    {
        Log::info('Remita Callback:', $request->all());

        $rrr = $request->input('RRR') ?? $request->input('rrr');
        
        if (!$rrr) {
             return redirect()->route('home')->with('error', 'Invalid Payment Callback');
        }

        $verification = $this->remita->verifyPayment($rrr);

        if ($verification && (isset($verification['status']) && in_array($verification['status'], ['00', '01']))) {
            $payment = Payment::where('remita_rrr', $rrr)->first();
            
            if ($payment) {
                $payment->update([
                    'status' => 'VERIFIED',
                    'response_payload' => json_encode($verification),
                    'verified_at' => now(),
                    'paid_at' => now(),
                ]);

                $this->updateApplicationFeeStatuses($payment);
                
                return redirect()->route('applications.review', $payment->application->application_ref)->with('success', 'Payment Successful!');
            }
        }

        return redirect()->route('home')->with('error', 'Payment Verification Failed.');
    }

    protected function updateApplicationFeeStatuses($payment)
    {
        $application = $payment->application;
        $type = $payment->payment_type;

        if ($type === 'APPLICATION_FEE') {
            $application->application_fee_status = 'PAID';
        } elseif ($type === 'COURSE_FEE') {
            $application->course_fee_status = 'PAID';
        } elseif ($type === 'BOTH') {
            $application->application_fee_status = 'PAID';
            $application->course_fee_status = 'PAID';
        }

        // Check if overall payment status should be PAID
        if ($application->application_fee_status === 'PAID' && $application->course_fee_status === 'PAID') {
            $application->payment_status = 'PAID';
        }

        $application->save();

        // Send Receipt Email
        try {
            \Illuminate\Support\Facades\Mail::to($application->email)->send(new \App\Mail\PaymentReceipt($application));
        } catch (\Exception $e) {
            Log::error('Email Error: ' . $e->getMessage());
        }
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
