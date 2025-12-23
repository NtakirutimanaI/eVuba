<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; color: #1e293b; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 12px; border: 1px solid #e2e8f0; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #4f46e5; margin: 0; font-size: 24px; }
        .content { font-size: 16px; line-height: 1.6; }
        .details { background: #f1f5f9; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .details p { margin: 5px 0; font-weight: 500; }
        .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #64748b; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Appointment Updated</h1>
        </div>
        <div class="content">
            <p>Hello {{ $booking->user->name }},</p>
            <p>Your appointment has been rescheduled. Please review the new details below:</p>
            
            <div class="details">
                <p><strong>Service:</strong> {{ $booking->service->name }}</p>
                <p><strong>New Date:</strong> {{ \Carbon\Carbon::parse($booking->booking_date)->format('F d, Y') }}</p>
                <p><strong>New Time:</strong> {{ \Carbon\Carbon::parse($booking->booking_date)->format('h:i A') }}</p>
            </div>
            
            <p>If this time does not work for you, please contact us immediately.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} eVuba. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
