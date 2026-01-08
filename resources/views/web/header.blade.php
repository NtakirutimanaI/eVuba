<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>eVuba Connect</title>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">


  <style>
    /* ================= RESET (SCOPED) ================= */
    .evc-page * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    .evc-page {
      font-family: 'Inter', sans-serif;
      padding-top: 100px;
      background-color: #050b1a;
      /* Darker Theme Base */
      color: #e9eef6;
    }

    /* ================= HEADER ================= */
    .evc-header {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      background-color: rgba(5, 11, 26, 0.95);
      /* Semi-transparent backdrop */
      backdrop-filter: blur(10px);
      z-index: 1000;
      height: 90px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 5%;
      border-bottom: 1px solid rgba(255, 255, 255, 0.05);
      transition: all 0.3s ease;
    }

    .evc-header.scrolled {
      height: 70px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    }

    /* ================= LOGO ================= */
    .evc-logo {
      display: flex;
      align-items: center;
      font-weight: 900;
      font-size: 38px;
      color: #ffffff;
      gap: 15px;
      letter-spacing: -0.5px;
      text-decoration: none;
      text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
      z-index: 1001;
      /* Ensure on top */
    }

    .evc-logo img {
      height: 150px;
      width: auto;
      transition: all 0.3s ease;
      filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
    }

    /* ================= NAV AREA ================= */
    .evc-nav-auth {
      display: flex;
      align-items: center;
      gap: 40px;
    }

    /* ================= NAV LINKS ================= */
    .evc-nav {
      display: flex;
      gap: 40px;
      align-items: center;
    }

    .evc-nav a {
      position: relative;
      text-decoration: none;
      color: #94a3b8;
      font-size: 14px;
      font-weight: 500;
      transition: color 0.25s ease;
    }

    .evc-nav a:hover {
      color: #fff;
    }

    .evc-nav a.evc-active {
      color: #fff;
    }

    .evc-nav a::after {
      content: "";
      position: absolute;
      left: 50%;
      bottom: -4px;
      width: 0;
      height: 2px;
      background-color: #3b82f6;
      border-radius: 2px;
      transition: width 0.3s ease, left 0.3s ease;
    }

    .evc-nav a.evc-active::after,
    .evc-nav a:hover::after {
      width: 100%;
      left: 0;
    }

    /* ================= AUTH ================= */
    .evc-auth a {
      padding: 10px 24px;
      background: linear-gradient(135deg, #2563eb, #1e40af);
      border-radius: 8px;
      font-size: 14px;
      font-weight: 600;
      text-decoration: none;
      color: #ffffff;
      transition: all 0.3s ease;
      box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .evc-auth a:hover {
      transform: translateY(-1px);
      box-shadow: 0 6px 16px rgba(37, 99, 235, 0.4);
    }

    /* ================= HAMBURGER ================= */
    .hamburger {
      display: none;
      font-size: 24px;
      color: #fff;
      cursor: pointer;
    }

    /* ================= RESPONSIVE ================= */
    @media (max-width: 900px) {
      .evc-header {
        padding: 0 20px;
      }

      .hamburger {
        display: block;
      }

      .evc-nav-auth {
        position: absolute;
        top: 90px;
        left: 0;
        width: 100%;
        background-color: #050b1a;
        flex-direction: column;
        padding: 40px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        transform: translateY(-150%);
        transition: transform 0.3s ease-in-out;
        z-index: -1;
      }

      .evc-nav-auth.open {
        transform: translateY(0);
      }

      .evc-nav {
        flex-direction: column;
        gap: 30px;
      }
    }
  </style>
</head>

<body class="evc-page">

  <header class="evc-header" id="mainHeader">

    <a href="{{ url('/') }}" class="evc-logo">
      @if(file_exists(public_path('images/logo1.png')))
        <img src="{{ asset('images/logo1.png') }}?v=4" alt="eVuba">
      @else
        <i class="fas fa-cube" style="color: #3b82f6;"></i> eVubaConnect
      @endif
    </a>

    <div class="hamburger" id="hamburgerBtn">
      <i class="fas fa-bars"></i>
    </div>

    <div class="evc-nav-auth" id="navMenu">
      <nav class="evc-nav" id="evcMainNav">
        <a href="{{ url('/') }}#home" data-link="home">Home</a>
        <a href="{{ url('/') }}#services" data-link="services">Services</a>
        <a href="{{ url('/') }}#about" data-link="about">About</a>
        <a href="{{ url('/') }}#contact" data-link="contact">Contact</a>

        @auth
          <a href="{{ url('/dashboard') }}">Dashboard</a>
        @else
          <a href="{{ route('auth.login') }}" class="btn-login">Login</a>
          <a href="{{ route('auth.register') }}" class="btn-signup">Sign Up</a>
        @endauth
    </div>
    </nav>
    </div>

  </header>

  <style>
    /* ... existing styles ... */

    /* Update Auth Buttons */
    .evc-auth {
      display: flex;
      gap: 12px;
      align-items: center;
    }

    .evc-auth a {
      /* Reset default specific to previous single button if needed, but we used .evc-auth a selector */
      text-decoration: none;
      font-weight: 600;
      font-size: 14px;
      transition: all 0.3s ease;
    }

    .btn-login {
      color: #94a3b8;
      background: transparent;
      padding: 8px 16px;
      border-radius: 8px;
    }

    .btn-login:hover {
      color: #fff;
      background: rgba(255, 255, 255, 0.05);
    }

    .btn-signup {
      background: linear-gradient(135deg, #2563eb, #1e40af);
      color: #ffffff;
      padding: 10px 24px;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .btn-signup:hover {
      transform: translateY(-1px);
      box-shadow: 0 6px 16px rgba(37, 99, 235, 0.4);
    }
  </style>

  <script>
    // Header Scroll Effect
    window.addEventListener('scroll', () => {
      const header = document.getElementById('mainHeader');
      if (window.scrollY > 50) {
        header.classList.add('scrolled');
      } else {
        header.classList.remove('scrolled');
      }
    });

    // Mobile Menu Toggle
    const hamburger = document.getElementById('hamburgerBtn');
    const navMenu = document.getElementById('navMenu');
    hamburger.addEventListener('click', () => {
      navMenu.classList.toggle('open');
    });

    // Active Link Logic
    const links = document.querySelectorAll('.evc-nav a[data-link]');

    // Close menu on link click
    links.forEach(link => {
      link.addEventListener('click', () => {
        navMenu.classList.remove('open');
        links.forEach(l => l.classList.remove('evc-active'));
        link.classList.add('evc-active');
      });
    });

  </script>

</body>

</html>