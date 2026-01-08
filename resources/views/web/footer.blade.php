<footer class="evc-footer">
  <div class="footer-content">

    <!-- BRAND COLUMN -->
    <div class="footer-col brand-col">
      <div class="footer-logo">
        <i class="fas fa-cube" style="color: #3b82f6;"></i> eVubaConnect
      </div>
      <p>Partnering with visionary enterprises to deliver robust IT infrastructure, security, and software solutions.
      </p>

      <div class="social-links">
        <a href="#"><i class="fab fa-linkedin"></i></a>
        <a href="#"><i class="fab fa-twitter"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
      </div>
    </div>

    <!-- LINKS COLUMN -->
    <div class="footer-col">
      <h4>Company</h4>
      <a href="{{ url('/') }}#about">About Us</a>
      <a href="{{ url('/') }}#services">Services</a>
      <a href="{{ url('/') }}#contact">Contact</a>
      <a href="{{route('web.privacy-policy')}}">Privacy Policy</a>
    </div>

    <!-- CONTACT COLUMN -->
    <div class="footer-col">
      <h4>Contact</h4>
      <div class="contact-item">
        <i class="fas fa-map-marker-alt"></i>
        <span>Kigali, Rwanda<br>Gasabo-Gisozi</span>
      </div>
      <div class="contact-item">
        <i class="fas fa-envelope"></i>
        <span>evubaconnect@gmail.com</span>
      </div>
      <div class="contact-item">
        <i class="fas fa-phone"></i>
        <span>+250 786 325 291</span>
      </div>
    </div>

    <!-- NEWSLETTER COLUMN -->
    <div class="footer-col newsletter-col">
      <h4>Stay Updated</h4>
      <p>Subscribe to receive updates on new products and security alerts.</p>

      <form id="evc-footer-form" action="{{ route('subscribe.store') }}" method="POST" class="sub-form">
        @csrf
        <input type="email" name="email" placeholder="Email address" required>
        <button type="submit"><i class="fas fa-paper-plane"></i></button>
      </form>
      <div id="evc-footer-success"></div>
    </div>

  </div>

  <div class="footer-bottom">
    &copy; {{ date('Y') }} eVubaConnect. All rights reserved.
  </div>
</footer>

<style>
  .evc-footer {
    background-color: #020617;
    /* Very Dark Blue/Black */
    padding: 80px 7% 30px;
    color: #94a3b8;
    font-family: 'Inter', sans-serif;
    border-top: 1px solid rgba(255, 255, 255, 0.05);
  }

  .footer-content {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1.5fr;
    gap: 60px;
    margin-bottom: 60px;
  }

  .footer-col h4 {
    color: #fff;
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 24px;
  }

  .brand-col p {
    margin: 20px 0;
    line-height: 1.6;
    font-size: 14px;
  }

  .footer-logo {
    font-size: 20px;
    font-weight: 700;
    color: #fff;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .footer-col a {
    display: block;
    color: #94a3b8;
    text-decoration: none;
    margin-bottom: 12px;
    font-size: 14px;
    transition: color 0.3s;
  }

  .footer-col a:hover {
    color: #3b82f6;
  }

  .contact-item {
    display: flex;
    gap: 12px;
    margin-bottom: 16px;
    font-size: 14px;
  }

  .contact-item i {
    color: #3b82f6;
    margin-top: 3px;
  }

  .social-links {
    display: flex;
    gap: 16px;
  }

  .social-links a {
    width: 36px;
    height: 36px;
    background: rgba(255, 255, 255, 0.05);
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    color: #fff;
    transition: all 0.3s;
  }

  .social-links a:hover {
    background: #3b82f6;
    transform: translateY(-3px);
  }

  .sub-form {
    display: flex;
    gap: 8px;
    margin-top: 16px;
  }

  .sub-form input {
    flex: 1;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    padding: 10px 14px;
    border-radius: 8px;
    color: #fff;
    outline: none;
  }

  .sub-form input:focus {
    border-color: #3b82f6;
  }

  .sub-form button {
    background: #3b82f6;
    border: none;
    color: #fff;
    width: 44px;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.3s;
  }

  .sub-form button:hover {
    background: #2563eb;
  }

  .footer-bottom {
    text-align: center;
    padding-top: 30px;
    border-top: 1px solid rgba(255, 255, 255, 0.05);
    font-size: 13px;
  }

  #evc-footer-success {
    color: #4ade80;
    font-size: 13px;
    margin-top: 10px;
  }

  @media (max-width: 1024px) {
    .footer-content {
      grid-template-columns: 1fr 1fr;
      gap: 40px;
    }
  }

  @media (max-width: 600px) {
    .footer-content {
      grid-template-columns: 1fr;
      gap: 40px;
    }
  }
</style>

<script>
  // Simple AJAX for Footer Subscription
  document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById('evc-footer-form');
    const msg = document.getElementById('evc-footer-success');

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const formData = new FormData(form);

      try {
        const response = await fetch(form.action, {
          method: 'POST',
          body: formData,
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          }
        });

        if (response.ok) {
          msg.innerText = "Subscribed successfully!";
          msg.style.color = "#4ade80";
          form.reset();
        } else {
          msg.innerText = "Subscription failed. Try again.";
          msg.style.color = "#ef4444";
        }
      } catch (err) {
        console.error(err);
      }
    });
  });
</script>