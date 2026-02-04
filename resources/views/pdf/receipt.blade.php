<!DOCTYPE html>
<html>
<head>
    <title>Payment Receipt</title>
    <style>
        body { font-family: sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .details { width: 100%; border-collapse: collapse; }
        .details th, .details td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .footer { margin-top: 30px; text-align: center; font-size: 0.8em; }
    </style>
</head>
<body>
    <div class="header">
        <h1>ACETEL Short Courses</h1>
        <h3>Payment Receipt</h3>
    </div>

    <table class="details">
        <tr>
            <th>Application Ref</th>
            <td>{{ $application->application_ref }}</td>
        </tr>
        <tr>
            <th>Date</th>
            <td>{{ $application->updated_at->format('d M Y') }}</td>
        </tr>
        <tr>
            <th>Name</th>
            <td>{{ $application->surname }} {{ $application->first_name }} {{ $application->other_name }}</td>
        </tr>
        <tr>
            <th>Course</th>
            <td>{{ $application->course->course_name }}</td>
        </tr>
        <tr>
            <th>Amount Paid</th>
            <td>₦{{ number_format($application->amount, 2) }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>{{ $application->payment_status }}</td>
        </tr>
    </table>

    <div class="footer">
        <p>This is an electronically generated receipt.</p>
        <p>ACETEL - Africa Centre of Excellence on Technology Enhanced Learning</p>
    </div>
</body>
</html>
