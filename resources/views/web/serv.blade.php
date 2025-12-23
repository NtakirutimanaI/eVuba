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
  background: radial-gradient(
    circle at top,
    #0c1633 0%,
    #050b1a 60%
  );
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
}

.services-header p {
  font-size: 16px;
  line-height: 1.6;
  color: #cbd5e1;
}

/* GRID */
.services-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 30px;
}

/* CARD */
.service-card {
  background: linear-gradient(
    180deg,
    rgba(255,255,255,0.05),
    rgba(255,255,255,0.02)
  );
  border-radius: 16px;
  padding: 40px 32px;
  position: relative;
  border: 1px solid rgba(255,255,255,0.06);
  transition: all 0.35s ease;
}

.service-card:hover {
  transform: translateY(-8px);
  border-color: rgba(59,130,246,0.5);
  box-shadow: 0 20px 60px rgba(0,0,0,0.4);
}

/* ICON */
.icon-box {
  width: 54px;
  height: 54px;
  border-radius: 12px;
  background: linear-gradient(
    135deg,
    #2563eb,
    #1e40af
  );
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 24px;
}

.icon-box i {
  font-size: 22px;
  color: #ffffff;
}

/* TEXT */
.service-card h3 {
  font-size: 20px;
  font-weight: 700;
  margin-bottom: 14px;
}

.service-card p {
  font-size: 14.5px;
  line-height: 1.7;
  color: #cbd5e1;
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
    <h2>Our Core Services</h2>
    <p>
      Comprehensive IT solutions designed to streamline your operations and drive growth
    </p>
  </div>

  <div class="services-grid">
    
    <div class="service-card">
      <div class="icon-box">
        <i class="fas fa-wifi"></i>
      </div>
      <h3>High-Speed Internet</h3>
      <p>
        Enterprise-grade connectivity solutions with 99.9% uptime guarantee.
        Fiber-optic infrastructure for maximum speed and reliability.
      </p>
    </div>

    <div class="service-card">
      <div class="icon-box">
        <i class="fas fa-screwdriver-wrench"></i>
      </div>
      <h3>IT Maintenance & Support</h3>
      <p>
        24/7 technical support and proactive system maintenance.
        Expert team ready to resolve any IT challenges your business faces.
      </p>
    </div>

    <div class="service-card">
      <div class="icon-box">
        <i class="fas fa-cart-shopping"></i>
      </div>
      <h3>Hardware Inventory Store</h3>
      <p>
        Complete catalog of enterprise hardware and accessories.
        From servers to workstations, we have everything your business needs.
      </p>
    </div>

  </div>
</section>

<script>
    // Optional subtle entrance animation
document.addEventListener("DOMContentLoaded", () => {
  const cards = document.querySelectorAll(".service-card");

  cards.forEach((card, index) => {
    card.style.opacity = "0";
    card.style.transform = "translateY(20px)";
    
    setTimeout(() => {
      card.style.transition = "0.6s ease";
      card.style.opacity = "1";
      card.style.transform = "translateY(0)";
    }, index * 150);
  });
});

</script>
</body>
</html>
