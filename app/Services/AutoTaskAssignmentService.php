<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Booking;
use App\Models\Order;
use App\Models\MessageUs;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AutoTaskAssignmentService
{
    /**
     * Automatically create and assign task from support ticket
     */
    public function createTaskFromSupportTicket($ticket)
    {
        // Determine priority based on ticket urgency or category
        $priority = $this->determinePriorityFromTicket($ticket);
        
        // Find best available employee
        $employee = $this->findBestEmployee('support');
        
        // Resolve User from Ticket Customer
        $userId = null;
        if ($ticket->customer && $ticket->customer->email) {
            $user = User::where('email', $ticket->customer->email)->first();
            $userId = $user ? $user->id : null;
        }

        // Create appointment/task
        $appointment = Appointment::create([
            'title' => 'Support Ticket: ' . $ticket->subject,
            'description' => $ticket->description, // Changed from message to description
            'user_id' => $userId, // Use resolved User ID
            'employee_id' => $employee?->id,
            'scheduled_at' => now()->addHours(2), // Default 2 hours from now
            'status' => 'pending',
            'priority' => $priority,
            'source_type' => 'ticket', // Changed to match model
            'source_id' => $ticket->id,
            'auto_assigned' => true,
            'assigned_at' => now(),
            'assignment_notes' => 'Auto-assigned based on support ticket #' . $ticket->id
        ]);

        // Send notifications
        // Pass User object if found, otherwise Customer object wrapped/mocked or handle in mailer
        $customerObj = $ticket->customer; // This is the Customer model
        $this->sendTaskNotifications($appointment, $employee, $customerObj);

        return $appointment;
    }

    /**
     * Create task from booking
     */
    public function createTaskFromBooking($booking)
    {
        $priority = $this->determinePriorityFromBooking($booking);
        $employee = $this->findBestEmployee('booking');

        $appointment = Appointment::create([
            'title' => 'Service Booking: ' . ($booking->service->name ?? 'Service Request'),
            'description' => 'Customer booking for ' . ($booking->service->name ?? 'service'),
            'user_id' => $booking->user_id,
            'employee_id' => $employee?->id,
            'scheduled_at' => $booking->booking_date ?? now()->addDay(),
            'status' => 'confirmed',
            'priority' => $priority,
            'source_type' => 'booking',
            'source_id' => $booking->id,
            'auto_assigned' => true,
            'assigned_at' => now(),
            'assignment_notes' => 'Auto-assigned from booking #' . $booking->id
        ]);

        $this->sendTaskNotifications($appointment, $employee, $booking->user);

        return $appointment;
    }

    /**
     * Create task from order
     */
    public function createTaskFromOrder($order)
    {
        $priority = 'high'; // Orders are usually high priority
        $employee = $this->findBestEmployee('order');

        $appointment = Appointment::create([
            'title' => 'Order Processing: Order #' . $order->id,
            'description' => 'Process and fulfill customer order',
            'user_id' => $order->user_id,
            'employee_id' => $employee?->id,
            'scheduled_at' => now()->addHours(1),
            'status' => 'confirmed',
            'priority' => $priority,
            'source_type' => 'order',
            'source_id' => $order->id,
            'auto_assigned' => true,
            'assigned_at' => now(),
            'assignment_notes' => 'Auto-assigned from order #' . $order->id
        ]);

        $this->sendTaskNotifications($appointment, $employee, $order->user);

        return $appointment;
    }

    /**
     * Create task from message
     */
    public function createTaskFromMessage($message)
    {
        $priority = $message->priority ?? 'medium';
        $employee = $this->findBestEmployee('message');
        
        // Subject doesn't exist, so use truncated message as title
        $title = 'Customer Inquiry: ' . Str::limit($message->message, 30);

        $appointment = Appointment::create([
            'title' => $title,
            'description' => $message->message,
            'user_id' => null, // Messages might not have user_id
            'employee_id' => $employee?->id,
            'scheduled_at' => now()->addHours(4),
            'status' => 'pending',
            'priority' => $priority,
            'source_type' => 'message',
            'source_id' => $message->id,
            'auto_assigned' => true,
            'assigned_at' => now(),
            'assignment_notes' => 'Auto-assigned from customer message #' . $message->id
        ]);

        $this->sendTaskNotifications($appointment, $employee, null);

        return $appointment;
    }

    /**
     * Find best available employee based on workload and type
     */
    private function findBestEmployee($taskType = null)
    {
        // Get all active employees
        $employees = User::where('role', 'employee')
            ->where('is_active', true)
            ->get();

        if ($employees->isEmpty()) {
            return null;
        }

        // Count current pending/confirmed tasks for each employee
        $employeeWorkload = [];
        foreach ($employees as $employee) {
            $workload = Appointment::where('employee_id', $employee->id)
                ->whereIn('status', ['pending', 'confirmed'])
                ->count();
            
            $employeeWorkload[$employee->id] = [
                'employee' => $employee,
                'workload' => $workload
            ];
        }

        // Sort by workload (ascending) and return employee with least tasks
        usort($employeeWorkload, function($a, $b) {
            return $a['workload'] <=> $b['workload'];
        });

        return $employeeWorkload[0]['employee'] ?? null;
    }

    /**
     * Determine priority from support ticket
     */
    private function determinePriorityFromTicket($ticket)
    {
        // Check ticket category or status
        if (isset($ticket->category)) {
            $urgentCategories = ['technical', 'billing', 'urgent'];
            if (in_array(strtolower($ticket->category), $urgentCategories)) {
                return 'urgent';
            }
        }

        if (isset($ticket->priority)) {
            return $ticket->priority;
        }

        return 'medium';
    }

    /**
     * Determine priority from booking
     */
    private function determinePriorityFromBooking($booking)
    {
        // Check booking date - if soon, higher priority
        if (isset($booking->booking_date)) {
            $daysUntil = now()->diffInDays($booking->booking_date, false);
            if ($daysUntil <= 1) {
                return 'urgent';
            } elseif ($daysUntil <= 3) {
                return 'high';
            }
        }

        return 'medium';
    }

    /**
     * Send email notifications to employee and customer
     */
    private function sendTaskNotifications($appointment, $employee, $customer)
    {
        try {
            // Send to employee
            if ($employee && $employee->email) {
                Mail::send('emails.task-assigned', [
                    'employee' => $employee,
                    'task' => $appointment,
                    'customer' => $customer
                ], function ($message) use ($employee, $appointment) {
                    $message->to($employee->email)
                        ->subject('New Task Assigned: ' . $appointment->title);
                });
            }

            // Send to customer
            if ($customer && $customer->email) {
                Mail::send('emails.task-created-customer', [
                    'customer' => $customer,
                    'task' => $appointment,
                    'employee' => $employee
                ], function ($message) use ($customer, $appointment) {
                    $message->to($customer->email)
                        ->subject('Your Request Has Been Assigned: ' . $appointment->title);
                });
            }
        } catch (\Exception $e) {
            Log::error('Failed to send task notification emails: ' . $e->getMessage());
        }
    }

    /**
     * Reassign task to different employee
     */
    public function reassignTask($appointmentId, $newEmployeeId, $reason = null)
    {
        $appointment = Appointment::findOrFail($appointmentId);
        $oldEmployee = $appointment->employee;
        $newEmployee = User::findOrFail($newEmployeeId);

        $appointment->update([
            'employee_id' => $newEmployeeId,
            'assigned_at' => now(),
            'assignment_notes' => $reason ?? 'Manually reassigned'
        ]);

        // Send notifications about reassignment
        try {
            if ($newEmployee->email) {
                Mail::send('emails.task-reassigned', [
                    'employee' => $newEmployee,
                    'task' => $appointment,
                    'reason' => $reason
                ], function ($message) use ($newEmployee, $appointment) {
                    $message->to($newEmployee->email)
                        ->subject('Task Reassigned to You: ' . $appointment->title);
                });
            }
        } catch (\Exception $e) {
            Log::error('Failed to send reassignment email: ' . $e->getMessage());
        }

        return $appointment;
    }

    /**
     * Get task assignment statistics
     */
    public function getAssignmentStats()
    {
        return [
            'total_auto_assigned' => Appointment::where('auto_assigned', true)->count(),
            'total_manual_assigned' => Appointment::where('auto_assigned', false)->count(),
            'pending_tasks' => Appointment::where('status', 'pending')->count(),
            'in_progress_tasks' => Appointment::where('status', 'confirmed')->count(),
            'completed_tasks' => Appointment::where('status', 'completed')->count(),
            'employee_workload' => $this->getEmployeeWorkload()
        ];
    }

    /**
     * Get workload per employee
     */
    private function getEmployeeWorkload()
    {
        $employees = User::where('role', 'employee')->get();
        $workload = [];

        foreach ($employees as $employee) {
            $workload[$employee->name] = [
                'pending' => Appointment::where('employee_id', $employee->id)
                    ->where('status', 'pending')->count(),
                'in_progress' => Appointment::where('employee_id', $employee->id)
                    ->where('status', 'confirmed')->count(),
                'completed' => Appointment::where('employee_id', $employee->id)
                    ->where('status', 'completed')->count(),
            ];
        }

        return $workload;
    }
}
