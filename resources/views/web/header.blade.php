<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>eVuba Connect</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* ================= RESET (SCOPED) ================= */
.evc-page * {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.evc-page {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  padding-top: 110px;
  background-color: #0A1128;
  color: white;
}

/* ================= HEADER ================= */
.evc-header {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  background-color: #0A1128;
  z-index: 1000;
  height: 110px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 32px;
}

/* ================= LOGO ================= */
.evc-logo {
  margin-top: 90px;
  margin-left: 90px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  padding: 12px;
  width: 240px;
  height: 140px;
}

.evc-logo img {
  height: 150px;
  width: 150px;
  object-fit: contain;
  border-radius: 50%;
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
  gap: 30px;
  align-items: center;
}

.evc-nav a {
  position: relative;
  text-decoration: none;
  color: #cfd6ee;
  font-size: 14px;
  font-weight: 500;
  padding: 6px 0;
  transition: color 0.25s ease;
}

.evc-nav a:hover {
  color: #ffb703;
}

.evc-nav a.evc-active {
  color: #ffffff;
}

.evc-nav a.evc-active::after {
  content: "";
  position: absolute;
  left: 0;
  bottom: -10px;
  width: 100%;
  height: 2px;
  background-color: #ffffff;
  border-radius: 2px;
}

/* ================= AUTH ================= */
.evc-auth a {
  padding: 9px 18px;
  border: 1px solid #ffffff;
  border-radius: 6px;
  font-size: 13px;
  text-decoration: none;
  color: #ffffff;
  transition: background 0.3s ease, color 0.3s ease;
  display: inline-block;
}

.evc-auth a:hover {
  background: #ffffff;
  color: #0A1128;
}

/* ================= RESPONSIVE ================= */
@media (max-width: 900px) {
  .evc-header {
    height: auto;
    padding: 18px 20px;
    flex-wrap: wrap;
    gap: 14px;
  }

  .evc-nav-auth {
    width: 100%;
    justify-content: space-between;
  }

  .evc-logo {
    width: 120px;
    height: 120px;
    margin-top: 0;
    margin-left: 0;
  }

  .evc-logo img {
    height: 85px;
    width: 85px;
  }

  .evc-nav {
    gap: 20px;
  }
}

@media (max-width: 600px) {
  .evc-page {
    padding-top: 130px;
  }

  .evc-nav-auth {
    flex-direction: column;
    align-items: flex-start;
    gap: 14px;
  }

  .evc-nav {
    flex-wrap: wrap;
    gap: 18px;
  }

  .evc-logo {
    width: 100px;
    height: 100px;
  }

  .evc-logo img {
    height: 70px;
    width: 70px;
  }

  .evc-nav a.evc-active::after {
    bottom: -6px;
  }
}
</style>
</head>

<body class="evc-page">

<header class="evc-header">

  <div class="evc-logo">
    <img src="{{ asset('images/logo1.png') }}?v=3" alt="eVubaConnect Logo">
  </div>

  <div class="evc-nav-auth">
    <nav class="evc-nav" id="evcMainNav">

      <!-- SCROLL LINKS -->
      <a href="{{ url('/') }}#home" data-link="home">Home</a>
      <a href="{{ url('/') }}#about" data-link="about">About Us</a>
      <a href="{{ url('/') }}#services" data-link="services">Services</a>
      <a href="{{ url('/') }}#contact" data-link="contact">Contact</a>

      <div class="evc-auth">
        <a href="{{ route('auth.login') }}">Login</a>
      </div>

    </nav>
  </div>

</header>

<script>
/* ================= ACTIVE LINK (CLICK + SCROLL SAFE) ================= */
const evcLinks = document.querySelectorAll('#evcMainNav a[data-link]');

/* Restore active link */
const savedLink = localStorage.getItem('evcActiveNav');
if (savedLink) {
  evcLinks.forEach(link => {
    link.classList.toggle('evc-active', link.dataset.link === savedLink);
  });
} else {
  evcLinks[0].classList.add('evc-active');
}

/* Set active on click */
evcLinks.forEach(link => {
  link.addEventListener('click', () => {
    localStorage.setItem('evcActiveNav', link.dataset.link);
    evcLinks.forEach(l => l.classList.remove('evc-active'));
    link.classList.add('evc-active');
  });
});
</script>

</body>
</html>
