<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>eVuba Connect</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #1d4ed8;
            --primary-light: #2563eb;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --bg-light: #f8fafc;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-light);
            background-image: radial-gradient(circle at 15% 50%, rgba(37, 99, 235, 0.04), transparent 25%), radial-gradient(circle at 85% 30%, rgba(37, 99, 235, 0.04), transparent 25%);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            margin: 0;
        }

        /* Navbar */
        .navbar {
            padding: 1.5rem 0;
            background: transparent;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-brand img {
            height: 40px;
        }

        /* Nav Links resembling sample */
        .nav-link {
            color: var(--text-dark);
            font-weight: 500;
            font-size: 0.95rem;
            padding: 0.5rem 1rem !important;
        }

        .nav-link:hover {
            color: var(--primary-light);
        }

        /* Hero Section */
        .hero-section {
            flex: 1;
            display: flex;
            align-items: center;
            padding: 3rem 0;
            text-align: center;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            background: white;
            padding: 0.4rem 1rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-dark);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        .hero-badge i {
            color: #fbbf24;
            margin-right: 8px;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 1.5rem;
            line-height: 1.2;
            letter-spacing: -0.03em;
        }

        .hero-subtitle {
            font-size: 1.125rem;
            color: var(--text-muted);
            max-width: 600px;
            margin: 0 auto 2.5rem;
            line-height: 1.6;
        }

        /* Buttons matching sample */
        .btn-custom {
            padding: 0.8rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-primary-custom {
            background-color: var(--primary-light);
            color: white;
            border: 2px solid var(--primary-light);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.25);
        }

        .btn-primary-custom:hover {
            background-color: var(--primary);
            border-color: var(--primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(37, 99, 235, 0.35);
        }

        .btn-secondary-custom {
            background-color: transparent;
            color: var(--text-dark);
            border: 1px solid #e2e8f0;
        }

        .btn-secondary-custom:hover {
            border-color: #cbd5e1;
            background-color: white;
            color: var(--text-dark);
            transform: translateY(-2px);
        }

        /* Stats Cards matching sample */
        .stats-wrapper {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin: 3rem 0;
            flex-wrap: wrap;
        }

        .stat-card {
            background: white;
            padding: 1.25rem 1.5rem;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.03);
            display: flex;
            align-items: center;
            gap: 1rem;
            min-width: 180px;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: #f1f5f9;
            color: var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .stat-icon.purple {
            color: #8b5cf6;
            background: #f5f3ff;
        }

        .stat-icon.blue {
            color: #3b82f6;
            background: #eff6ff;
        }

        .stat-icon.indigo {
            color: #6366f1;
            background: #eef2ff;
        }

        .stat-text {
            text-align: left;
        }

        .stat-val {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-dark);
            line-height: 1.2;
        }

        .stat-label {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin: 0;
            line-height: 1.2;
        }

        /* Mockup Image Container */
        .hero-mockup {
            margin-top: 2rem;
            position: relative;
            z-index: 10;
        }

        .hero-mockup img {
            max-width: 100%;
            height: auto;
            border-radius: 16px;
            filter: drop-shadow(0 25px 35px rgba(0, 0, 0, 0.1));
        }

        /* Footer */
        footer {
            padding: 2rem 0;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.875rem;
        }

        /* Scroll Explore Original Graphic */
        .scroll-explore {
            position: fixed;
            bottom: 40px;
            right: 40px;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            z-index: 1000;
            color: #64748b;
            font-size: 13px;
            font-weight: 500;
            line-height: 1.4;
            opacity: 0.8;
            transition: opacity 0.3s;
        }

        .scroll-explore:hover {
            opacity: 1;
        }

        .scroll-explore span {
            display: block;
        }

        .gradient-line {
            width: 40px;
            height: 3px;
            background: linear-gradient(90deg, #2563eb, #0ea5e9, #6366f1);
            border-radius: 2px;
            margin-top: 6px;
        }
    </style>
</head>

<body>
    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">
                @if(file_exists(public_path('images/logo.png')))
                    <img src="{{ asset('images/logo.png') }}" alt="eVuba Logo" style="height: 40px;">
                @else
                    <i class="fas fa-cube text-primary me-2"></i> eVuba Connect
                @endif
            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link px-3" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="#">Features</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="#">Services</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="#">Contact</a></li>
                </ul>
                <div class="d-flex align-items-center gap-2">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn-custom btn-primary-custom py-2 px-4"
                                style="font-size: 0.9rem;">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="btn-custom btn-secondary-custom border-0 py-2 px-4"
                                style="font-size: 0.9rem;">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn-custom btn-primary-custom py-2 px-4"
                                    style="font-size: 0.9rem;">Get Started</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Hero -->
    <main class="hero-section">
        <div class="container">

            <!-- Badge -->
            <div class="hero-badge">
                <i class="fas fa-star"></i> Integrated Business Solution
            </div>

            <!-- Title & Tagline -->
            <h1 class="hero-title pt-2">eVuba Connect</h1>

            <p class="hero-subtitle">
                Streamlined Business Management Solution
            </p>

            <!-- Actions (using original content wording) -->
            <div class="d-flex justify-content-center gap-3 mb-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="btn-custom btn-primary-custom d-flex align-items-center gap-2">Go to Dashboard <i
                                class="fas fa-arrow-right"></i></a>
                    @else
                        <a href="{{ route('login') }}" class="btn-custom btn-primary-custom">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-custom btn-secondary-custom">Register</a>
                        @endif
                    @endauth
                @endif
            </div>

            <!-- Stats (matching sample image visually) -->
            <div class="stats-wrapper">
                <div class="stat-card">
                    <div class="stat-icon purple"><i class="fas fa-shapes"></i></div>
                    <div class="stat-text">
                        <div class="stat-val">Streamlined</div>
                        <p class="stat-label">Operations</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon blue"><i class="fas fa-layer-group"></i></div>
                    <div class="stat-text">
                        <div class="stat-val">Centralized</div>
                        <p class="stat-label">Management</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon indigo"><i class="fas fa-chart-pie"></i></div>
                    <div class="stat-text">
                        <div class="stat-val">Real-time</div>
                        <p class="stat-label">Analytics</p>
                    </div>
                </div>
            </div>

            <!-- Hero Image Mockups -->
            <div class="hero-mockup mt-5">
                <!-- User can upload their own dashboard mockup here -->
                @if(file_exists(public_path('images/hero-mockup.png')))
                    <img src="{{ asset('images/hero-mockup.png') }}" alt="Dashboard Preview">
                @else
                    <!-- Fallback if user hasn't uploaded the images yet -->
                    <div
                        style="background: white; border-radius: 16px; padding: 4rem; max-width: 900px; margin: 0 auto; min-height: 400px; display: flex; align-items: center; justify-content: center; color: #94a3b8; font-weight: 500; border: 2px dashed #e2e8f0;">
                        <span><i class="fas fa-image me-2"></i> Upload mockup image to: public/images/hero-mockup.png</span>
                    </div>
                @endif
            </div>

        </div>
    </main>

    <footer>
        <div class="container">
            &copy; {{ date('Y') }} eVuba Connect. All rights reserved.
        </div>
    </footer>

    <!-- Original Scroll Explore Element -->
    <div class="scroll-explore d-none d-md-flex">
        <span>Scroll to</span>
        <span>explore</span>
        <div class="gradient-line"></div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>