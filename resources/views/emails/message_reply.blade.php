<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Response to your Inquiry</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #334155; background-color: #f1f5f9; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
        <h2 style="color: #6366f1; margin-top: 0;">Hello,</h2>
        
        <p>Thank you for contacting us. We have received your inquiry and here is our response:</p>
        
        <div style="background-color: #f8fafc; border-left: 4px solid #6366f1; padding: 15px; margin: 20px 0; border-radius: 4px;">
            <p style="margin: 0; white-space: pre-wrap;">{{ $reply->reply_content }}</p>
        </div>

        <p style="font-size: 14px; color: #64748b; margin-top: 30px; border-top: 1px solid #e2e8f0; padding-top: 20px;">
            Best regards,<br>
            <strong>{{ config('app.name') }} Team</strong>
        </p>
    </div>
</body>
</html>
