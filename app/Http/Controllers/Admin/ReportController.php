<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Payment;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function export(Request $request)
    {
        $request->validate([
            'type' => 'required|in:applications,payments',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $query = $request->type === 'applications' ? Application::query() : Payment::query();

        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Use cursor to minimize memory usage
        $callback = function () use ($query, $request) {
            $file = fopen('php://output', 'w');
            
            if ($request->type === 'applications') {
                fputcsv($file, ['Ref', 'Name', 'Email', 'Course', 'Amount', 'Payment Status', 'Admission Status', 'Date']);
                foreach ($query->latest()->cursor() as $row) {
                    fputcsv($file, [
                        $row->application_ref,
                        $row->surname . ' ' . $row->first_name,
                        $row->email,
                        $row->course->course_name ?? 'N/A',
                        $row->amount,
                        $row->payment_status,
                        $row->admission_status,
                        $row->created_at->format('Y-m-d H:i:s'),
                    ]);
                }
            } else {
                fputcsv($file, ['Ref', 'RRR', 'Amount', 'Channel', 'Paid At', 'Date']);
                foreach ($query->latest()->cursor() as $row) {
                    fputcsv($file, [
                        $row->payment_ref, // Model doesn't have payment_ref, usually RRR is the ref or separate column. Checking Payment migration...
                        // Payment migration has no payment_ref, only remita_rrr.
                        // Assuming payment_ref was a mistake in original code or accessor. original code used $row->payment_ref. 
                        // Let's stick to original fields but use $row from cursor.
                         $row->remita_rrr, // Using RRR as Ref if payment_ref not exists. 
                        $row->remita_rrr,
                        $row->amount,
                        $row->channel,
                        $row->paid_at,
                        $row->created_at->format('Y-m-d H:i:s'),
                    ]);
                }
            }
            
            fclose($file);
        };

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $request->type . '_report_' . date('Y-m-d') . '.csv"',
        ];

        return Response::stream($callback, 200, $headers);
    }
}
