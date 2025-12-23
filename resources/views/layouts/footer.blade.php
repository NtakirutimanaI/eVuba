<footer class="evuba-footer">
  <div class="evuba-footer-container">

    <div class="evuba-footer-column">
        <h3>eVubaConnect</h3>
        <p>Subscribe</p>
        <p>Get 10% off your first order</p>
        <form class="evuba-footer-form">
            <input type="email" placeholder="Enter your email" />
            <button type="submit">&gt;</button>
        </form>
    </div>

    <div class="evuba-footer-column">
        <h3>Support</h3>
        <p>Rwanda, Kigali,<br>Nyamirambo</p>
        <p>eVubaConnect@gmail.com</p>
        <p>+250 786 325 291</p>
    </div>

    <div class="evuba-footer-column">
        <h3>Account</h3>
        <a href="#">My Account</a>
        <a href="#">Login / Register</a>
        <a href="#">Cart</a>
        <a href="#">Wishlist</a>
        <a href="#">Shop</a>
    </div>

    <div class="evuba-footer-column">
        <h3>Quick Link</h3>
        <a href="#">Privacy Policy</a>
        <a href="#">Terms Of Use</a>
        <a href="#">FAQ</a>
        <a href="#">Contact</a>
    </div>

    <div class="evuba-footer-column evuba-footer-download">
        <h3>Download App</h3>
        <p>Save $3 with App New User Only</p>    
        <div class="evuba-footer-icons">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-linkedin-in"></i></a>
        </div>
    </div>

  </div>

  <div class="evuba-footer-bottom">
    &copy; <span id="evuba-currentYear"></span> eVubaConnect. All rights reserved.
  </div>
</footer>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

<style>
/* Footer container */
.evuba-footer {
    background:#0A1128;
    color: #fff;
    font-family: Arial, sans-serif;
    margin-top:120px;
    z-index: 9999;
    position: relative;
    width: 100%;
}

.evuba-footer-container {
    display:flex;
    flex-wrap: wrap;
    justify-content: space-between;
    padding: 40px 20px;
    max-width: 1200px;
    margin: 0 auto;
}

/* Columns */
.evuba-footer-column {
    flex: 1 1 180px;
    margin: 10px;
}

.evuba-footer-column h3 {
    font-size: 16px;
    margin-bottom: 10px;
}

.evuba-footer-column p,
.evuba-footer-column a {
    font-size: 14px;
    color: #ccc;
    text-decoration: none;
    display: block;
    margin-bottom: 8px;
}

/* Email form */
.evuba-footer-form {
    display: flex;
    gap: 5px;
}

.evuba-footer-form input[type="email"] {
    padding: 8px;
    width: 70%;
    border: none;
    border-radius: 2px;
}

.evuba-footer-form button {
    padding: 8px 12px;
    background: transparent;
    border: 1px solid #fff;
    color: #fff;
    cursor: pointer;
}

/* Social icons */
.evuba-footer-download .evuba-footer-icons {
    display: flex;
    gap: 10px;
    margin-top: 10px;
}

.evuba-footer-download .evuba-footer-icons a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background-color: #222;
    border-radius: 50%;
    width: 32px;
    height: 32px;
    color: #fff;
    font-size: 14px;
    transition: background 0.3s;
}

.evuba-footer-download .evuba-footer-icons a:hover {
    background-color: #322EFF;
}

/* Bottom copyright */
.evuba-footer-bottom {
    text-align: center;
    font-size: 12px;
    color: #888;
    padding: 10px 0;
    background-color: #0b0b0b;
}

/* Responsive */
@media (max-width: 768px) {
    .evuba-footer-container {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>

<script>
    // Dynamic year
    document.getElementById('evuba-currentYear').textContent = new Date().getFullYear();
</script>
