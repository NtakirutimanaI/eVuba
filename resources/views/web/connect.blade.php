
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>umurinzi Dashboard Preview</title>
    <style>
        /* ===== RESET ONLY FOR THIS PAGE ===== */
        .umurinzi-body {
            margin: 0;
            font-family: Arial, sans-serif;
            color: white;
        }

        /* ===== HERO SECTION ===== */
        .umurinzi-hero {
            padding: 40px 20px;
            display: flex;
            justify-content: center;
        }

        .umurinzi-hero-content {
            display: flex;
            align-items: center;
            position: relative;
            flex-wrap: wrap;
            max-width: 1200px;
            top:45px;
        }

        .umurinzi-left-image img {
            width: 250px;
            z-index: 2;
            max-width: 100%;
        }

        .umurinzi-dashboard-preview img {
            width: 700px;
            border-radius: 8px;
            max-width: 100%;
        }

        .umurinzi-mobile-preview {
            position: absolute;
            right: -40px;
            bottom: -50px;
        }

        .umurinzi-mobile-preview img {
            width: 200px;
            max-width: 100%;
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .umurinzi-mobile-preview {
                position: relative;
                right: 0;
                bottom: 0;
                margin-top: 20px;
            }
        }

        /* ===== PARTNERS SECTION ===== */
        .umurinzi-partners-section {
            background: #f5f5f5;
            padding: 20px;
            text-align: center;
            color: black;
        }

        .umurinzi-partners-section h3 {
            margin-bottom: 20px;
            font-weight: 500;
        }

       

        .umurinzi-marquee-content {
            display: inline-flex;
            animation: umurinzi-marquee 15s linear infinite;
        }

        .umurinzi-marquee-content img {
            height: 50px;
            margin: 0 30px;
            object-fit: contain;
            max-width: 100%;
        }

        @keyframes umurinzi-marquee {
            from {
                transform: translateX(0);
            }
            to {
                transform: translateX(-50%);
            }
        }

        /* Pause animation on hover */
        .umurinzi-partners-marquee:hover .umurinzi-marquee-content {
            animation-play-state: paused;
        }
    </style>
</head>
<body class="umurinzi-body">

    <!-- Main Container -->
    <section class="umurinzi-hero">
        <div class="umurinzi-hero-content">
            <div class="umurinzi-left-image">
                <img src="{{asset('images/invoices.png')}}" alt="Woman Thumbs Up">
            </div>
            <div class="umurinzi-dashboard-preview">
                <img src="{{asset('images/toop.png')}}" alt="Dashboard">
            </div>
        </div>
    </section>

   

</body>
</html>
