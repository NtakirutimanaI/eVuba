<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Product;

class InvoiceDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Customers
        $customers = [
            [
                'name' => 'Alice Umutoniwase',
                'email' => 'alice.u@example.rw',
                'phone' => '+250 788 123 456',
                'address' => 'KG 123 St, Kimironko, Kigali, Rwanda',
            ],
            [
                'name' => 'Jean-Luc Habimana',
                'email' => 'jeanluc.h@example.rw',
                'phone' => '+250 783 654 321',
                'address' => 'KK 456 Ave, Kicukiro, Kigali, Rwanda',
            ],
            [
                'name' => 'Sarah Mukamanzi',
                'email' => 'sarah.m@example.rw',
                'phone' => '+250 785 987 654',
                'address' => 'KN 789 Blvd, Nyarugenge, Kigali, Rwanda',
            ],
            [
                'name' => 'Emmanuel Rukundo',
                'email' => 'emmy.r@example.rw',
                'phone' => '+250 722 111 222',
                'address' => 'Remera, Kigali, Rwanda',
            ],
        ];

        foreach ($customers as $customer) {
            Customer::updateOrCreate(['email' => $customer['email']], $customer);
        }

        // Seed Products
        $products = [
            [
                'product_code' => 'PC-001',
                'name' => 'Dell Latitude 5420',
                'unit_price' => 850000,
            ],
            [
                'product_code' => 'PC-002',
                'name' => 'MacBook Air M2',
                'unit_price' => 1250000,
            ],
            [
                'product_code' => 'ACC-001',
                'name' => 'Logitech Wireless Mouse',
                'unit_price' => 25000,
            ],
            [
                'product_code' => 'ACC-002',
                'name' => 'External SSD 1TB',
                'unit_price' => 120000,
            ],
            [
                'product_code' => 'SRV-001',
                'name' => 'Software Installation & Config',
                'unit_price' => 50000,
            ],
            [
                'product_code' => 'SRV-002',
                'name' => 'Network Maintenance (Monthly)',
                'unit_price' => 150000,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(['product_code' => $product['product_code']], $product);
        }
    }
}
