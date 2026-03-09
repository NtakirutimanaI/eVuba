<?php

use App\Models\Customer;
use App\Models\Ticket;
use App\Models\Appointment;
use App\Models\Feedback;
use App\Models\Employee;

// Get the first employee to assign
$employee = Employee::first() ?? Employee::factory()->create();

// Ensure we have some customers
$customers = Customer::take(5)->get();

if ($customers->isEmpty()) {
    echo "No customers found. Creating one...\n";
    $customer = Customer::create([
        'name' => 'Test Customer',
        'email' => 'customer@test.com',
        'password' => bcrypt('password'),
    ]);
    $customers = collect([$customer]);
}

foreach ($customers as $customer) {
    // Check if customer has a ticket
    $ticket = Ticket::where('customer_id', $customer->id)->first();
    if (!$ticket) {
        $ticket = Ticket::create([
            'customer_id' => $customer->id,
            'subject' => 'Test Feedback Ticket',
            'description' => 'A completed ticket to test feedback.',
            'status' => 'completed',
            'priority' => 'medium',
            'ticket_no' => 'TKT-' . rand(1000, 9999),
            'assigned_to' => $employee->id,
        ]);

        // This triggers the model event 'updated' but let's make sure feedback exists
        Feedback::firstOrCreate([
            'feedbackable_id' => $ticket->id,
            'feedbackable_type' => 'App\Models\Ticket',
        ], [
            'customer_id' => $customer->id,
            'status' => 'pending',
            'sla_threshold' => 1440,
            'actual_completion_time' => rand(60, 1000),
            'sla_compliant' => true,
        ]);

        echo "Created Ticket/Feedback for Customer {$customer->name}\n";
    }

    // Determine if feedback already generated
    $feedbackCount = Feedback::where('customer_id', $customer->id)->count();
    if ($feedbackCount === 0) {
        // Manually trigger feedback for existing Ticket if any
        if ($ticket) {
            Feedback::firstOrCreate([
                'feedbackable_id' => $ticket->id,
                'feedbackable_type' => 'App\Models\Ticket',
            ], [
                'customer_id' => $customer->id,
                'status' => 'pending',
                'sla_threshold' => 1440,
                'actual_completion_time' => 120,
                'sla_compliant' => true,
            ]);
        }
    }
}
echo "Seeding completed!\n";
