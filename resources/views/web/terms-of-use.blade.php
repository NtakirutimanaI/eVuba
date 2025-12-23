<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms of Use - eVubaConnect</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --window-bg: #ffffff;
            --accent: #4f46e5;
            --accent-glow: rgba(79, 70, 229, 0.1);
            --text-main: #0f172a;
            --text-muted: #475569;
            --page-bg: #f8fafc;
        }

        body.terms-window-mode {
            font-family: 'Outfit', sans-serif;
            background-color: var(--page-bg);
            background-image: radial-gradient(circle at 20% 20%, rgba(79, 70, 229, 0.05) 0%, transparent 40%);
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
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .premium-window {
            background: var(--window-bg);
            border-radius: 24px;
            box-shadow: 0 40px 100px -20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .window-header {
            background: #0f172a;
            padding: 70px 40px;
            text-align: center;
            color: white;
            position: relative;
        }

        .window-header h1 {
            font-size: 2.8rem;
            font-weight: 800;
            margin: 0;
            background: linear-gradient(to right, #818cf8, #c084fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .window-header p {
            opacity: 0.7;
            margin: 15px 0 0;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .window-body {
            padding: 60px 80px;
        }

        .section-tag {
            color: var(--accent);
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 10px;
            display: block;
        }

        h2 {
            font-size: 1.6rem;
            color: var(--text-main);
            margin: 50px 0 20px;
            font-weight: 700;
        }

        p {
            line-height: 1.8;
            color: var(--text-muted);
            margin-bottom: 25px;
            font-size: 1.05rem;
        }

        .terms-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin: 40px 0;
        }

        .term-card {
            background: #f1f5f9;
            padding: 25px;
            border-radius: 16px;
            border-left: 4px solid var(--accent);
        }

        .term-card h4 {
            margin: 0 0 10px;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .legal-notice {
            margin-top: 60px;
            padding: 30px;
            background: #fff8eb;
            border: 1px solid #fee2e2;
            border-radius: 16px;
            font-style: italic;
            color: #92400e;
            font-size: 0.95rem;
        }

        @media (max-width: 768px) {
            .window-body { padding: 40px 25px; }
            .terms-grid { grid-template-columns: 1fr; }
            .window-header h1 { font-size: 2.2rem; }
        }
    </style>
</head>
<body class="terms-window-mode">
    @include('web.header')
    
    <div class="window-container">
        <div class="premium-window">
            <div class="window-header">
                <h1>Terms of Use</h1>
                <p>Legal Framework & User Agreement</p>
            </div>

            <div class="window-body">
                <span class="section-tag">Governing Agreement</span>
                <h2>1. Service Utilization</h2>
                <p>By accessing eVubaConnect, you enter into a legally binding agreement to comply with our professional standards and operational guidelines. This platform is designed for legitimate business enhancement and operational management.</p>

                <div class="terms-grid">
                    <div class="term-card">
                        <h4><i class="fas fa-shield-alt"></i> Authentication</h4>
                        <p style="font-size: 0.9rem; margin: 0;">You are responsible for maintaining the strict confidentiality of your account credentials.</p>
                    </div>
                    <div class="term-card">
                        <h4><i class="fas fa-ban"></i> Prohibited Acts</h4>
                        <p style="font-size: 0.9rem; margin: 0;">Automated scraping, unauthorized penetration testing, and data manipulation are strictly forbidden.</p>
                    </div>
                </div>

                <h2>2. Intellectual Property</h2>
                <p>All software architecture, visual interface designs, and conceptual frameworks within eVubaConnect remain the exclusive property of eVubaConnect. Modification, decompilation, or unauthorized mirroring of our services constitutes a direct breach of license.</p>

                <h2>3. Service Reliability</h2>
                <p>While we strive for 99.9% operational uptime, eVubaConnect is provided "as is". We reserve the right to perform necessary maintenance and system updates to ensure long-term stability and security.</p>

                <div class="legal-notice">
                    <strong>Note:</strong> These terms are subject to evolutionary updates. Continued use of the platform following modifications constitutes acceptance of the refined legal framework.
                </div>
            </div>
        </div>
    </div>

    @include('web.footer')
</body>
</html>
