<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #4f46e5; color: white; padding: 15px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { padding: 20px; border: 1px solid #ddd; border-top: none; border-radius: 0 0 5px 5px; }
        .task-details { background: #f8fafc; padding: 15px; border-radius: 5px; margin: 15px 0; border-left: 4px solid #4f46e5; }
        .btn { display: inline-block; padding: 10px 20px; background: #4f46e5; color: white; text-decoration: none; border-radius: 5px; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>New Task Assigned</h2>
        </div>
        <div class="content">
            <p>Hello {{ $employee->name }},</p>
            <p>A new task has been automatically assigned to you based on a customer request.</p>
            
            <div class="task-details">
                <p><strong>Task:</strong> {{ $task->title }}</p>
                <p><strong>Priority:</strong> <span style="color: {{ $task->priority == 'urgent' ? 'red' : 'inherit' }}">{{ ucfirst($task->priority) }}</span></p>
                <p><strong>Due Date:</strong> {{ \Carbon\Carbon::parse($task->scheduled_at)->format('M d, Y h:i A') }}</p>
                <p><strong>Customer:</strong> {{ $customer ? $customer->name : 'N/A' }}</p>
                <p><strong>Description:</strong><br>{{ $task->description }}</p>
            </div>

            <p>Please log in to your dashboard to view full details and update the status.</p>
            
            <a href="{{ route('admin.appointments.index') }}" class="btn">View Task</a>
        </div>
    </div>
</body>
</html>
