<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>eVuba Alumni – Hero</title>

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <style>
    :root {
      --primary: #3b5cff;
      --primary-dark: #2f49d1;
      --text-main: #e9eef6;
      --text-muted: #b7c0d1;
      --bg-main: #0A1128;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Inter', Arial, sans-serif;
    }

    html, body {
      width: 100%;
      height: 100%;
      background: var(--bg-main);
      overflow-x: hidden;
    }

    /* ================= NAV ================= */
    .nav {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      padding: 22px 7%;
      display: flex;
      justify-content: space-between;
      align-items: center;
      z-index: 3;
    }

    .logo {
      display: flex;
      align-items: center;
      gap: 10px;
      color: #fff;
      font-weight: 700;
      font-size: 14px;
      letter-spacing: 0.4px;
    }

    .logo img {
      height: 28px;
    }

    .nav-links {
      display: flex;
      gap: 28px;
      font-size: 13px;
    }

    .nav-links a {
      color: #dbe2f1;
      text-decoration: none;
      font-weight: 500;
      transition: color 0.3s ease;
    }

    .nav-links a:hover {
      color: #ffffff;
    }

    /* ================= HERO ================= */
    .hero {
      position: relative;
      min-height: 100svh;
      width: 100%;
      background-color: var(--bg-main);
      display: flex;
      align-items: center;
      padding: clamp(90px, 12vh, 140px) clamp(16px, 7vw, 7%);
      overflow: hidden;
    }

    /* ================= CONTENT ================= */
    .hero-content {
      position: relative;
      z-index: 2;
      max-width: 620px;
      margin-left: 20%; /* left margin */
    }

    .hero-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: rgba(59, 92, 255, 0.15);
      color: #9fb0ff;
      padding: 6px 12px;
      border-radius: 999px;
      font-size: 12px;
      font-weight: 500;
      margin-bottom: 22px;
    }

    .hero-title {
      font-size: 48px;
      line-height: 1.15;
      font-weight: 800;
      color: var(--text-main);
      margin-bottom: 22px;
      letter-spacing: -0.6px;
    }

    .hero-title span {
      color: var(--primary);
      display: inline-block;
      animation: pulse 15s infinite;
    }

    @keyframes pulse {
      0%, 20%, 40%, 60%, 80%, 100% { opacity: 1; transform: scale(1); }
      10%, 30%, 50%, 70%, 90% { opacity: 0; transform: scale(1.2); }
    }

    .hero-text {
      font-size: 15px;
      line-height: 1.7;
      color: var(--text-muted);
      max-width: 520px;
      margin-bottom: 34px;
    }

    /* ================= BUTTONS ================= */
    .hero-actions {
      display: flex;
      align-items: center;
      gap: 16px;
      flex-wrap: wrap;
    }

    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      height: 46px;
      padding: 0 22px;
      border-radius: 8px;
      font-size: 14px;
      font-weight: 600;
      text-decoration: none;
      transition: all 0.25s ease;
      cursor: pointer;
      white-space: nowrap;
    }

    .btn-primary {
      background: var(--primary);
      color: #fff;
      box-shadow: 0 8px 20px rgba(59,92,255,0.35);
    }

    .btn-primary:hover {
      background: var(--primary-dark);
      transform: translateY(-1px);
    }

    .auth-links a {
      padding: 10px 20px;
      border: 1px solid #fff;
      border-radius: 6px;
      font-size: 14px;
      text-decoration: none;
      color: #0A1128;
      background-color: #fff;
      transition: background 0.3s, color 0.3s;
      height: 46px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }

    .auth-links a:hover {
      background-color: #0A1128;
      color: #fff;
    }

    /* ================= SMALL TEXT ================= */
    .scroll-note {
      position: absolute;
      right: 28px;
      bottom: 28px;
      z-index: 2;
      font-size: 11px;
      letter-spacing: 1.2px;
      color: #9aa6bd;
      text-transform: uppercase;
    }

    /* ================= RESPONSIVE ================= */
    @media (max-width: 900px) {
      .hero-title {
        font-size: 38px;
      }
    }

    @media (max-width: 640px) {
      .nav-links {
        display: none;
      }

      .hero-title {
        font-size: 30px;
      }

      .hero-actions {
        flex-direction: column;
        align-items: flex-start;
        width: 100%;
      }

      .btn,
      .auth-links a {
        width: 100%;
      }

      .hero-content {
        margin-left: 0;
      }
    }
  </style>
</head>
<body>



  <!-- HERO -->
  <section class="hero">
    <div class="hero-content">

      <div class="hero-badge">
        We For You As+ eVuba Quick Service Providers
      </div>

      <h1 class="hero-title">
        Your Lifelong<br>
        Connection to <span>eVuba</span>
      </h1>

      <p class="hero-text">
        Reconnect with enterprise-grade IT solutions. Discover seamless connectivity, expert support, and innovative hardware solutions that power your business growth.
      </p>

      <div class="hero-actions">
        <a href="#" class="btn btn-primary">Get Started <span>&nbsp;→</span></a>
        <div class="auth-links">
          <a href="{{ route('auth.register') }}">Sign Up</a>
        </div>
      </div>
    </div>
  </section>

</body>
</html>
