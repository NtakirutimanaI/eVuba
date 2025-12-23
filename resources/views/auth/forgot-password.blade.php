<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Forgot Password</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
    body {
      min-height: 100vh;
      background: #ffffff;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px;
    }
    .container {
      width: 480px;
      max-width: 100%;
      background: #ffffff;
      border-radius: 24px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      padding: 56px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      border: 1px solid #e5e7eb;
    }
    h2 { 
      font-size: 26px; 
      margin-bottom: 6px; 
      color: #020617;
      font-weight: 600;
    }
    p { 
      color: #6b7280; 
      margin-bottom: 28px; 
      font-size: 14px;
    }
    .input-group { 
      margin-bottom: 18px; 
    }
    .input-group label {
      display: block;
      font-size: 13px;
      margin-bottom: 6px;
      color: #0f172a;
      font-weight: 500;
    }
    .input-group input {
      width: 100%;
      padding: 12px 14px;
      border-radius: 10px;
      border: 1px solid #e5e7eb;
      font-size: 14px;
      background: #f9fafb;
      transition: all 0.2s;
    }
    .input-group input:focus {
      outline: none;
      border-color: #111827;
      box-shadow: 0 0 0 1px rgba(0,0,0,0.2);
      background: #ffffff;
    }
    button {
      width: 100%;
      padding: 13px;
      border-radius: 999px;
      border: none;
      background: linear-gradient(90deg, #111827, #000000);
      color: #fff;
      font-size: 14px;
      font-weight: 500;
      cursor: pointer;
      margin-top: 8px;
      box-shadow: 0 18px 40px rgba(0,0,0,0.35);
      transition: all 0.2s;
    }
    button:hover {
      background: linear-gradient(90deg, #000000, #111827);
      transform: translateY(-1px);
      box-shadow: 0 20px 45px rgba(0,0,0,0.4);
    }
    .message {
      margin-top: 16px;
      font-size: 13px;
      color: #059669;
      padding: 12px;
      background: #d1fae5;
      border-radius: 8px;
      text-align: center;
    }
    .error-message {
      margin-top: 6px;
      font-size: 13px;
      color: #dc2626;
    }
    .back {
      margin-top: 24px;
      text-align: center;
      font-size: 13px;
      color: #6b7280;
    }
    .back a {
      color: #111827;
      font-weight: 600;
      text-decoration: none;
    }
    .back a:hover {
      text-decoration: underline;
    }
    @media(max-width: 600px) {
      .container { 
        width: 100%; 
        padding: 40px 32px;
      }
    }
  </style>
</head>
<body>
<div class="container">
  <h2>Forgot Password?</h2>
  <p>Enter your email and we'll send you a reset link.</p>

  <!-- Success message -->
  @if (session('status'))
      <div class="message">{{ session('status') }}</div>
  @endif

 <form id="forgotPasswordForm" method="POST" action="{{ route('password.email') }}">
      @csrf
      <div class="input-group">
          <label for="email">Email Address</label>
          <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required autofocus>
          @error('email')
              <div class="error-message">{{ $message }}</div>
          @enderror
          <div class="error-message" id="emailError" style="display:none;"></div>
      </div>
      <button type="submit">Send Password Reset Link</button>
  </form>

  <div class="back">
    Remembered your password? <a href="{{ route('auth.login') }}">Back to Login</a>
  </div>
</div>

<script>
  document.getElementById('forgotPasswordForm').addEventListener('submit', function(e) {
      const emailField = document.getElementById('email');
      const emailError = document.getElementById('emailError');
      const emailValue = emailField.value.trim();

      // Simple email regex
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

      if(!emailPattern.test(emailValue)) {
          e.preventDefault();
          emailError.textContent = "Please enter a valid email address.";
          emailError.style.display = "block";
          emailField.focus();
      } else {
          emailError.style.display = "none";
      }
  });
</script>
</body>
</html>
