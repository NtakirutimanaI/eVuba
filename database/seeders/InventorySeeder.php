<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Customer;
use App\Models\StockIn;
use App\Models\StockOut;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🔄 Starting inventory seeding...');

        // Get existing products, suppliers, and customers
        $products = Product::all();
        $suppliers = Supplier::all();
        $customers = Customer::all();

        if ($products->isEmpty()) {
            $this->command->error('❌ No products found! Please create products first.');
            return;
        }

        if ($suppliers->isEmpty()) {
            $this->command->warn('⚠️  No suppliers found! Creating default supplier...');
            $suppliers = collect([
                Supplier::firstOrCreate(
                    ['email' => 'default@supplier.com'],
                    ['name' => 'Default Supplier']
                )
            ]);
        }

        if ($customers->isEmpty()) {
            $this->command->warn('⚠️  No customers found! Creating default customers...');
            $customers = collect([
                Customer::firstOrCreate(
                    ['email' => 'john@example.com'],
                    ['name' => 'John Doe', 'phone' => '+250788111222', 'address' => 'Kigali']
                ),
                Customer::firstOrCreate(
                    ['email' => 'jane@example.com'],
                    ['name' => 'Jane Smith', 'phone' => '+250788222333', 'address' => 'Kigali']
                ),
            ]);
        }

        $userId = 1; // Admin user

        $this->command->info('📦 Adding stock-in records...');

        foreach ($products as $product) {
            // Add initial stock-in
            $quantity = rand(20, 100);
            $unitCost = rand(5000, 500000);
            
            StockIn::create([
                'product_id' => $product->id,
                'supplier_id' => $suppliers->random()->id,
                'quantity' => $quantity,
                'unit_cost' => $unitCost,
                'total_cost' => $quantity * $unitCost,
                'type' => 'purchase',
                'stock_in_date' => Carbon::now()->subDays(rand(5, 15)),
                'user_id' => $userId,
                'note' => 'Initial inventory stock',
            ]);

            $this->command->info("  ✓ Added stock for: {$product->name}");

            // Add some sales transactions
            $salesCount = rand(2, 5);
            for ($i = 0; $i < $salesCount; $i++) {
                $saleQty = rand(1, 5);
                $salePrice = $unitCost * 1.5; // 50% markup
                $daysAgo = rand(1, 7);
                
                StockOut::create([
                    'product_id' => $product->id,
                    'customer_id' => $customers->random()->id,
                    'quantity' => $saleQty,
                    'unit_price' => $salePrice,
                    'total_price' => $saleQty * $salePrice,
                    'type' => 'sale',
                    'stock_out_date' => Carbon::now()->subDays($daysAgo),
                    'user_id' => $userId,
                    'note' => 'Customer purchase',
                ]);
            }

            // Add 1-2 sales for today
            $todaySales = rand(1, 2);
            for ($i = 0; $i < $todaySales; $i++) {
                $saleQty = rand(1, 3);
                $salePrice = $unitCost * 1.5;
                
                StockOut::create([
                    'product_id' => $product->id,
                    'customer_id' => $customers->random()->id,
                    'quantity' => $saleQty,
                    'unit_price' => $salePrice,
                    'total_price' => $saleQty * $salePrice,
                    'type' => 'sale',
                    'stock_out_date' => Carbon::today(),
                    'user_id' => $userId,
                    'note' => "Today's sale",
                ]);
            }
        }

        $this->command->info('✅ Inventory seeded successfully!');
        $this->command->info('📦 Stock-in records added for all products');
        $this->command->info('🛒 Sample sales transactions created');
        $this->command->info('📅 Today\'s transactions included');
    }
}
