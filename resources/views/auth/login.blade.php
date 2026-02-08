<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - eVuba Connect</title>
    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/auth-v2.css') }}">
</head>

<body>

    <div class="login-main-wrapper">

        <!-- LEFT: INFO PANEL -->
        <div class="left-panel">
            <div class="brand-pill">
                eVuba Connect
            </div>

            <h1 class="panel-heading">
                Join and Partner with us in <br><span>three quick steps</span>
            </h1>

            <p class="panel-text">
                Access your dashboard and manage services efficiently.
            </p>

            <div class="steps-list">
                <!-- Step 1 -->
                <div class="step-item">
                    <div class="step-number">01</div>
                    <div class="step-content">
                        <h4>Login Securely</h4>
                        <p>Enter your credentials to access your personal dashboard.</p>
                    </div>
                </div>
                <!-- Step 2 -->
                <div class="step-item">
                    <div class="step-number">02</div>
                    <div class="step-content">
                        <h4>Manage Services</h4>
                        <p>Request support, order equipment, or upgrade cloud plans.</p>
                    </div>
                </div>
                <!-- Step 3 -->
                <div class="step-item">
                    <div class="step-number">03</div>
                    <div class="step-content">
                        <h4>Get Support</h4>
                        <p>Connect with our expert team 24/7 for any assistance.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT: LOGIN FORM -->
        <div class="right-panel">
            <div class="form-header">
                <h2>Welcome back</h2>
                <p>Please enter your details to sign in.</p>
            </div>

            <!-- Messages -->
            @if (session('error'))
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif
            @if (session('status'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('auth.login.submit') }}">
                @csrf

                <!-- Email -->
                <div class="input-group">
                    <label for="email">Email Address</label>
                    <input id="email" type="email" name="email" class="input-field" value="{{ old('email') }}" required
                        autofocus placeholder="name@company.com">
                    @error('email')
                        <div class="error-msg"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="input-group" x-data="{ show: false }">
                    <label for="password">Password</label>
                    <div style="position:relative;">
                        <input id="password" :type="show ? 'text' : 'password'" name="password" class="input-field"
                            required placeholder="Enter your password">
                        <button type="button" @click="show = !show" class="toggle-password">
                            <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <div class="options-row">
                    <label class="remember-me">
                        <input type="checkbox" name="remember"> Remember me
                    </label>
                    <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                </div>

                <button type="submit" class="btn-submit">Sign In</button>
            </form>

            <div class="divider">
                <div class="divider-line"></div>
                OR
                <div class="divider-line"></div>
            </div>

            <a href="{{ route('google.redirect') }}" class="btn-submit btn-google">
                <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" width="18" height="18"
                    alt="Google icon">
                Sign in with Google
            </a>

            <div class="footer-text">
                Don't have an account? <a href="{{ route('auth.register') }}">Sign up</a>
            </div>

            <div class="back-link">
                <a href="{{ url('/') }}">← Back to Home</a>
            </div>
        </div>
    </div>

</body>

</html>