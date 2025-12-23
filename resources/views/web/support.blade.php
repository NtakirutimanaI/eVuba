<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Support Section</title>
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

  <style>
    * {
      box-sizing: border-box;
      padding: 0;
    }

    body {
      font-family: Arial, sans-serif;
      background-color: #0A1128;
    }

    .support-section {
      background: linear-gradient(to right, #0A1128, #0A1128);
      color: white;
      padding: 40px 20px;
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: center;
      margin-top: 10px;
    }

    .support-content {
      flex: 1;
      min-width: 300px;
      max-width: 500px;
      padding: 20px;
    }

    .support-label {
      color: #00ff88;
      font-size: 14px;
      margin-bottom: 10px;
      font-weight: bold;
    }

    .support-title {
      font-size: 32px;
      font-weight: bold;
      margin-bottom: 10px;
    }

    .support-description {
      font-size: 28px;
      margin-bottom: 30px;
    }

    .support-action {
      display: flex;
      align-items: center;
      gap: 15px;
      flex-wrap: wrap;
    }

    .call-icon {
      background: white;
      border-radius: 50%;
      padding: 10px;
      width: 60px;
      height: 60px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 28px;
      color: #25D366;
    }

    /* WhatsApp Button */
    .whatsapp-btn {
      display: inline-block;
      background-color: #25D366;
      color: white;
      padding: 12px 20px;
      border-radius: 50px;
      font-size: 16px;
      font-weight: bold;
      text-decoration: none;
      box-shadow: 0px 4px 8px rgba(0,0,0,0.2);
      transition: background 0.3s, transform 0.2s;
    }
    .whatsapp-btn:hover {
      background-color: #1ebe5c;
      transform: scale(1.05);
    }

    .support-image {
      flex: 1;
      min-width: 300px;
      max-width: 500px;
      text-align: center;
      padding: 20px;
    }

    .support-image img {
      max-width: 100%;
      height: auto;
      border-radius: 8px;
    }

    @media (max-width: 768px) {
      .support-section {
        flex-direction: column;
        text-align: center;
      }

      .support-action {
        justify-content: center;
      }
    }
  </style>
</head>
<body>

  <section class="support-section">
    <div class="support-content">
      <div class="support-label">Support</div>
      <div class="support-title">We are here for you</div>
      <div class="support-description">Any issue reach on us</div>
      <div class="support-action">
        <!-- WhatsApp Icon -->
        <div class="call-icon">
          <i class="bi bi-whatsapp"></i>
        </div>
        
        <!-- WhatsApp Chat Button -->
        <a href="https://wa.me/25078732490" target="_blank" class="whatsapp-btn">
          Chat on WhatsApp
        </a>
      </div>
    </div>
    <div class="support-image">
      <img src="{{asset('images/support.png')}}" alt="Support Person">
    </div>
  </section>

</body>
</html>
