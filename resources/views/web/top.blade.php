<!DOCTYPE html>
<html lang="en">

<head>
  <style>
    /* Inherits from Parent, just adding Hero Specifics */
    .hero {
      position: relative;
      min-height: 100vh;
      width: 100%;
      background: radial-gradient(circle at 15% 50%, rgba(59, 92, 255, 0.08) 0%, transparent 25%),
        radial-gradient(circle at 85% 30%, rgba(236, 72, 153, 0.08) 0%, transparent 25%);
      display: flex;
      align-items: center;
      padding: 0 7%;
      overflow: hidden;
    }

    .hero-content {
      position: relative;
      z-index: 2;
      max-width: 700px;
    }

    .hero-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(255, 255, 255, 0.1);
      color: #9fb0ff;
      padding: 8px 16px;
      border-radius: 999px;
      font-size: 13px;
      font-weight: 500;
      margin-bottom: 24px;
      backdrop-filter: blur(5px);
    }

    .hero-title {
      font-size: 64px;
      line-height: 1.1;
      font-weight: 800;
      color: #fff;
      margin-bottom: 24px;
      letter-spacing: -1.5px;
    }

    .hero-title span {
      background: linear-gradient(135deg, #60a5fa 0%, #a855f7 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .hero-text {
      font-size: 18px;
      line-height: 1.6;
      color: #94a3b8;
      max-width: 580px;
      margin-bottom: 40px;
    }

    .hero-actions {
      display: flex;
      gap: 16px;
    }

    .btn-hero {
      padding: 16px 32px;
      border-radius: 12px;
      font-weight: 600;
      font-size: 16px;
      text-decoration: none;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    .btn-hero-primary {
      background: #3b82f6;
      color: white;
      box-shadow: 0 4px 20px rgba(59, 130, 246, 0.4);
    }

    .btn-hero-primary:hover {
      background: #2563eb;
      transform: translateY(-2px);
      box-shadow: 0 8px 30px rgba(59, 130, 246, 0.5);
    }

    .btn-hero-outline {
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(255, 255, 255, 0.1);
      color: white;
    }

    .btn-hero-outline:hover {
      background: rgba(255, 255, 255, 0.1);
      border-color: rgba(255, 255, 255, 0.2);
    }

    @media (max-width: 768px) {
      .hero-title {
        font-size: 42px;
      }

      .hero-actions {
        flex-direction: column;
      }

      .btn-hero {
        width: 100%;
        justify-content: center;
      }
    }
  </style>
</head>

<body>

  <section class="hero">
    <div class="hero-content">

      <div class="hero-badge">
        <i class="fas fa-check-circle" style="color: #4ade80;"></i> Enterprise-Grade Solutions
      </div>

      <h1 class="hero-title">
        Empowering Next-Gen<br>
        <span>Digital Transformation</span>
      </h1>

      <p class="hero-text">
        From advanced Cloud Computing and CyberSecurity to reliable Hardware Supply. We provide the integrated
        technology ecosystem your business needs to scale securely.
      </p>

      <div class="hero-actions">
        <a href="#services" class="btn-hero btn-hero-primary">
          Explore Services <i class="fas fa-arrow-down"></i>
        </a>
        <a href="{{ route('auth.register') }}" class="btn-hero btn-hero-outline">
          Create Account
        </a>
      </div>
    </div>
  </section>

</body>

</html>