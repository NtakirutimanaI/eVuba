<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    $customers = \App\Models\Customer::take(5)->get();
    if ($customers->isEmpty()) {
        $customer = \App\Models\Customer::create([
            'name' => 'Test Customer',
            'email' => 'customer' . time() . '@test.com',
            'password' => bcrypt('password'),
        ]);
        $customers = collect([$customer]);
    }

    $employee = \App\Models\Employee::first();
    if (!$employee) {
        $employee = \App\Models\Employee::create([
            'name' => 'Test Employee',
            'email' => 'emp' . time() . '@test.com',
            'password' => bcrypt('password'),
        ]);
    }

    foreach ($customers as $customer) {
        $ticket = \App\Models\Ticket::firstOrCreate([
            'customer_id' => $customer->id,
        ], [
            'subject' => 'Test Ticket',
            'description' => 'A ticket for feedback testing.',
            'status' => 'completed',
            'priority' => 'medium',
            'ticket_no' => 'TKT-' . rand(1000, 9999),
            'assigned_to' => $employee->id,
        ]);

        \App\Models\Feedback::firstOrCreate([
            'feedbackable_id' => $ticket->id,
            'feedbackable_type' => 'App\Models\Ticket',
        ], [
            'customer_id' => $customer->id,
            'status' => 'pending',
            'sla_threshold' => 1440,
            'actual_completion_time' => 120,
            'sla_compliant' => true,
        ]);
        echo "Created generic feedback for " . $customer->name . "\n";
    }
} catch (\Exception $e) {
    file_put_contents('err.log', $e->getMessage() . "\n" . $e->getTraceAsString());
    echo 'Error: ' . $e->getMessage() . "\n";
}
