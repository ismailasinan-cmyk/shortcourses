<x-mail::message>
# Payment Receipt Verification Required

A new payment receipt has been uploaded by an applicant and requires your verification.

**Applicant Details:**
- **Name:** {{ $application->surname }} {{ $application->first_name }}
- **Reference:** {{ $application->application_ref }}
- **Course:** {{ $application->course->course_name }}

**Payment Details:**
- **RRR:** {{ $payment->remita_rrr }}
- **Amount:** ₦{{ number_format($payment->amount, 2) }}
- **Type:** {{ str_replace('_', ' ', $payment->payment_type) }}

Please log in to the admin portal to review and approve this payment.

<x-mail::button :url="route('login')">
Log In to Admin Portal
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
