<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePagePermissionSeeder extends Seeder
{
    public function run()
    {
        // Clear existing role-page-permissions
        DB::table('role_page_permission')->truncate();

        // Define roles and pages they should access
        $rolePages = [
            'admin' => [1,2,3,4,5,6,7,8,9,10],        // Dashboard, Users, Employees, etc.
            'manager' => [1,6,7,9,11],               // Dashboard, Tasks, Inventory, Reports, Team
            'employee' => [1,6,12],                  // Dashboard, Tasks, Appointments
            'customer' => [1,13,12,14,15],           // Dashboard, Bookings, Appointments, Orders, Support
        ];

        // Map role names to IDs
        $roles = DB::table('roles')->pluck('id', 'name');

        // Default permission_id (can be 1 for generic access)
        $defaultPermissionId = 1;

        foreach ($rolePages as $roleName => $pageIds) {
            $roleId = $roles[$roleName] ?? null;
            if (!$roleId) continue;

            foreach ($pageIds as $pageId) {
                DB::table('role_page_permission')->insert([
                    'role_id' => $roleId,
                    'page_id' => $pageId,
                    'permission_id' => $defaultPermissionId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('Role-page permissions seeded successfully!');
    }
}
