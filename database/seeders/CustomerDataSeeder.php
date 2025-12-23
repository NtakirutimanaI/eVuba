<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Appointment;
use App\Models\Booking;
use App\Models\Ticket;
use App\Models\SupportCategory;
use App\Models\Announcement;
use App\Models\Category;
use App\Models\Service;
use Carbon\Carbon;

class CustomerDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        // 1. Create or Find Demo Customer
        $customer = User::firstOrCreate(
            ['email' => 'customer@evuba.com'],
            [
                'name' => 'Demo Customer',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $this->command->info('Customer Account: ' . $customer->email);

        // 1b. Ensure a 'Customer' record exists with the SAME ID
        $existingCustomer = DB::table('customers')->where('email', $customer->email)->first();
        if (!$existingCustomer) {
            DB::table('customers')->insert([
                'id' => $customer->id, // Force ID match
                'name' => $customer->name,
                'email' => $customer->email,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 2. Create Product Categories & Products (if not exist)
        $categories = ['Electronics', 'Home & Office', 'Services', 'Software'];
        foreach ($categories as $catName) {
            Category::firstOrCreate(['name' => $catName], ['description' => $catName . ' items']);
        }
        
        // Ensure we have at least 5 products
        if (Product::count() < 5) {
            $catIds = Category::pluck('id')->toArray();
            for ($i = 0; $i < 5; $i++) {
                Product::create([
                    'name' => $faker->words(3, true) . ' Pro',
                    'description' => $faker->sentence(10),
                    'category_id' => $faker->randomElement($catIds),
                    'unit_price' => $faker->numberBetween(5000, 500000),
                    'product_code' => 'PRD-' . strtoupper($faker->bothify('##??')),
                    'stock_quantity' => $faker->numberBetween(10, 100), 
                    'status' => 'published',
                    'image' => null
                ]);
            }
        }
        $products = Product::all();

        // 3. Seed Orders (5 rows)
        for ($i = 0; $i < 5; $i++) {
            $product = $products->random();
            $qty = $faker->numberBetween(1, 5);
            Order::create([
                'user_id' => $customer->id, 
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => $qty,
                'price' => $product->unit_price,
                'status' => $faker->randomElement(['pending', 'processing', 'completed', 'cancelled']),
                'created_at' => $faker->dateTimeBetween('-3 months', 'now'),
            ]);
        }
        $this->command->info('Seeded 5 Orders.');

        // 4. Seed Appointments (5 rows)
        $statuses = ['pending', 'confirmed', 'completed', 'cancelled'];
        for ($i = 0; $i < 5; $i++) {
            Appointment::create([
                'user_id' => $customer->id,
                'title' => $faker->randomElement(['Consultation', 'System Setup', 'Maintenance', 'Audit', 'Review']),
                'description' => $faker->sentence(),
                'scheduled_at' => $faker->dateTimeBetween('-1 month', '+1 month'),
                'status' => $faker->randomElement($statuses),
                'created_at' => now(),
            ]);
        }
        $this->command->info('Seeded 5 Appointments.');

        // 5. Seed Services & Bookings (5 rows)
        if (Service::count() < 3) {
            for ($i = 0; $i < 3; $i++) {
                Service::create([
                    'name' => $faker->jobTitle . ' Service',
                    'description' => $faker->bs,
                    'price' => $faker->numberBetween(10000, 100000),
                    'duration' => $faker->numberBetween(30, 120),
                    'is_published' => true,
                ]);
            }
        }
        $services = Service::all();
        // If no services (due to checks), create one manually to rely on
        if($services->isEmpty()) {
             $srv = Service::create(['name'=>'Service A', 'price'=>5000, 'duration'=>60, 'is_published'=>true]);
             $services->push($srv);
        }

        for ($i = 0; $i < 5; $i++) {
            Booking::create([
                'user_id' => $customer->id,
                'service_id' => $services->random()->id,
                'title' => 'Service Booking ' . ($i+1),
                'description' => $faker->catchPhrase,
                'booking_date' => $faker->dateTimeBetween('now', '+2 months'),
                'status' => $faker->randomElement(['pending', 'confirmed', 'completed', 'cancelled']),
            ]);
        }
        $this->command->info('Seeded 5 Bookings.');


        // 6. Seed Support Tickets (5 rows)
        $supportCats = ['Technical', 'Billing', 'General', 'Feature Request'];
        foreach ($supportCats as $sc) {
            SupportCategory::firstOrCreate(['name' => $sc]);
        }
        $sCatIds = SupportCategory::pluck('id')->toArray();

        // Fix: Use 'in_progress' instead of 'pending' based on migration.
        for ($i = 0; $i < 5; $i++) {
            $ticket = Ticket::create([
                'ticket_no' => 'TCK-' . strtoupper($faker->bothify('????##')),
                'customer_id' => $customer->id, 
                'category_id' => $faker->randomElement($sCatIds),
                'subject' => $faker->sentence(4),
                'description' => $faker->paragraph(),
                'status' => $faker->randomElement(['open', 'in_progress', 'resolved', 'closed']),
                'priority' => $faker->randomElement(['low', 'medium', 'high']),
                'created_at' => $faker->dateTimeBetween('-1 month', 'now'),
            ]);
            
            DB::table('ticket_logs')->insert([
                'ticket_id' => $ticket->id,
                'action' => 'Ticket Created',
                'description' => 'System generated ticket.',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        $this->command->info('Seeded 5 Tickets.');

        // 7. Seed Notifications (5 rows)
        for ($i = 0; $i < 5; $i++) {
            DB::table('notifications')->insert([
                'id' => $faker->uuid,
                'type' => 'App\Notifications\SystemAlert',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $customer->id,
                'data' => json_encode([
                    'title' => 'System Update ' . ($i+1),
                    'message' => $faker->sentence(),
                    'icon' => 'fa-info-circle',
                    'action_url' => '#'
                ]),
                'read_at' => $i % 2 == 0 ? now() : null,
                'created_at' => $faker->dateTimeBetween('-1 week', 'now'),
                'updated_at' => now(),
            ]);
        }
        $this->command->info('Seeded 5 Notifications.');

        // 8. Seed Announcements (5 rows)
        for ($i = 0; $i < 5; $i++) {
            Announcement::create([
                'title' => 'Global Announcement ' . ($i+1),
                'message' => $faker->paragraph(),
                'is_active' => true,
                'target_role' => 'customer',
                'created_at' => now(),
            ]);
        }
        $this->command->info('Seeded 5 Announcements.');
    }
}
