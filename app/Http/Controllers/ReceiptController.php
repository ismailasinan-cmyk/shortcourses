<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptController extends Controller
{
    public function download($ref)
    {
        $application = Application::where('application_ref', $ref)->with('course')->firstOrFail();

        if ($application->payment_status != 'PAID') {
            return redirect()->back()->with('error', 'Payment not completed or verified yet.');
        }

        $pdf = Pdf::loadView('pdf.receipt', compact('application'));
        return $pdf->download('ACETEL-Receipt-' . $application->application_ref . '.pdf');
    }

    public function view($ref)
    {
        $application = Application::where('application_ref', $ref)->with('course')->firstOrFail();

        if ($application->payment_status != 'PAID') {
            return redirect()->back()->with('error', 'Payment not completed or verified yet.');
        }

        $pdf = Pdf::loadView('pdf.receipt', compact('application'));
        return $pdf->stream('ACETEL-Receipt-' . $application->application_ref . '.pdf');
    }
}
