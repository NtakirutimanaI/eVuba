<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP - {{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text-main);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .card {
            background: var(--card-bg);
            padding: 40px;
            border-radius: 24px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 440px;
            text-align: center;
        }

        .icon-box {
            width: 64px;
            height: 64px;
            background: #eff6ff;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            color: var(--primary);
        }

        h1 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
            color: var(--text-main);
        }

        p {
            color: var(--text-muted);
            font-size: 15px;
            margin-bottom: 32px;
            line-height: 1.5;
        }

        .email-highlight {
            color: var(--text-main);
            font-weight: 600;
        }

        .otp-inputs {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin-bottom: 32px;
        }

        .otp-inputs input {
            width: 50px;
            height: 60px;
            border: 2px solid var(--border);
            border-radius: 12px;
            font-size: 24px;
            font-weight: 700;
            text-align: center;
            color: var(--text-main);
            transition: all 0.2s;
            outline: none;
        }

        .otp-inputs input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .btn-verify {
            width: 100%;
            padding: 14px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            margin-bottom: 24px;
        }

        .btn-verify:hover {
            background: var(--primary-hover);
        }

        .resend {
            font-size: 14px;
            color: var(--text-muted);
        }

        .resend a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .resend a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: #ef4444;
            font-size: 14px;
            margin-bottom: 16px;
            display: block;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="icon-box">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m12 14 4-4-4-4" />
                <path d="M3.34 19a10 10 0 1 1 17.32 0" />
            </svg>
        </div>
        <h1>Verification Code</h1>
        <p>We've sent a 6-digit code to <br><span class="email-highlight">{{ session('otp_email') }}</span></p>

        @if($errors->any())
            <span class="error-message">{{ $errors->first() }}</span>
        @endif

        <form action="{{ route('auth.verify-otp.submit') }}" method="POST" id="otp-form">
            @csrf
            <div class="otp-inputs">
                <input type="text" name="otp[]" maxlength="1" required autofocus>
                <input type="text" name="otp[]" maxlength="1" required>
                <input type="text" name="otp[]" maxlength="1" required>
                <input type="text" name="otp[]" maxlength="1" required>
                <input type="text" name="otp[]" maxlength="1" required>
                <input type="text" name="otp[]" maxlength="1" required>
            </div>
            <input type="hidden" name="full_otp" id="full_otp">
            <button type="submit" class="btn-verify">Verify Account</button>
        </form>

        <div class="resend">
            Didn't receive the code? <a href="{{ route('auth.resend-otp') }}">Resend Code</a>
        </div>
    </div>

    <script>
        const inputs = document.querySelectorAll('.otp-inputs input');
        const form = document.getElementById('otp-form');
        const fullOtpInput = document.getElementById('full_otp');

        inputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                if (e.target.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !e.target.value && index > 0) {
                    inputs[index - 1].focus();
                }
            });
        });

        form.addEventListener('submit', (e) => {
            let fullOtp = "";
            inputs.forEach(input => fullOtp += input.value);
            fullOtpInput.value = fullOtp;
        });
    </script>
</body>

</html>