<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; border: 1px solid #e1e4e8; border-radius: 12px; overflow: hidden; background: #fff; }
        .header { background: #14B8A6; color: white; padding: 30px; text-align: center; }
        .content { padding: 40px; }
        .footer { background: #f8f9fa; color: #6c757d; padding: 20px; text-align: center; font-size: 12px; }
        .status-box { background: #ecfdf5; border: 1px solid #14B8A6; padding: 20px; border-radius: 8px; margin: 20px 0; text-align: center; color: #065f46; }
        .btn { display: inline-block; padding: 12px 24px; background: #1E3A8A; color: white; text-decoration: none; border-radius: 9999px; font-weight: bold; margin-top: 20px; }
        .accent { color: #1E3A8A; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0;">Congratulations!</h1>
        </div>
        <div class="content">
            <p>Dear <span class="accent">{{ $application->surname }} {{ $application->first_name }}</span>,</p>
            <p>We are pleased to inform you that your application for the <span class="accent">{{ $application->course->course_name }}</span> has been <span style="color: #14B8A6; font-weight: bold;">APPROVED</span>.</p>
            
            <div class="status-box">
                <h3 style="margin: 0;">You have been ADMITTED!</h3>
            </div>

            <p>You can now log in to your dashboard to view your admission details and next steps for the commencement of your course.</p>

            <div style="text-align: center;">
                <a href="{{ route('home') }}" class="btn">Go to My Dashboard</a>
            </div>

            <p style="margin-top: 30px;">Once again, congratulations on your admission.<br>Best regards,<br>The ACETEL Team</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} ACETEL. Africa Centre of Excellence on Technology Enhanced Learning.
        </div>
    </div>
</body>
</html>
