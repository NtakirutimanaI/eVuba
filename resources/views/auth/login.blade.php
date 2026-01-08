<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - eVuba Connect</title>
    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #f8fafc; /* Very light slate gray */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #334155;
        }

        .login-main-wrapper {
            width: 1000px;
            height: 650px;
            background: #ffffff;
            border-radius: 24px;
            display: flex;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
        }

        /* LEFT SIDE - BLUE PANEL */
        .left-panel {
            flex: 1;
            background: linear-gradient(135deg, #090e24, #1e3a8a, #1d4ed8);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px 50px;
            position: relative;
            color: #ffffff;
            overflow: hidden;
        }
        
         /* Decorative Circle */
        .left-panel::after {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
        }

        .brand-pill {
            display: inline-flex;
            align-items: center;
            padding: 12px 24px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 30px;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin-bottom: 40px;
            width: fit-content;
            text-transform: uppercase;
        }

        .panel-heading {
            font-size: 36px;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 20px;
        }
        
        .panel-heading span {
            color: #93c5fd; /* Soft Blue highlight */
        }

        .panel-text {
            font-size: 16px;
            line-height: 1.6;
            color: #cbd5e1;
            margin-bottom: 60px;
        }

        .steps-list {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .step-item {
            display: flex;
            gap: 16px;
            align-items: flex-start;
        }

        .step-number {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            background: #ffffff;
            color: #1e3a8a;
            font-weight: 700;
            border-radius: 8px;
            font-size: 14px;
            flex-shrink: 0;
        }

        .step-content h4 {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .step-content p {
            font-size: 13px;
            color: #94a3b8;
            line-height: 1.4;
        }

        /* RIGHT SIDE - FORM */
        .right-panel {
            flex: 1.1;
            padding: 50px 70px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: #ffffff;
        }

        .form-header {
            margin-bottom: 32px;
        }

        .form-header h2 {
            font-size: 28px;
            font-weight: 800;
            color: #0f172a; /* Dark slate */
            margin-bottom: 8px;
        }

        .form-header p {
            color: #64748b;
            font-size: 14px;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
        }

        .input-field {
            width: 100%;
            padding: 14px 16px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            color: #1e293b;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .input-field:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .input-field::placeholder {
            color: #94a3b8;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: #0f172a; /* Dark Navy for contrast */
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .btn-submit:hover {
            background: #1e293b;
            transform: translateY(-1px);
        }

        .options-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            font-size: 13px;
        }
        
        .forgot-link {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
        }

        .toggle-password {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: none;
            color: #94a3b8;
            cursor: pointer;
        }

        .back-link {
            text-align: center;
            margin-top: 30px;
        }
        
        .back-link a {
            color: #64748b;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: color 0.2s;
        }
        
        .back-link a:hover {
            color: #0f172a;
        }
        
        .alert {
            padding: 12px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
        }
        .alert-error { background: #fef2f2; border: 1px solid #fee2e2; color: #dc2626; }
        .alert-success { background: #f0fdf4; border: 1px solid #dcfce7; color: #16a34a; }

        @media (max-width: 900px) {
            .login-main-wrapper {
                flex-direction: column;
                width: 95%;
                height: auto;
            }
            .left-panel {
                padding: 40px 30px;
                min-height: auto;
            }
            .right-panel {
                padding: 40px 30px;
            }
            .panel-heading { font-size: 28px; }
        }
    </style>
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
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif
            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('auth.login.submit') }}">
                @csrf
                
                <!-- Email -->
                <div class="input-group">
                    <label for="email">Email Address</label>
                    <input id="email" type="email" name="email" class="input-field" 
                           value="{{ old('email') }}" required placeholder="name@company.com">
                    @error('email')
                        <div style="color:#dc2626; font-size:12px; margin-top:4px;">{{ $message }}</div>
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
                        <div style="color:#dc2626; font-size:12px; margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="options-row">
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;color:#64748b;">
                        <input type="checkbox" name="remember" style="accent-color:#2563eb;"> Remember me
                    </label>
                    <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                </div>

                <button type="submit" class="btn-submit">Sign In</button>
            </form>
            
            <div style="text-align:center; margin-top:20px; font-size:14px; color:#64748b;">
                Don't have an account? <a href="{{ route('auth.register') }}" style="color:#2563eb; font-weight:600; text-decoration:none;">Sign up</a>
            </div>

            <div class="back-link">
                <a href="{{ url('/') }}">← Back to Home</a>
            </div>
        </div>
    </div>

</body>
</html>