<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - eVubaConnect</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8fafc; color: #1f2937; margin: 0; }
        .page-hero { background: linear-gradient(135deg, #3b82f6, #2563eb); padding: 120px 20px 40px; text-align: center; color: white; }
        .page-hero h1 { font-size: 3rem; margin: 0; }
        .page-content { margin-top: -60px; padding-bottom: 60px; }
    </style>
</head>
<body>
    @include('web.header')
    
    <div class="page-hero">
        <h1>Get in Touch</h1>
    </div>

    <div class="page-content">
        @include('web.form-contact')
    </div>

    @include('web.footer')
</body>
</html>
