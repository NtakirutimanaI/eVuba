<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Ticket;
use App\Models\Customer;
use App\Models\Booking; 
use App\Models\Order;   
use App\Models\MessageUs;
use App\Services\AutoTaskAssignmentService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TaskAssignmentDemoSeeder extends Seeder
{
    public function run()
    {
        $service = new AutoTaskAssignmentService();
        
        $this->command->info('Creating Demo Employees...');
        
        // Create 3 Employees with different specializations
        $employees = [
            ['name' => 'John Tech', 'email' => 'john.tech@evuba.com'],
            ['name' => 'Sarah Support', 'email' => 'sarah.support@evuba.com'],
            ['name' => 'Mike Sales', 'email' => 'mike.sales@evuba.com'],
        ];

        foreach ($employees as $emp) {
            User::firstOrCreate(
                ['email' => $emp['email']],
                [
                    'name' => $emp['name'],
                    'password' => Hash::make('password'),
                    'role' => 'employee',
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );
        }

        $this->command->info('Creating Demo Customers...');
        
        // Create 5 Customers (User + Customer Profile)
        $simulatedData = [];
        for ($i = 1; $i <= 5; $i++) {
            $email = "customer{$i}@test.com";
            
            // Create User Account
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => "Customer {$i}",
                    'password' => Hash::make('password'),
                    'role' => 'customer',
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );
            
            // Create Customer Profile
            $customerProfile = Customer::firstOrCreate(
                ['email' => $email],
                [
                    'name' => "Customer {$i}",
                    'phone' => '078000000' . $i,
                    'address' => 'Kigali, Rwanda'
                ]
            );
            
            $simulatedData[] = ['user' => $user, 'profile' => $customerProfile];
        }

        $this->command->info('Simulating Customer Requests & Auto-Assignment...');

        // 1. Create Support Tickets -> Auto Tasks
        // Ticket uses customer_id from customers table
        foreach ($simulatedData as $index => $data) {
            if ($index % 2 == 0) { 
                if (class_exists(Ticket::class)) {
                    $ticket = new Ticket();
                    $ticket->customer_id = $data['profile']->id;
                    $ticket->subject = "Issue with Order #100{$index}";
                    $ticket->description = "I haven't received my order yet. Please help.";
                    $ticket->status = 'open';
                    $ticket->priority = $index == 0 ? 'urgent' : 'medium';
                    $ticket->category_id = 1; 
                    $ticket->save();

                    $service->createTaskFromSupportTicket($ticket);
                    $this->command->info("Created Task from Ticket #{$ticket->id}");
                }
            }
        }

        // Create a Dummy Service for bookings
        $serviceId = null;
        if (class_exists(\App\Models\Service::class)) { // Or AdminService if named differently
             // Check if any service exists
             $svc = \App\Models\Service::first();
             if (!$svc) {
                 $svc = new \App\Models\Service();
                 $svc->name = 'General Consultation';
                 $svc->description = 'General service consultation';
                 $svc->price = 5000;
                 $svc->duration = 60;
                 $svc->save();
             }
             $serviceId = $svc->id;
        }

        // 2. Create Bookings -> Auto Tasks
        // Booking uses user_id from users table
        if (class_exists(Booking::class) && $serviceId) {
             foreach ($simulatedData as $index => $data) {
                if ($index % 2 != 0) {
                    $booking = new Booking();
                    $booking->user_id = $data['user']->id;
                    $booking->title = 'Service Booking Request';
                    $booking->service_id = $serviceId;
                    $booking->booking_date = now()->addDays($index + 1);
                    $booking->status = 'pending';
                    $booking->save();

                    $service->createTaskFromBooking($booking);
                    $this->command->info("Created Task from Booking #{$booking->id}");
                }
            }
        }

        // 3. Create Orders -> Auto Tasks
        // Order uses user_id from users table
        if (class_exists(Order::class)) {
             $data = $simulatedData[0];
             $order = new Order();
             $order->user_id = $data['user']->id;
             $order->product_name = 'Premium Service Package';
             $order->quantity = 1;
             $order->price = 5000;
             $order->status = 'pending';
             // $order->order_number removed as it doesn't exist
             $order->save();

             $service->createTaskFromOrder($order);
             $this->command->info("Created Task from Order #{$order->id}");
        }

        // 4. Create Messages -> Auto Tasks
         if (class_exists(MessageUs::class)) {
             $message = new MessageUs();
             $message->first_name = 'Guest';
             $message->last_name = 'Visitor';
             $message->email = 'guest@visitor.com';
             // $message->subject removed
             $message->message = 'Do you offer bulk discounts for corporate orders?';
             $message->save();

             $service->createTaskFromMessage($message);
             $this->command->info("Created Task from Message #{$message->id}");
        }
        
        $this->command->info('Seeding Completed Successfully!');
    }
}
