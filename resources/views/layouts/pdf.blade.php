<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Report</title>
    <style>
        /* RESET & BASICS */
        /* RESET & BASICS */
        @page { 
            margin: 160px 40px 100px 40px; /* MAJOR MARGIN INCREASE for header clearance */
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px; /* Increased from 10px for visibility */
            color: #333;
            line-height: 1.5;
        }

        /* FIXED HEADER */
        header {
            position: fixed;
            top: -120px;
            left: 0;
            right: 0;
            height: 110px;
            background: #fff;
            z-index: 1000;
        }

        .header-container {
            width: 100%;
            padding-bottom: 10px;
            border-bottom: 2px solid #e2e8f0;
        }

        .header-left {
            float: left;
            width: 40%;
            padding-top: 10px;
        }

        .logo-img {
            max-height: 75px;
            max-width: 180px;
        }
        
        .logo-text {
            font-size: 24px;
            font-weight: 800;
            color: #4f46e5;
        }

        .header-right {
            float: right;
            width: 50%;
            text-align: right;
        }

        .company-wrapper {
            margin-top: 5px;
        }

        .company-name {
            font-size: 18px;
            font-weight: 800;
            color: #1e293b;
            text-transform: uppercase;
            margin-bottom: 8px;
            display: inline-block;
            /* TRACE SHORT LINE ABOVE TITLE */
            border-top: 4px solid #4f46e5;
            padding-top: 5px;
        }

        .company-details {
            font-size: 11px;
            color: #64748b;
            line-height: 1.5;
        }

        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        /* FIXED FOOTER */
        footer {
            position: fixed;
            bottom: -60px;
            left: 0;
            right: 0;
            height: 50px;
            background: #fff;
            z-index: 1000;
            text-align: center;
        }

        .footer-container {
            width: 100%;
        }

        .footer-line {
            height: 1px;
            background: #e2e8f0;
            margin-bottom: 8px;
            width: 100%;
        }

        .footer-text {
            font-size: 10px;
            line-height: 1.4;
        }

        .footer-text.warning {
            color: #ef4444;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .footer-text.meta {
            color: #94a3b8;
        }
        
        .page-number:after { content: counter(page); }

        /* CONTENT AREA */
        .content {
            margin-top: 20px;
            position: relative;
            z-index: 1;
        }

        /* UTILITY: Tables */
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
            /* Remove table-layout: fixed if column content varies wildly, 
               but keep auto to allow browser to size based on content */
            table-layout: auto; 
        }
        th, td { 
            border: 1px solid #ccc; 
            padding: 10px 8px; /* More breathing room */
            text-align: left; 
            vertical-align: top; /* Align top so multi-line text looks good */
            font-size: 11px; /* Readable table text */
        }
        th { 
            background-color: #f1f5f9; 
            color: #1e293b;
            font-weight: 700;
            text-transform: uppercase;
        }
        tr { page-break-inside: avoid; } /* Try to keep rows together */
        tr:nth-child(even) { background-color: #f8fafc; }

        /* SUMMARY BOXES */
        .summary-box {
            margin-top: 30px;
            width: 45%;
            margin-left: auto;
            border: 1px solid #ccc;
            background: #fff;
            page-break-inside: avoid;
        }
        .summary-row {
            display: block;
            padding: 10px 15px;
            border-bottom: 1px solid #eee;
        }
        .summary-row:last-child { border-bottom: none; }
        .summary-label { font-weight: 600; font-size: 12px; }
        .summary-val { float: right; font-weight: 700; font-size: 12px; }
        
        .total-highlight {
            background: #e0e7ff !important; /* Lighter blue for better print contrast */
            border-top: 2px solid #4f46e5;
        }
        .total-highlight .summary-label { color: #000; }
        .total-highlight .summary-val { color: #000; }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="header-left">
                @if(file_exists(public_path('images/logo.png')))
                    <img src="{{ public_path('images/logo.png') }}" alt="eVuba Connect" class="logo-img">
                @else
                    <div class="logo-text">eVuba</div>
                @endif
            </div>
            
            <div class="header-right">
                <div class="company-wrapper">
                    <div class="company-name">eVuba Connect</div>
                    <div class="company-details">
                        Address: Kigali-Gisozi, Rwanda<br>
                        Email: evubaconnect@gmail.com<br>
                        Date: {{ date('F d, Y') }}
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </header>

    <footer>
        <div class="footer-container">
            <div class="footer-line"></div>
            <div class="footer-text warning">
                This report is for the company internal use only or other permitted parties
            </div>
            <div class="footer-text meta">
                Secure System Generated Report &bull; Page <span class="page-number"></span> &bull; &copy; {{ date('Y') }} eVuba Connect
            </div>
        </div>
    </footer>

    <!-- Main Content -->
    <div class="content">
        @yield('content')
    </div>
</body>
</html>
