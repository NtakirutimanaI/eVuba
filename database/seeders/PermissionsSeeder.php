<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        $pages = [
            'users', 'employees', 'customers', 'services', 'tasks',
            'inventory', 'schedules', 'reports', 'settings', 
            'appointments', 'bookings', 'orders', 'support'
        ];

        $actions = [
            'view', 'edit', 'delete', 'update', 'change_status', 
            'approve', 'reject', 'download', 'activate'
        ];

        foreach ($pages as $page) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name' => $page.'.'.$action,
                    'guard_name' => 'web'
                ]);
            }
        }
    }
}
