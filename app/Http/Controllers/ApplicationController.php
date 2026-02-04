<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function showForm()
    {
        $courses = \App\Models\ShortCourse::where('status', true)->get();
        return view('apply.form', compact('courses'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'surname' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'other_name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'gender' => 'required|string',
            'date_of_birth' => 'required|date',
            'address' => 'required|string',
            'country' => 'required|string',
            'state' => 'required|string',
            'lga' => 'required|string',
            
            'highest_qualification' => 'nullable|string|in:SSCE,Degree',
            
            'ssce_type' => 'required_if:highest_qualification,SSCE|nullable|string',
            'ssce_year' => 'required_if:highest_qualification,SSCE|nullable|digits:4|integer|min:1990|max:' . (date('Y')),
            'ssce_exam_number' => 'required_if:highest_qualification,SSCE|nullable|string',
            
            'degree_type' => 'required_if:highest_qualification,Degree|nullable|string|in:BSc,MSc,PhD,HND',
            'degree_institution' => 'required_if:highest_qualification,Degree|nullable|string',
            'degree_year' => 'required_if:highest_qualification,Degree|nullable|digits:4|integer|min:1980|max:' . (date('Y')),
            'degree_class' => 'required_if:highest_qualification,Degree|nullable|string',

            'additional_certifications' => 'nullable|array|max:5',
            'additional_certifications.*.name' => 'required_with:additional_certifications|string|max:255',
            'additional_certifications.*.institution' => 'required_with:additional_certifications|string|max:255',
            'additional_certifications.*.year' => 'required_with:additional_certifications|integer|digits:4',

            'short_course_id' => 'required|exists:short_courses,id',
            'declaration' => 'accepted',
        ]);

        // Get Course Fee
        $course = \App\Models\ShortCourse::find($validated['short_course_id']);

        // Generate Ref and Create Application with Retry
        $maxRetries = 5;
        $attempt = 0;
        $application = null;

        while ($attempt < $maxRetries) {
            try {
                $year = date('Y');
                
                // Get the last created application for the current year
                $latestApp = \App\Models\Application::whereYear('created_at', $year)
                                ->latest('id')
                                ->first();

                if ($latestApp) {
                    $parts = explode('-', $latestApp->application_ref);
                    $number = intval(end($parts));
                    $count = $number + 1;
                } else {
                    $count = 1;
                }

                // Add an offset based on attempts to avoid repeating the same failed number in this request
                // checks if "exists" logic failed previously
                $count += $attempt; 
                
                // Hunt for a free slot if the calculated one is already taken (handling gaps or race conditions visible to exists())
                do {
                    $ref = 'ACETEL-SC-' . $year . '-' . str_pad($count, 6, '0', STR_PAD_LEFT);
                    $exists = \App\Models\Application::where('application_ref', $ref)->exists();
                    if ($exists) {
                        $count++;
                    }
                } while ($exists);

                $application = \App\Models\Application::create([
                    'user_id' => auth()->id(),
                    'application_ref' => $ref,
                    'surname' => $validated['surname'],
                    'first_name' => $validated['first_name'],
                    'other_name' => $validated['other_name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'gender' => $validated['gender'],
                    'date_of_birth' => $validated['date_of_birth'],
                    'address' => $validated['address'],
                    'country' => $validated['country'],
                    'state' => $validated['state'],
                    'lga' => $validated['lga'],
                    
                    'highest_qualification' => $validated['highest_qualification'] ?? null,
                    'ssce_type' => $validated['ssce_type'] ?? null,
                    'ssce_year' => $validated['ssce_year'] ?? null,
                    'ssce_exam_number' => $validated['ssce_exam_number'] ?? null,
                    
                    'degree_type' => $validated['degree_type'] ?? null,
                    'degree_institution' => $validated['degree_institution'] ?? null,
                    'degree_year' => $validated['degree_year'] ?? null,
                    'degree_class' => $validated['degree_class'] ?? null,

                    'short_course_id' => $course->id,
                    'amount' => $course->fee, // Store frozen amount
                    'payment_status' => 'PENDING',
                    'locale' => app()->getLocale(),
                ]);

                // Store Additional Certifications
                if (!empty($validated['additional_certifications'])) {
                    foreach ($validated['additional_certifications'] as $cert) {
                        \DB::table('application_qualifications')->insert([
                            'application_id' => $application->id,
                            'name' => $cert['name'],
                            'institution' => $cert['institution'],
                            'year' => $cert['year'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }

                try {
                    \Illuminate\Support\Facades\Log::info('Dispatching ApplicationSubmitted email to: ' . $application->email);
                    \Illuminate\Support\Facades\Mail::to($application->email)->send(new \App\Mail\ApplicationSubmitted($application));
                    \Illuminate\Support\Facades\Log::info('ApplicationSubmitted email dispatched to queue.');
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error('Mail submission failed: ' . $e->getMessage());
                    \Illuminate\Support\Facades\Log::error($e->getTraceAsString());
                }

                break; // If successful, exit loop

            } catch (\Illuminate\Database\QueryException $e) {
                if ($e->errorInfo[1] == 1062) { // Duplicate entry
                    $attempt++;
                    sleep(1); // Small delay to allow the other transaction to settle
                    continue;
                }
                throw $e; // Throw other errors
            }
        }

        if (!$application) {
            return back()->with('error', 'Unable to generate unique application reference after multiple attempts. Please try again.');
        }

        return redirect()->route('applications.review', $application->application_ref)->with('success', 'Your application is successfully submitted');
    }


    public function review($ref)
    {
        $application = \App\Models\Application::where('application_ref', $ref)->with(['course', 'payments'])->firstOrFail();
        
        if ($application->payment_status == 'PAID') {
             // If already paid, redirect to receipt or status?
             // For now, allow viewing but maybe show different UI
        }

        return view('apply.review', compact('application'));
    }

    public function statusForm()
    {
        return view('apply.status');
    }

    public function checkStatus(Request $request)
    {
        $request->validate([
            'application_ref' => 'required|string',
            'email' => 'required|email',
        ]);

        $application = \App\Models\Application::where('application_ref', $request->application_ref)
            ->where('email', $request->email)
            ->first();

        if (!$application) {
            return back()->with('error', 'Application not found with provided details.');
        }

        return redirect()->route('applications.review', $application->application_ref);
    }

    public function downloadAdmission($ref)
    {
        $application = \App\Models\Application::where('application_ref', $ref)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($application->admission_status !== 'ADMITTED') {
            return back()->with('error', 'Admission letter is not available.');
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.admission_letter', compact('application'));
        return $pdf->download('admission_letter_' . $application->application_ref . '.pdf');
    }

    public function viewAdmission($ref)
    {
        $application = \App\Models\Application::where('application_ref', $ref)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($application->admission_status !== 'ADMITTED') {
            abort(403, 'Admission letter is not available.');
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.admission_letter', compact('application'));
        return $pdf->stream('admission_letter_' . $application->application_ref . '.pdf');
    }
    public function uploadReceipt(Request $request)
    {
        $request->validate([
            'application_ref' => 'required|exists:applications,application_ref',
            'payment_rrr' => 'required|string|max:50',
            'receipt' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        $application = \App\Models\Application::where('application_ref', $request->application_ref)->firstOrFail();

        \Illuminate\Support\Facades\Log::info('Upload Receipt Request', $request->all());
        \Illuminate\Support\Facades\Log::info('Has File receipt?', [$request->hasFile('receipt')]);
        
        $application->payment_rrr = $request->payment_rrr;

        if ($request->hasFile('receipt')) {
            $path = $request->file('receipt')->store('payment_receipts', 'public');
            \Illuminate\Support\Facades\Log::info('File Stored at: ' . $path);
            $application->payment_receipt_path = $path;
            $application->save();
        } else {
            \Illuminate\Support\Facades\Log::warning('No file found in request');
        }

        return back()->with('success', 'Payment receipt uploaded successfully. Awaiting confirmation.');
    }

    public function downloadPaymentProcedure()
    {
        $setting = \App\Models\Setting::where('key', 'payment_procedure_path')->first();

        if (!$setting || !$setting->value || !\Illuminate\Support\Facades\Storage::disk('public')->exists($setting->value)) {
            return back()->with('error', 'Payment procedure document not found.');
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->download($setting->value);
    }
}
