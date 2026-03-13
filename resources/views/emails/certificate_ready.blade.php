<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; border: 1px solid #e1e4e8; border-radius: 12px; overflow: hidden; background: #fff; }
        .header { background: #1E3A8A; color: white; padding: 30px; text-align: center; }
        .content { padding: 40px; }
        .footer { background: #f8f9fa; color: #6c757d; padding: 20px; text-align: center; font-size: 12px; }
        .ref-box { background: #f0f7ff; border: 1px dashed #1E3A8A; padding: 15px; border-radius: 8px; margin: 20px 0; text-align: center; }
        .ref-number { font-family: monospace; font-size: 20px; font-weight: bold; color: #1E3A8A; }
        .btn { display: inline-block; padding: 12px 24px; background: #14B8A6; color: white !important; text-decoration: none; border-radius: 9999px; font-weight: bold; margin-top: 20px; }
        .accent { color: #14B8A6; font-weight: bold; }
        .congrats { color: #1E3A8A; font-size: 24px; font-weight: bold; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0;">Certificate Ready</h1>
        </div>
        <div class="content">
            <div class="congrats">Congratulations!</div>
            <p>Dear <span class="accent">{{ $application->surname }} {{ $application->first_name }}</span>,</p>
            <p>We are pleased to inform you that your certificate for the <span class="accent">{{ $application->course->course_name }}</span> is now ready for download.</p>
            
            <div class="ref-box">
                <div style="font-size: 14px; margin-bottom: 5px; color: #666;">Application Reference:</div>
                <div class="ref-number">{{ $application->application_ref }}</div>
            </div>

            <p>You can access your certificate by logging into your dashboard and clicking on the <strong>"Download Certificate"</strong> option under your application details.</p>

            <div style="text-align: center;">
                <a href="{{ route('home') }}" class="btn">Go to My Dashboard</a>
            </div>

            <p style="margin-top: 30px;">Best regards,<br>The ACETEL Team</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} ACETEL. Africa Centre of Excellence on Technology Enhanced Learning.
        </div>
    </div>
</body>
</html>
