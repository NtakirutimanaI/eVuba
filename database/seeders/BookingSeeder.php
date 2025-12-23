<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\User;
use App\Models\Service;
use App\Models\Employee;
use Illuminate\Support\Str;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // specific data for testing
        // Ensure we have some services
        if (Service::count() == 0) {
             // We can't easily use Service Factory if it doesn't exist, proceed with caution or manual create
             // Assuming User has Services created manually or we rely on existing ones
             // If no services, bookings will fail factory.
        }

        // Create 20 random bookings
        // We use try/catch in case Factories for Service/Employee don't exist
        try {
             Booking::factory()->count(20)->create();
        } catch (\Exception $e) {
             // Fallback if factories fail
             $this->command->info("Factory failed: " . $e->getMessage() . ". Creating manual bookings.");
             
             // Get Dependencies
             $employee = Employee::first();
             $service = Service::first();
             $client = User::factory()->create();

             if ($employee && $service) {
                 Booking::create([
                    'user_id' => $client->id,
                    'employee_id' => $employee->id,
                    'service_id' => $service->id,
                    'title' => 'Manual Seeding Booking',
                    'description' => 'Created because factory failed',
                    'booking_date' => now()->addDays(2),
                    'status' => 'pending'
                 ]);
             }
        }
    }
}
