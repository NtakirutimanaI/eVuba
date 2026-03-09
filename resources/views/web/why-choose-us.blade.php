<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Why Choose VUBA TECH</title>

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
    }

    /* SECTION */
    .why-section {
      background: #9aa0a6;
      padding: 90px 6%;
    }

    .why-container {
      display: grid;
      grid-template-columns: 1.3fr 1fr;
      gap: 60px;
      align-items: center;
    }

    /* LEFT CONTENT */
    .why-content h2 {
      font-size: 34px;
      font-weight: 800;
      color: #ffffff;
      margin-bottom: 20px;
    }

    .why-content p {
      font-size: 15.5px;
      line-height: 1.8;
      color: #e5e7eb;
      margin-bottom: 18px;
      max-width: 560px;
    }

    /* RIGHT STATS */
    .stats {
      display: flex;
      flex-direction: column;
      gap: 22px;
    }

    .stat-card {
      background: linear-gradient(135deg, #2563eb, #1e40af);
      border-radius: 14px;
      padding: 26px 30px;
      display: flex;
      align-items: center;
      gap: 20px;
      box-shadow: 0 12px 40px rgba(0, 0, 0, 0.25);
    }

    .stat-icon {
      font-size: 30px;
      color: #e0e7ff;
    }

    .stat-text h3 {
      font-size: 36px;
      font-weight: 800;
      color: #ffffff;
      line-height: 1;
    }

    .stat-text span {
      font-size: 15px;
      color: #e0e7ff;
      font-weight: 500;
    }

    /* RESPONSIVE */
    @media (max-width: 900px) {
      .why-container {
        grid-template-columns: 1fr;
      }

      .stats {
        margin-top: 40px;
      }
    }
  </style>
</head>

<body>

  <section class="why-section">
    <div class="why-container">

      <!-- LEFT -->
      <div class="why-content">
        <h2>Why Choose VUBA TECH Ltd?</h2>

        <p>
          As a leading provider of enterprise IT solutions, VUBA TECH Ltd combines
          cutting-edge technology with exceptional service delivery. Our commitment
          to excellence has made us the trusted partner for businesses across the region.
        </p>

        <p>
          From high-speed internet infrastructure to comprehensive IT support and
          hardware solutions, we deliver integrated services that empower your business
          to thrive in the digital age. Our team of certified professionals ensures
          seamless operations and rapid response to your needs.
        </p>
      </div>

      <!-- RIGHT -->
      <div class="stats">

        <div class="stat-card">
          <div class="stat-icon">
            <i class="fas fa-award"></i>
          </div>
          <div class="stat-text">
            <h3>7+</h3>
            <span>Years Experience</span>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon">
            <i class="fas fa-clock"></i>
          </div>
          <div class="stat-text">
            <h3>24/7</h3>
            <span>Support Available</span>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon">
            <i class="fas fa-users"></i>
          </div>
          <div class="stat-text">
            <h3>100+</h3>
            <span>Enterprise Clients</span>
          </div>
        </div>

      </div>

    </div>
  </section>

</body>

</html>