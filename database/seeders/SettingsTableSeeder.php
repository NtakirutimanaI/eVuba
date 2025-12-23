<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsTableSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            // General
            ['key' => 'app_name', 'value' => 'eVuba Connect', 'group' => 'general'],
            ['key' => 'app_desc', 'value' => 'Enterprise Resource Planning & Management System', 'group' => 'general'],
            ['key' => 'admin_email', 'value' => 'admin@evuba.com', 'group' => 'general'],
            ['key' => 'currency_symbol', 'value' => '$', 'group' => 'general'],
            
            // Business Info (for Invoices)
            ['key' => 'company_name', 'value' => 'eVuba Solutions Inc.', 'group' => 'business'],
            ['key' => 'company_address', 'value' => '123 Tech Park, Innovation Blvd, Silicon Valley, CA', 'group' => 'business'],
            ['key' => 'company_phone', 'value' => '+1 (555) 123-4567', 'group' => 'business'],
            ['key' => 'tax_id', 'value' => 'TAX-889977-US', 'group' => 'business'],
            
            // Modules
            ['key' => 'module_stock', 'value' => '1', 'group' => 'modules'],
            ['key' => 'module_invoices', 'value' => '1', 'group' => 'modules'],
            ['key' => 'module_support', 'value' => '1', 'group' => 'modules'],
            ['key' => 'module_hrm', 'value' => '1', 'group' => 'modules'],

            // Notifications
            ['key' => 'notify_new_order', 'value' => '1', 'group' => 'notifications'],
            ['key' => 'notify_low_stock', 'value' => '1', 'group' => 'notifications'],
            ['key' => 'notify_new_message', 'value' => '1', 'group' => 'notifications'],
            
            // Security
            ['key' => 'session_timeout', 'value' => '120', 'group' => 'security'],
            ['key' => 'strong_password', 'value' => '1', 'group' => 'security'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
