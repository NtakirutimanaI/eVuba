<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #f59e0b; color: white; padding: 15px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { padding: 20px; border: 1px solid #ddd; border-top: none; border-radius: 0 0 5px 5px; }
        .task-details { background: #fffbeb; padding: 15px; border-radius: 5px; margin: 15px 0; border-left: 4px solid #f59e0b; }
        .btn { display: inline-block; padding: 10px 20px; background: #f59e0b; color: white; text-decoration: none; border-radius: 5px; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Task Reassigned to You</h2>
        </div>
        <div class="content">
            <p>Hello {{ $employee->name }},</p>
            <p>The following task has been reassigned to you.</p>
            
            <div class="task-details">
                <p><strong>Task:</strong> {{ $task->title }}</p>
                <p><strong>Reason:</strong> {{ $reason ?? 'Workload redistribution' }}</p>
                <p><strong>Priority:</strong> {{ ucfirst($task->priority) }}</p>
                <p><strong>Due Date:</strong> {{ \Carbon\Carbon::parse($task->scheduled_at)->format('M d, Y h:i A') }}</p>
            </div>

            <p>Please review the task details carefully.</p>
            
            <a href="{{ route('admin.appointments.index') }}" class="btn">View Task</a>
        </div>
    </div>
</body>
</html>
