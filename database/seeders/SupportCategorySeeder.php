<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SupportCategory;

class SupportCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Technical Issue', 'description' => 'Problems with system or software'],
            ['name' => 'Billing', 'description' => 'Invoices, payments, refunds'],
            ['name' => 'Account', 'description' => 'Account creation, login issues'],
            ['name' => 'Other', 'description' => 'Miscellaneous requests'],
        ];

        foreach ($categories as $cat) {
            SupportCategory::create($cat);
        }
    }
}
