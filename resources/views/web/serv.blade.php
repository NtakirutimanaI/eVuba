<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Core Services</title>

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Icons -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      background-color: #050b1a;
      color: #ffffff;
    }

    /* SECTION */
    .services-section {
      width: 100%;
      padding: 100px 6%;
      background: radial-gradient(circle at top,
          #0c1633 0%,
          #050b1a 60%);
    }

    /* HEADER */
    .services-header {
      text-align: center;
      max-width: 750px;
      margin: 0 auto 70px;
    }

    .services-header h2 {
      font-size: 36px;
      font-weight: 800;
      margin-bottom: 14px;
      background: linear-gradient(90deg, #fff, #94a3b8);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .services-header p {
      font-size: 16px;
      line-height: 1.6;
      color: #cbd5e1;
    }

    /* GRID */
    .services-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 30px;
      justify-content: center;
    }

    /* CARD - Now an Anchor Tag */
    .service-card {
      display: block;
      /* Make anchor behave like block */
      text-decoration: none;
      /* Remove underline */
      background: linear-gradient(180deg,
          rgba(255, 255, 255, 0.05),
          rgba(255, 255, 255, 0.02));
      border-radius: 20px;
      padding: 40px 32px;
      position: relative;
      border: 1px solid rgba(255, 255, 255, 0.06);
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      overflow: hidden;
    }

    /* Hover Glow Effect */
    .service-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 2px;
      background: linear-gradient(90deg, transparent, #3b82f6, transparent);
      transform: scaleX(0);
      transition: transform 0.4s ease;
    }

    .service-card:hover::before {
      transform: scaleX(1);
    }

    .service-card:hover {
      transform: translateY(-10px);
      border-color: rgba(59, 130, 246, 0.5);
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6);
      background: linear-gradient(180deg,
          rgba(255, 255, 255, 0.08),
          rgba(255, 255, 255, 0.03));
    }

    /* ICON */
    .icon-box {
      width: 60px;
      height: 60px;
      border-radius: 16px;
      background: linear-gradient(135deg,
          #2563eb,
          #1e40af);
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 24px;
      box-shadow: 0 10px 20px rgba(37, 99, 235, 0.3);
      transition: transform 0.3s ease;
    }

    .service-card:hover .icon-box {
      transform: scale(1.1) rotate(-5deg);
    }

    .icon-box i {
      font-size: 26px;
      color: #ffffff;
    }

    /* TEXT */
    .service-card h3 {
      font-size: 21px;
      font-weight: 700;
      margin-bottom: 12px;
      color: #fff;
    }

    .service-card p {
      font-size: 15px;
      line-height: 1.7;
      color: #94a3b8;
      transition: color 0.3s;
    }

    .service-card:hover p {
      color: #cbd5e1;
    }

    /* CTA ARROW */
    .card-cta {
      margin-top: 20px;
      font-size: 14px;
      font-weight: 600;
      color: #60a5fa;
      display: flex;
      align-items: center;
      gap: 8px;
      opacity: 0;
      transform: translateX(-10px);
      transition: all 0.3s ease;
    }

    .service-card:hover .card-cta {
      opacity: 1;
      transform: translateX(0);
    }

    /* RESPONSIVE */
    @media (max-width: 992px) {
      .services-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 600px) {
      .services-section {
        padding: 80px 5%;
      }

      .services-header h2 {
        font-size: 28px;
      }

      .services-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>

<body>

  <section class="services-section">
    <div class="services-header">
      <h2>Our Premium Services</h2>
      <p>
        Discover excellence in every interaction. Click on any service to get started with eVuba today.
      </p>
    </div>

    <div class="services-grid">

      <!-- 1. Cloud Computing -->
      <a href="{{ route('auth.register') }}" class="service-card">
        <div class="icon-box">
          <i class="fas fa-cloud"></i>
        </div>
        <h3>Cloud Computing</h3>
        <p>
          Scalable cloud infrastructure solutions designed to elevate your business flexibility and data accessibility
          secure and fast.
        </p>
        <div class="card-cta">Get Started <i class="fas fa-arrow-right"></i></div>
      </a>

      <!-- 2. Software Solutions -->
      <a href="{{ route('auth.register') }}" class="service-card">
        <div class="icon-box">
          <i class="fas fa-code"></i>
        </div>
        <h3>Software Solutions</h3>
        <p>
          Custom-tailored software development to automate processes, enhance productivity, and solve complex business
          challenges.
        </p>
        <div class="card-cta">Build Your Solution <i class="fas fa-arrow-right"></i></div>
      </a>

      <!-- 3. IT Support -->
      <a href="{{ route('auth.register') }}" class="service-card">
        <div class="icon-box">
          <i class="fas fa-headset"></i>
        </div>
        <h3>IT Support</h3>
        <p>
          24/7 dedicated technical assistance. Our expert team ensures your operations run smoothly without
          interruption.
        </p>
        <div class="card-cta">Get Support <i class="fas fa-arrow-right"></i></div>
      </a>

      <!-- 4. CyberSecurity -->
      <a href="{{ route('auth.register') }}" class="service-card">
        <div class="icon-box">
          <i class="fas fa-shield-halved"></i>
        </div>
        <h3>CyberSecurity</h3>
        <p>
          Advanced threat protection and data security protocols to safeguard your valuable digital assets against
          modern threats.
        </p>
        <div class="card-cta">Secure Now <i class="fas fa-arrow-right"></i></div>
      </a>

      <!-- 5. IT Equipment Supply -->
      <a href="{{ route('auth.register') }}" class="service-card">
        <div class="icon-box">
          <i class="fas fa-server"></i>
        </div>
        <h3>IT Equipment Supply</h3>
        <p>
          Premier procurement service for top-tier hardware, servers, and networking equipment suited for enterprise
          needs.
        </p>
        <div class="card-cta">Shop Equipment <i class="fas fa-arrow-right"></i></div>
      </a>

    </div>
  </section>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const cards = document.querySelectorAll(".service-card");

      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.style.opacity = "1";
            entry.target.style.transform = "translateY(0)";
          }
        });
      }, { threshold: 0.1 });

      cards.forEach((card, index) => {
        card.style.opacity = "0";
        card.style.transform = "translateY(30px)";
        card.style.transition = `all 0.6s cubic-bezier(0.5, 0, 0, 1) ${index * 0.1}s`; // Staggered delay
        observer.observe(card);
      });
    });
  </script>
</body>

</html>