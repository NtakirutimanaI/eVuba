<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>eVuba Connect</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        
        <style>
            :root {
                --primary: #4f46e5;
                --text: #1e293b;
                --bg: #f8fafc;
            }
            body {
                font-family: 'Instrument Sans', sans-serif;
                background-color: var(--bg);
                color: var(--text);
                margin: 0;
                display: flex;
                flex-direction: column;
                min-height: 100vh;
                align-items: center;
                justify-content: center;
            }
            .container {
                text-align: center;
                animation: fadeIn 0.8s ease;
            }
            .logo {
                font-size: 48px;
                font-weight: 800;
                color: var(--primary);
                margin-bottom: 10px;
                text-transform: uppercase;
                letter-spacing: -1px;
            }
            .tagline {
                font-size: 18px;
                color: #64748b;
                margin-bottom: 40px;
            }
            .actions {
                display: flex;
                gap: 20px;
                justify-content: center;
            }
            .btn {
                padding: 12px 30px;
                border-radius: 50px;
                text-decoration: none;
                font-weight: 600;
                transition: all 0.3s;
                font-size: 16px;
            }
            .btn-primary {
                background: var(--primary);
                color: white;
                box-shadow: 0 10px 25px -10px rgba(79, 70, 229, 0.5);
            }
            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 15px 30px -10px rgba(79, 70, 229, 0.6);
            }
            .btn-secondary {
                background: white;
                color: var(--text);
                border: 1px solid #e2e8f0;
            }
            .btn-secondary:hover {
                background: #f1f5f9;
                border-color: #cbd5e1;
            }
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            footer {
                position: absolute;
                bottom: 20px;
                font-size: 12px;
                color: #94a3b8;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="logo">
                @if(file_exists(public_path('images/logo.png')))
                    <img src="{{ asset('images/logo.png') }}" alt="eVuba Logo" style="height: 80px;">
                @else
                    <i class="fas fa-cube"></i> eVuba Connect
                @endif
            </div>
            <p class="tagline">Streamlined Business Management Solution</p>
            
            <div class="actions">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-primary">Go to Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-secondary">Register</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>

        <footer>
            &copy; {{ date('Y') }} eVuba Connect. All rights reserved.
        </footer>
    </body>
</html>
