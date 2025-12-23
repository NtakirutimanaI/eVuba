<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #10b981; color: white; padding: 15px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { padding: 20px; border: 1px solid #ddd; border-top: none; border-radius: 0 0 5px 5px; }
        .info-box { background: #f0fdf4; padding: 15px; border-radius: 5px; margin: 15px 0; border-left: 4px solid #10b981; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>We're Working on Your Request</h2>
        </div>
        <div class="content">
            <p>Hello {{ $customer->name }},</p>
            <p>We have received your request and have assigned it to a dedicated team member.</p>
            
            <div class="info-box">
                <p><strong>Reference:</strong> {{ $task->title }}</p>
                <p><strong>Assigned To:</strong> {{ $employee ? $employee->name : 'Support Team' }}</p>
                <p><strong>Status:</strong> Processing</p>
                <p><strong>Estimated Response:</strong> {{ \Carbon\Carbon::parse($task->scheduled_at)->diffForHumans() }}</p>
            </div>

            <p>We will notify you as soon as there is an update on your request.</p>
            <p>Thank you for choosing eVubaConnect!</p>
        </div>
    </div>
</body>
</html>
