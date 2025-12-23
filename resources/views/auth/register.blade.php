<div class="container">
  <!-- LEFT SIDE -->
  <div class="left">
    <div class="overlay"></div>
    <div class="left-content-wrapper">
      <div class="left-title">
        <h1>E-VUBA <span class="arrow">CONNECT</span></h1>
      </div>
      <div class="left-features">
        <p>Partner with us</p>
        <p>&</p>
        <p>Transform Your Business</p>
      </div>
    </div>
  </div>

  <!-- RIGHT SIDE: REGISTER FORM -->
  <div class="right">
    <div class="signup-form" style="display:block;">
      <h2>Create Account</h2>
      <p>Sign up to get started.</p>

      <form method="POST" action="{{ route('auth.register.submit') }}">
        @csrf

        <div class="input-group">
          <label for="name">Name</label>
          <input id="name" type="text" name="name" value="{{ old('name') }}" required placeholder="Full name">
          @error('name')
            <div style="color:red;font-size:12px;">{{ $message }}</div>
          @enderror
        </div>

        <div class="input-group">
          <label for="email">Email</label>
          <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="Email address">
          @error('email')
            <div style="color:red;font-size:12px;">{{ $message }}</div>
          @enderror
        </div>

        <div class="input-group">
          <label for="password">Password</label>
          <input id="password" type="password" name="password" required placeholder="Create password">
          @error('password')
            <div style="color:red;font-size:12px;">{{ $message }}</div>
          @enderror
        </div>

        <div class="input-group">
          <label for="password_confirmation">Confirm Password</label>
          <input id="password_confirmation" type="password" name="password_confirmation" required placeholder="Confirm password">
        </div>

        <button type="submit">Create Account</button>
      </form>

      <div class="divider">Or, Sign up with</div>

      <a href="{{ route('google.redirect') }}" class="google">
        <img src="{{ asset('images/google.png') }}" alt="Google logo" style="height:16px; margin-right:6px;">
        Sign up with Google
      </a>

      <div class="toggle">
        Already have an account? <a href="{{ route('auth.login') }}">Login here</a>
      </div>

      <div class="back-btn">
        <a href="{{ url('/') }}">← Back to Website</a>
      </div>
    </div>
  </div>
</div>

<style>
  * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
  body { background: #f2f2f2; display: flex; justify-content: center; align-items: center; min-height: 100vh; }

  .container {
    width: 900px;
    height: 600px;
    background: #fff;
    border-radius: 18px;
    display: flex;
    overflow: hidden;
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
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
    background: linear-gradient(180deg, rgba(22,62,170,0.85), rgba(1,19,49,0.85));
  }

  .overlay { display: none; }

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

  .right h2 { font-size: 26px; margin-bottom: 6px; font-weight: 700; }
  .right p { color: #666; margin-bottom: 20px; font-size: 14px; }
  .input-group { margin-bottom: 16px; }
  .input-group label { display: block; font-size: 12px; margin-bottom: 4px; }
  .input-group input { width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ddd; font-size: 14px; }

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
  button:hover { background: #333; }

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
  }

  .divider {
    text-align: center;
    font-size: 12px;
    margin: 10px 0;
    color: #999;
    position: relative;
  }
  .divider::before, .divider::after {
    content: '';
    height: 1px;
    background-color: #ddd;
    position: absolute;
    top: 50%;
    width: 40%;
  }
  .divider::before { left: 0; }
  .divider::after { right: 0; }

  .toggle { text-align: center; font-size: 12px; margin-top: 10px; }
  .toggle a { color: #6c63ff; font-weight: 600; text-decoration: none; }
  .toggle a:hover { text-decoration: underline; }

  .back-btn { text-align: center; margin-top: 12px; }
  .back-btn a { text-decoration: none; color: #6c63ff; font-weight: 600; }
  .back-btn a:hover { text-decoration: underline; }

  @media(max-width: 900px) {
    .container { flex-direction: column; height: auto; width: 95%; }
    .left { min-height: 350px; padding: 30px 20px; border-radius: 18px 18px 0 0; }
    .right { padding: 30px 20px; }
  }
</style>
