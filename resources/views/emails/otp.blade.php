<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Verification Code</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: #334155;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 40px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .otp-code {
            font-size: 32px;
            font-weight: 700;
            color: #2563eb;
            text-align: center;
            letter-spacing: 5px;
            margin: 30px 0;
            padding: 20px;
            background: #f8fafc;
            border-radius: 8px;
        }

        .footer {
            font-size: 12px;
            color: #94a3b8;
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>Verify Your Identity</h2>
        </div>
        <p>Hello {{ $name }},</p>
        <p>Your verification code for <strong>{{ config('app.name') }}</strong> is:</p>
        <div class="otp-code">{{ $otp }}</div>
        <p>This code will expire in 10 minutes. If you did not request this, please ignore this email.</p>
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>

</html>