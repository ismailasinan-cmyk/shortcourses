<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Admission Letter</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .container {
            width: 100%;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 2px solid #14B8A6;
            padding-bottom: 20px;
        }
        .logo {
            max-width: 100px;
            margin-bottom: 15px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            color: #1E3A8A;
            margin: 0;
        }
        .subtitle {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }
        .content {
            margin-bottom: 40px;
        }
        .recipient {
            margin-bottom: 30px;
        }
        .recipient-name {
            font-weight: bold;
            font-size: 18px;
        }
        .details-box {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .details-row {
            margin-bottom: 10px;
        }
        .label {
            font-weight: bold;
            width: 150px;
            display: inline-block;
        }
        .signature {
            margin-top: 60px;
        }
        .signature-line {
            width: 200px;
            border-top: 1px solid #333;
            margin-bottom: 10px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ public_path('images/acetel-logo.jpeg') }}" class="logo" alt="Logo">
            <h1 class="title">{{ config('app.name') }}</h1>
            <div class="subtitle">Africa Centre of Excellence on Technology Enhanced Learning</div>
        </div>

        <div class="content">
            <div class="recipient">
                <div>{{ date('F d, Y') }}</div>
                <br>
                <div class="recipient-name">{{ $application->surname }} {{ $application->first_name }} {{ $application->other_name }}</div>
                <div>{{ $application->address }}</div>
                <div>{{ $application->state }}, {{ $application->lga }}</div>
            </div>

            <h2 style="text-align: center; margin: 20px 0; font-size: 18px; border-bottom: 1px solid #eee; padding-bottom: 10px;">OFFICIAL LETTER OF ADMISSION</h2>

            <p>Dear {{ $application->first_name }},</p>

            <p>We are pleased to inform you that you have been offered provisional admission into the <strong>{{ $application->course->course_name }}</strong> short course program at ACETEL.</p>

            <p style="margin-bottom: 15px;">Your admission is based on the information provided in your application form and is subject to verification of your credentials.</p>

            <div class="details-box">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td class="label">Application Ref:</td>
                        <td>{{ $application->application_ref }}</td>
                    </tr>
                    <tr>
                        <td class="label">Course:</td>
                        <td>{{ $application->course->course_name }}</td>
                    </tr>
                    <tr>
                        <td class="label">Duration:</td>
                        <td>{{ $application->course->duration }}</td>
                    </tr>
                    <tr>
                        <td class="label">Tuition Fee:</td>
                        <td>₦{{ number_format($application->amount, 2) }} <span style="font-size: 12px; color: green; font-weight: bold;">(PAID)</span></td>
                    </tr>
                </table>
            </div>

            <p>Please retain this letter as proof of your admission. You will receive further instructions regarding the commencement of lectures via your registered email address.</p>

            <p>Congratulations on your admission!</p>
        </div>

        <div class="signature">
            <div class="signature-line"></div>
            <div><strong>Registrar</strong></div>
            <div>ACETEL Short Courses</div>
        </div>

        <div class="footer">
            <p>National Open University of Nigeria (NOUN), University Village, Plot 91, Cadastral Zone, Nnamdi Azikiwe Expressway, Jabi, Abuja.</p>
            <p style="margin-top: 5px;">This document is electronically generated and is valid without a physical signature. Generated on {{ now()->format('Y-m-d') }}</p>
        </div>
    </div>
</body>
</html>
