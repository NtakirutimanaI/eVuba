<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - eVubaConnect</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --window-bg: #ffffff;
            --accent: #6c63ff;
            --accent-glow: rgba(108, 99, 255, 0.15);
            --text-main: #1e293b;
            --text-muted: #64748b;
            --page-bg: #f1f5f9;
        }

        body.policy-window-mode {
            font-family: 'Outfit', sans-serif;
            background-color: var(--page-bg);
            background-image: 
                radial-gradient(at 0% 0%, rgba(108, 99, 255, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(108, 99, 255, 0.05) 0px, transparent 50%);
            color: var(--text-main);
            margin: 0;
            min-height: 100vh;
        }

        .window-container {
            max-width: 1000px;
            margin: 120px auto 80px;
            padding: 0 20px;
            animation: windowAppear 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes windowAppear {
            from { opacity: 0; transform: translateY(30px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .premium-window {
            background: var(--window-bg);
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08), 0 0 0 1px rgba(0,0,0,0.02);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.7);
        }

        .window-header {
            background: linear-gradient(135deg, #6c63ff, #4f46e5);
            padding: 60px 40px;
            text-align: center;
            color: white;
            position: relative;
        }

        .window-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.02em;
        }

        .window-header p {
            opacity: 0.9;
            margin: 10px 0 0;
            font-size: 1.1rem;
            font-weight: 300;
        }

        .window-body {
            padding: 60px 80px;
        }

        .last-updated-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--accent-glow);
            color: var(--accent);
            padding: 6px 16px;
            border-radius: 100px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 40px;
        }

        h2 {
            font-size: 1.5rem;
            color: var(--text-main);
            margin: 45px 0 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        h2::before {
            content: '';
            width: 4px;
            height: 24px;
            background: var(--accent);
            border-radius: 2px;
        }

        p {
            line-height: 1.8;
            color: var(--text-muted);
            margin-bottom: 25px;
            font-size: 1.05rem;
        }

        .window-body ul {
            list-style: none;
            padding: 0;
            margin-bottom: 25px;
        }

        .window-body ul li {
            position: relative;
            padding-left: 28px;
            margin-bottom: 15px;
            color: var(--text-muted);
            line-height: 1.6;
        }

        .window-body ul li::before {
            content: "\f058";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            position: absolute;
            left: 0;
            color: var(--accent);
        }

        .contact-box {
            background: var(--page-bg);
            border-radius: 16px;
            padding: 30px;
            margin-top: 50px;
            border: 1px dashed var(--accent);
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .contact-icon {
            width: 50px;
            height: 50px;
            background: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent);
            font-size: 1.2rem;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        @media (max-width: 768px) {
            .window-body { padding: 40px 30px; }
            .window-header h1 { font-size: 2rem; }
            .window-container { margin-top: 100px; }
        }
    </style>
</head>
<body class="policy-window-mode">
    @include('web.header')
    
    <div class="window-container">
        <div class="premium-window">
            <div class="window-header">
                <h1>Privacy Policy</h1>
                <p>Ensuring your digital security and peace of mind.</p>
            </div>

            <div class="window-body">
                <div class="last-updated-pill">
                    <i class="far fa-calendar-alt"></i> Last Updated: December 22, 2025
                </div>

                <p>At eVubaConnect, your privacy is our unwavering commitment. We believe in being transparent about how we collect, use, and protect your personal information.</p>

                <h2>Our Data Philosophy</h2>
                <p>We only collect information that is strictly necessary for providing our premium services. We treat your data with the same level of security and confidentiality that we would expect for ourselves.</p>

                <h2>Information We Process</h2>
                <ul>
                    <li>Identity information provided during account creation</li>
                    <li>Usage data to help us improve the eVuba experience</li>
                    <li>Communication preferences and direct interaction history</li>
                    <li>Technical logs for system security and optimization</li>
                </ul>

                <h2>How We Use Your Data</h2>
                <p>Your information is primarily used to maintain smooth operations and develop new features that empower your business workflow. We never sell your personal information to third parties.</p>

                <h2>Global Security Standards</h2>
                <p>Our infrastructure utilizes state-of-the-art encryption and continuous monitoring. We implement rigorous technical and organizational measures to prevent unauthorized access or data breaches.</p>

                <div class="contact-box">
                    <div class="contact-icon">
                        <i class="fas fa-envelope-open-text"></i>
                    </div>
                    <div>
                        <div style="font-weight: 700; color: var(--text-main);">Data Privacy Inquiries</div>
                        <div style="font-size: 0.9rem; color: var(--text-muted);">Reach our privacy team at support@evubaconnect.com</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('web.footer')
</body>
</html>
