<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Service;
use App\Models\Employee;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['role' => 'customer']), // Create a user (client)
            'employee_id' => function() {
                // Find an existing employee or create one
                return Employee::inRandomOrder()->first()->id ?? Employee::factory()->create()->id; 
                // Note: Employee factory must handle User creation due to our fix
            },
            'service_id' => function() {
                return Service::inRandomOrder()->first()->id ?? Service::factory()->create()->id;
            },
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'booking_date' => $this->faker->dateTimeBetween('-1 month', '+1 month'),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected', 'completed', 'cancelled']),
        ];
    }
}
