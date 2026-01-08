<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;

class QuickOrderSeeder extends Seeder
{
    public function run()
    {
        // TARGET ALL USERS to ensure the currently logged-in user gets an order
        $users = User::all();

        // Find or create a product
        $product = Product::first();
        if (!$product) {
            $product = Product::create([
                'name' => 'General Service Fee',
                'price' => 5000,
                'description' => 'Standard handling fee',
                'user_id' => $users->first()->id ?? 1
            ]);
        }

        foreach ($users as $user) {
            Order::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => 1,
                'price' => 5000,
                'status' => 'processing',
                'payment_method' => 'MTN Mobile Money',
                'payment_status' => 'approved',
                'transaction_ref' => 'REF-' . rand(1000, 9999) . '-TEST-' . $user->id
            ]);
        }

        $this->command->info("Created paid orders for " . $users->count() . " users.");
    }
}
