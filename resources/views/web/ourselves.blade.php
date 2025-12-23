<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<style>
/* ===== Base Styles ===== */
body.evuba-about-body {
    font-family: 'Inter', sans-serif;
    margin: 0;
    background-color: #f7f8fc;
    color: #1f1f1f;
    scroll-behavior: smooth;
}
a.evuba-link {
    color: #6c63ff;
    text-decoration: none;
    transition: color 0.3s ease;
}
a.evuba-link:hover { color: #594ddf; }

/* ===== Hero Section ===== */
.evuba-hero {
    background: linear-gradient(135deg,#6c63ff,#8c7bff);
    color: #fff;
    text-align: center;
    padding: 6rem 2rem 5rem;
    position: relative;
    overflow: hidden;
}
.evuba-hero h1 {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0;
    transform: translateY(-50px);
    transition: all 0.8s ease;
}
.evuba-hero p {
    font-size: 1.2rem;
    max-width: 700px;
    margin: 0 auto;
    line-height: 1.6;
    opacity: 0;
    transform: translateY(-50px);
    transition: all 0.8s ease 0.3s;
}

/* ===== Sections ===== */
.evuba-section { max-width: 1200px; margin: 4rem auto; padding: 0 2rem; }
.evuba-section h2 {
    font-size: 2rem;
    text-align: center;
    margin-bottom: 1rem;
    color: #6c63ff;
    position: relative;
}
.evuba-section h2::after {
    content: '';
    width: 60px;
    height: 3px;
    background: #6c63ff;
    display: block;
    margin: 0.5rem auto 0;
    border-radius: 3px;
}
.evuba-section p {
    font-size: 1rem;
    line-height: 1.8;
    text-align: center;
    max-width: 900px;
    margin: 0 auto 2rem;
}

/* ===== Feature Cards Grid ===== */
.evuba-features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px,1fr));
    gap: 2rem;
    margin-top: 3rem;
}
.evuba-feature-card {
    background-color: #fff;
    border-radius: 12px;
    padding: 2rem 1.5rem;
    text-align: center;
    transform: translateY(50px);
    opacity: 0;
    transition: all 0.6s ease, transform 0.4s ease;
}
.evuba-feature-card i {
    font-size: 2.5rem;
    color: #6c63ff;
    margin-bottom: 1rem;
    transition: transform 0.3s ease, color 0.3s ease;
}
.evuba-feature-card:hover i {
    transform: scale(1.2);
    color: #594ddf;
}
.evuba-feature-card h3 { margin-bottom: 1rem; color:#1f1f1f; font-size:1.1rem; }
.evuba-feature-card ul { padding-left: 1.25rem; list-style-type: disc; text-align: left; }
.evuba-feature-card ul li { margin-bottom: 0.75rem; font-size: 0.95rem; line-height:1.5; }

/* ===== Image / Figure Section ===== */
.evuba-image-section {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 2rem;
    margin-top: 3rem;
}
.evuba-image-card {
    position: relative;
    overflow: hidden;
    border-radius: 12px;
    flex: 1 1 300px;
    max-width: 350px;
    cursor: pointer;
    transform: translateY(50px);
    opacity: 0;
    transition: all 0.6s ease;
}
.evuba-image-card img {
    width: 100%;
    display: block;
    transition: transform 0.5s ease;
}
.evuba-image-card:hover img { transform: scale(1.1); }
.evuba-image-card .caption {
    position: absolute;
    bottom: 0;
    background: rgba(0,0,0,0.6);
    color: #fff;
    width: 100%;
    padding: 1rem;
    text-align: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}
.evuba-image-card:hover .caption { opacity: 1; }

/* ===== Impact Section ===== */
.evuba-impact {
    background-color: #565663ff;
    color: #fff;
    text-align: center;
    padding: 4rem 2rem;
}
.evuba-impact h2 { color:#fff; margin-bottom:2rem; }
.evuba-impact ul {
    display: grid;
    grid-template-columns: repeat(auto-fit,minmax(220px,1fr));
    gap: 1.5rem;
    margin-top: 2rem;
    list-style:none;
    padding:0;
}
.evuba-impact ul li {
    background: rgba(255,255,255,0.15);
    padding: 1.5rem;
    border-radius: 10px;
    font-weight: 600;
    font-size: 1rem;
    transition: transform 0.3s ease, background 0.3s ease;
    opacity:0;
    transform: translateY(30px);
}
.evuba-impact ul li:hover { transform: translateY(-5px); background: rgba(255,255,255,0.25); }

/* ===== Animations ===== */
.evuba-show { opacity: 1 !important; transform: translateY(0) !important; }
</style>
<body class="evuba-about-body">

</section>


<!-- Impact Section -->
<section class="evuba-impact">
    <h2>Expected Impact</h2>
    <ul>
        <li>Increases operational efficiency through automated workflows.</li>
        <li>Enhances customer experience with faster, AI-powered support.</li>
        <li>Reduces human error in appointment scheduling and service delivery.</li>
        <li>Optimizes employee management by tracking work performance.</li>
        <li>Improves inventory accuracy with real-time stock tracking.</li>
    </ul>
</section>

<script>
// ===== Scroll animation =====
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if(entry.isIntersecting){
            entry.target.classList.add('evuba-show');
        }
    });
}, { threshold: 0.2 });

document.querySelectorAll('.evuba-feature-card, .evuba-image-card, .evuba-impact ul li, .evuba-hero h1, .evuba-hero p').forEach(el => {
    observer.observe(el);
});
</script>

