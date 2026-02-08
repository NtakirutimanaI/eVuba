<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - eVuba Connect</title>
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
                Partner with us<br><span>& Transform</span>
            </h1>

            <p class="panel-text">
                Your reliable IT infrastructure starts here.
            </p>

            <div class="steps-list">
                <div class="step-item">
                    <div class="step-number">01</div>
                    <div class="step-content">
                        <h4>Create Account</h4>
                        <p>Fill in your details to join eVuba Connect.</p>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-number">02</div>
                    <div class="step-content">
                        <h4>Verify Email</h4>
                        <p>Secure your account with a quick verification.</p>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-number">03</div>
                    <div class="step-content">
                        <h4>Start Exploring</h4>
                        <p>Access our cloud, software, and hardware services.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT: REGISTER FORM -->
        <div class="right-panel">
            <div class="form-header">
                <h2>Create Account</h2>
                <p>Sign up to get started.</p>
            </div>

            <!-- Messages -->
            @if (session('error'))
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('auth.register.submit') }}">
                @csrf

                <!-- Name -->
                <div class="input-group">
                    <label for="name">Full Name</label>
                    <input id="name" type="text" name="name" class="input-field" value="{{ old('name') }}" required
                        placeholder="Faustin Ndayishimiye">
                    @error('name')
                        <div class="error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="input-group">
                    <label for="email">Email Address</label>
                    <input id="email" type="email" name="email" class="input-field" value="{{ old('email') }}" required
                        placeholder="name@company.com">
                    @error('email')
                        <div class="error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="input-group" x-data="{ show: false }">
                    <label for="password">Password</label>
                    <div style="position:relative;">
                        <input id="password" :type="show ? 'text' : 'password'" name="password" class="input-field"
                            required placeholder="Create a password">
                        <button type="button" @click="show = !show" class="toggle-password">
                            <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="input-group" x-data="{ show: false }">
                    <label for="password_confirmation">Confirm Password</label>
                    <div style="position:relative;">
                        <input id="password_confirmation" :type="show ? 'text' : 'password'"
                            name="password_confirmation" class="input-field" required
                            placeholder="Confirm your password">
                        <button type="button" @click="show = !show" class="toggle-password">
                            <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-submit">Sign Up</button>
            </form>

            <div class="divider">
                <div class="divider-line"></div>
                OR
                <div class="divider-line"></div>
            </div>

            <a href="{{ route('google.redirect') }}" class="btn-submit btn-google">
                <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" width="18" height="18"
                    alt="Google icon">
                Sign up with Google
            </a>

            <div class="footer-text">
                Already have an account? <a href="{{ route('auth.login') }}">Login</a>
            </div>

            <div class="back-link">
                <a href="{{ url('/') }}">← Back to Home</a>
            </div>
        </div>
    </div>

</body>

</html>