<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MessageUsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\MessageUs::create([
            'first_name' => 'Sarah',
            'last_name'  => 'Johnson',
            'email'      => 'sarah.j@example.com',
            'message'    => 'I am interested in your premium consulting services. Could you please send me a brochure or price list? Thank you!',
            'read'       => false,
        ]);

        \App\Models\MessageUs::create([
            'first_name' => 'Michael',
            'last_name'  => 'Chen',
            'email'      => 'm.chen@techsolutions.com',
            'message'    => 'URGENT: Having trouble logging into my employee portal since this morning. It keeps throwing a 403 error. Please assist.',
            'read'       => false,
        ]);

        \App\Models\MessageUs::create([
            'first_name' => 'David',
            'last_name'  => 'Miller',
            'email'      => 'david@miller-group.org',
            'message'    => 'We are looking to migrate our current CRM to eVuba. Would it be possible to schedule a demo for our executive team next Tuesday?',
            'read'       => true,
        ]);
        
        \App\Models\MessageUs::create([
            'first_name' => 'Emma',
            'last_name'  => 'Wilson',
            'email'      => 'emma.wilson@creative-co.uk',
            'message'    => 'Just wanted to say that your support team was incredibly helpful with my last inquiry. Great job!',
            'read'       => true,
        ]);
    }
}
