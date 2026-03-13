<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Application::with(['course' => fn($q) => $q->withTrashed(), 'payments'])->latest();

        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('application_ref', 'like', "%{$search}%")
                  ->orWhere('surname', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('course', function($q) use ($search) {
                      $q->where('course_name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('course_id')) {
            $query->where('short_course_id', $request->course_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->has('export')) {
            return $this->exportCsv($query->get());
        }

        $applications = $query->paginate(20);
        $courses = \App\Models\ShortCourse::all();

        return view('admin.applications.index', compact('applications', 'courses'));
    }

    private function exportCsv($applications)
    {
        $fileName = 'applications_export_' . date('Y-m-d_H-i') . '.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'Ref', 'Surname', 'First Name', 'Other Name', 'Email', 'Phone', 
            'Gender', 'Address', 'State', 'LGA', 'Course', 'Amount', 
            'App Fee Status', 'Course Fee Status', 'Payment Status', 'SSCE Type', 'Exam Year', 'Exam No', 'Date'
        ];

        $callback = function() use($applications, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($applications as $app) {
                fputcsv($file, [
                    $app->application_ref,
                    $app->surname,
                    $app->first_name,
                    $app->other_name,
                    $app->email,
                    $app->phone,
                    $app->gender,
                    $app->address,
                    $app->state,
                    $app->lga,
                    $app->course->course_name,
                    $app->amount,
                    $app->application_fee_status,
                    $app->course_fee_status,
                    $app->payment_status,
                    $app->ssce_type,
                    $app->ssce_year,
                    $app->ssce_exam_number,
                    $app->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function downloadSsce($id)
    {
        $application = \App\Models\Application::findOrFail($id);
        
        if (!\Illuminate\Support\Facades\Storage::exists($application->ssce_file_path)) {
            return back()->with('error', 'File not found.');
        }

        return \Illuminate\Support\Facades\Storage::download($application->ssce_file_path);
    }

    public function viewSsce($id)
    {
        $application = \App\Models\Application::findOrFail($id);
        
        if (!\Illuminate\Support\Facades\Storage::exists($application->ssce_file_path)) {
            return response()->json(['error' => 'File not found.'], 404);
        }

        $file = \Illuminate\Support\Facades\Storage::get($application->ssce_file_path);
        $type = \Illuminate\Support\Facades\Storage::mimeType($application->ssce_file_path);

        return response($file)->header('Content-Type', $type);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'admission_status' => 'required|in:PENDING,ADMITTED,REJECTED'
        ]);

        $application = \App\Models\Application::findOrFail($id);
        $application->admission_status = $request->admission_status;
        $application->save();

        if ($application->admission_status === 'ADMITTED') {
            try {
                \Illuminate\Support\Facades\Log::info('Attempting to send admission email to: ' . $application->email);
                \Illuminate\Support\Facades\Mail::to($application->email)->send(new \App\Mail\AdmissionNotification($application));
                \Illuminate\Support\Facades\Log::info('Admission email sent successfully.');
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Admission Mail CRITICAL FAILURE: ' . $e->getMessage());
                \Illuminate\Support\Facades\Log::error($e->getTraceAsString());
            }
        }

        return back()->with('success', 'Application status updated successfully.');
    }
    public function viewReceipt(Request $request, $id)
    {
        $application = \App\Models\Application::findOrFail($id);
        
        $paymentId = $request->input('payment_id');
        if ($paymentId) {
            $payment = \App\Models\Payment::where('application_id', $application->id)->findOrFail($paymentId);
        } else {
            // Find latest payment with receipt
            $payment = $application->payments()->whereNotNull('receipt_path')->latest()->first();
        }

        if (!$payment || !\Illuminate\Support\Facades\Storage::disk('private')->exists($payment->receipt_path)) {
            return back()->with('error', 'Receipt file not found.');
        }

        return \Illuminate\Support\Facades\Storage::disk('private')->response($payment->receipt_path);
    }

    public function approvePayment(Request $request, $id)
    {
        $application = \App\Models\Application::findOrFail($id);
        
        $paymentId = $request->input('payment_id');
        if ($paymentId) {
            $payment = \App\Models\Payment::where('application_id', $application->id)->findOrFail($paymentId);
        } else {
            $payment = $application->payments()->where('status', 'PENDING')->latest()->first();
        }

        if (!$payment) {
             return back()->with('error', 'No pending payment found to approve.');
        }

        // Update Payment Status
        $payment->update([
            'status' => 'VERIFIED',
            'verified_at' => now(),
            'paid_at' => now(),
            'channel' => 'MANUAL_APPROVAL' 
        ]);
        
        // Update Application Status based on payment type
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

        try {
            \Illuminate\Support\Facades\Mail::to($application->email)->send(new \App\Mail\PaymentReceipt($application));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Payment Receipt Mail failed: ' . $e->getMessage());
        }

        return back()->with('success', 'Payment approved successfully.');
    }

    public function uploadCertificate(Request $request, $id)
    {
        $request->validate([
            'certificate' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);

        $application = \App\Models\Application::findOrFail($id);

        if ($application->admission_status !== 'ADMITTED' || $application->application_fee_status !== 'PAID' || $application->course_fee_status !== 'PAID') {
            return back()->with('error', 'Certificate can only be uploaded for admitted students with fully verified payments.');
        }

        if ($request->hasFile('certificate')) {
            // Delete old certificate if exists
            if ($application->certificate_path && \Illuminate\Support\Facades\Storage::disk('private')->exists($application->certificate_path)) {
                \Illuminate\Support\Facades\Storage::disk('private')->delete($application->certificate_path);
            }

            $path = $request->file('certificate')->store('certificates', 'private');
            
            $application->update([
                'certificate_path' => $path,
                'certificate_issued_at' => now()
            ]);

            try {
                \Illuminate\Support\Facades\Mail::to($application->email)->send(new \App\Mail\CertificateReady($application));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Certificate Notification Mail failed: ' . $e->getMessage());
            }

            return back()->with('success', 'Certificate uploaded successfully student has been notified.');
        }

        return back()->with('error', 'Failed to upload certificate.');
    }

    public function batchDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:applications,id',
        ]);

        $count = count($request->ids);
        
        // Prevent deleting PAID applications
        $paidCount = \App\Models\Application::whereIn('id', $request->ids)->where('payment_status', 'PAID')->count();
        if ($paidCount > 0) {
            return back()->with('error', "Cannot delete applications with PAID status. $paidCount selected application(s) have been paid for.");
        }

        \App\Models\Application::whereIn('id', $request->ids)->delete();

        return back()->with('success', "$count application(s) deleted successfully.");
    }
    public function viewCertificate($id)
    {
        $application = \App\Models\Application::findOrFail($id);
        
        if (!$application->certificate_path || !\Illuminate\Support\Facades\Storage::disk('private')->exists($application->certificate_path)) {
            return response()->json(['error' => 'Certificate file not found.'], 404);
        }

        return \Illuminate\Support\Facades\Storage::disk('private')->response($application->certificate_path);
    }
}
