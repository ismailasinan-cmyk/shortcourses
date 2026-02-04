<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; border: 1px solid #e1e4e8; border-radius: 12px; overflow: hidden; background: #fff; }
        .header { background: #059669; color: white; padding: 30px; text-align: center; }
        .content { padding: 40px; }
        .footer { background: #f8f9fa; color: #6c757d; padding: 20px; text-align: center; font-size: 12px; }
        .amount-box { background: #ecfdf5; border: 1px solid #059669; padding: 20px; border-radius: 8px; margin: 20px 0; text-align: center; color: #065f46; }
        .amount { font-size: 24px; font-weight: bold; }
        .btn { display: inline-block; padding: 12px 24px; background: #059669; color: white; text-decoration: none; border-radius: 9999px; font-weight: bold; margin-top: 20px; }
        .accent { color: #059669; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0;">Payment Successful</h1>
        </div>
        <div class="content">
            <p>Dear <span class="accent">{{ $application->surname }} {{ $application->first_name }}</span>,</p>
            <p>We have successfully received your payment for the course: <span class="accent">{{ $application->course->course_name }}</span>.</p>
            
            <div class="amount-box">
                <div style="font-size: 14px; margin-bottom: 5px;">Amount Paid</div>
                <div class="amount">₦{{ number_format($application->amount, 2) }}</div>
                <div style="font-size: 14px; margin-top: 5px; color: #666;">Ref: {{ $application->application_ref }}</div>
            </div>

            <p>Please find your official receipt attached to this email.</p>
            
            <p>Your application is now being processed for admission review. You can check your status on your dashboard.</p>

            <div style="text-align: center;">
                <a href="{{ route('home') }}" class="btn">View My Dashboard</a>
            </div>

            <p style="margin-top: 30px;">Thank you for choosing ACETEL.<br>Best regards,<br>The ACETEL Team</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} ACETEL. Africa Centre of Excellence on Technology Enhanced Learning.
        </div>
    </div>
</body>
</html>
