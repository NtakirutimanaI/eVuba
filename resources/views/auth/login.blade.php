<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<div class="container">
  <!-- LEFT SIDE -->
  <div class="left">
    <div class="overlay"></div>
    <div class="left-content-wrapper">
      <div class="left-title">
        <h1>e-Vuba<span class="arrow">Connect</span></h1>
      </div>
      <div class="left-features">
        <p>Partner with us</p>
        <p>&</p>
        <p>Transform Your Business with reliable IT infrastructure</p>
      </div>
    </div>
  </div>

  <!-- RIGHT SIDE: LOGIN FORM -->
  <div class="right">
    <div class="login-form">
      <h2>Welcome Back</h2>
      <p>Log in to start creating connections</p>

      <!-- Display session messages -->
      @if (session('error'))
        <div style="color:red;font-size:13px; margin-bottom:8px;">
          {{ session('error') }}
        </div>
      @endif
      @if (session('status'))
        <div style="color:green;font-size:13px; margin-bottom:8px;">
          {{ session('status') }}
        </div>
      @endif

      <form method="POST" action="{{ route('auth.login.submit') }}">
        @csrf

        <div class="input-group">
          <label for="email">Email</label>
          <input id="email" type="email" name="email" value="{{ old('email') }}" required
            placeholder="Enter your email">
          @error('email')
            <div style="color:red;font-size:12px;">{{ $message }}</div>
          @enderror
        </div>

        <div class="input-group" x-data="{ show: false }">
          <label for="password">Password</label>
          <div style="position: relative;">
            <input id="password" :type="show ? 'text' : 'password'" name="password" required
              placeholder="Enter your password" style="padding-right: 40px;">
            <button type="button" @click="show = !show" class="toggle-btn">
              <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
            </button>
          </div>
          @error('password')
            <div style="color:red;font-size:12px;">{{ $message }}</div>
          @enderror
        </div>

        <div class="options">
          <label><input type="checkbox" name="remember"> Remember me</label>
          <a href="{{ route('password.request') }}">Forgot password?</a>
        </div>

        <!-- Submit button -->
        <button type="submit">Login</button>
      </form>

      <!-- Divider for Google -->
      <div class="divider">Or, Sign up with</div>

      <!-- Google Login Button -->
      <a href="{{ route('google.redirect') }}" class="google">
        <img src="{{ asset('images/google.png') }}" alt="Google logo" style="height:16px; margin-right:6px;">
        Continue with Google
      </a>

      <!-- Sign up link -->
      <div class="toggle">
        Don't have an account? <a href="{{ route('auth.register') }}">Sign Up</a>
      </div>

      <!-- Back to website link -->
      <div class="back-btn">
        <a href="{{ url('/') }}">← Back to Website</a>
      </div>
    </div>
  </div>
</div>

<style>
  * {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    font-family: 'Inter', sans-serif;
  }

  body {
    background: #f2f2f2;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
  }

  .container {
    width: 900px;
    height: 550px;
    background: #fff;
    border-radius: 18px;
    display: flex;
    overflow: hidden;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
  }

  /* LEFT SIDE STYLES */
  .left {
    flex: 1;
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 40px 20px;
    flex-direction: column;
    text-align: center;
    color: #fff;
    background: linear-gradient(180deg, rgba(22, 62, 170, 0.85), rgba(1, 19, 49, 0.85));
  }

  .overlay {
    display: none;
  }

  .left-content-wrapper {
    position: relative;
    z-index: 2;
    display: flex;
    flex-direction: column;
    gap: 25px;
    width: 100%;
    max-width: 300px;
    text-align: center;
  }

  .left-title h1 {
    font-size: 28px;
    font-weight: 700;
    line-height: 1.3;
    margin: 6px 0;
  }

  .left-features p {
    font-size: 15px;
    margin: 6px 0;
  }

  .arrow {
    font-weight: bold;
    margin-left: 8px;
    color: #0A1128;
    background: #fff;
    padding: 2px 6px;
    border-radius: 4px;
  }

  /* RIGHT SIDE FORM STYLES */
  .right {
    flex: 1;
    padding: 50px 30px;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  .right h2 {
    font-size: 26px;
    margin-bottom: 6px;
    font-weight: 700;
  }

  .right p {
    color: #666;
    margin-bottom: 20px;
    font-size: 14px;
  }

  .input-group {
    margin-bottom: 16px;
  }

  .input-group label {
    display: block;
    font-size: 12px;
    margin-bottom: 4px;
  }

  .input-group input {
    width: 100%;
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #ddd;
    font-size: 14px;
  }

  .options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    font-size: 13px;
    color: #555;
  }


  button {
    width: 100%;
    padding: 14px;
    border-radius: 30px;
    border: none;
    background: #111;
    color: #fff;
    font-size: 14px;
    cursor: pointer;
    margin-bottom: 12px;
    transition: 0.3s;
  }

  button:hover {
    background: #333;
  }

  /* Divider */
  .divider {
    text-align: center;
    font-size: 12px;
    margin: 10px 0;
    color: #999;
    position: relative;
  }

  .divider::before,
  .divider::after {
    content: '';
    height: 1px;
    background-color: #ddd;
    position: absolute;
    top: 50%;
    width: 40%;
  }

  .divider::before {
    left: 0;
  }

  .divider::after {
    right: 0;
  }

  /* Google Button */
  .google {
    background: #fff;
    border: 1px solid #ddd;
    color: #333;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    padding: 12px;
    border-radius: 25px;
    margin-bottom: 12px;
    font-weight: 500;
  }

  /* Sign Up Link */
  .toggle {
    text-align: center;
    font-size: 12px;
    margin-top: 12px;
  }

  .toggle a {
    color: #6c63ff;
    font-weight: 600;
    text-decoration: none;
    transition: 0.3s;
  }

  .toggle a:hover {
    text-decoration: underline;
  }

  /* Back button */
  .back-btn {
    text-align: center;
    margin-top: 12px;
  }

  .back-btn a {
    text-decoration: none;
    color: #0A1128;
    font-weight: 600;
  }

  .back-btn a:hover {
    text-decoration: underline;
  }

  @media(max-width: 900px) {
    .container {
      flex-direction: column;
      height: auto;
      width: 95%;
    }

    .left {
      min-height: 350px;
      padding: 30px 20px;
      border-radius: 18px 18px 0 0;
    }

    .right {
      padding: 30px 20px;
    }
  }

  .toggle-btn {
    position: absolute !important;
    right: 12px !important;
    top: 50% !important;
    transform: translateY(-50%) !important;
    background: transparent !important;
    border: none !important;
    width: auto !important;
    padding: 0 !important;
    margin: 0 !important;
    color: #777 !important;
    cursor: pointer !important;
    font-size: 16px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    height: auto !important;
    z-index: 10;
  }

  .toggle-btn:hover {
    background: transparent !important;
    color: #333 !important;
  }
</style>