<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;
use Barryvdh\DomPDF\Facade\Pdf;

class PaymentReceipt extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $timeout = 120;

    public $application;

    public function __construct($application)
    {
        $this->application = $application;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Receipt - ACETEL Short Courses',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment_receipt',
        );
    }

    public function attachments(): array
    {
        $pdf = Pdf::loadView('pdf.receipt', ['application' => $this->application]);
        
        return [
            Attachment::fromData(fn () => $pdf->output(), 'Receipt-' . $this->application->application_ref . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
