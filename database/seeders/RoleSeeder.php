<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $roles = ['admin', 'manager', 'employee', 'customer'];
        
        foreach ($roles as $roleName) {
            Role::firstOrCreate(
                ['name' => $roleName, 'guard_name' => 'web']
            );
        }

        // Create basic permissions
        $permissions = [
            // User management
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Customer management
            'view customers',
            'create customers',
            'edit customers',
            'delete customers',
            
            // Employee management
            'view employees',
            'create employees',
            'edit employees',
            'delete employees',
            
            // Product management
            'view products',
            'create products',
            'edit products',
            'delete products',
            
            // Order management
            'view orders',
            'create orders',
            'edit orders',
            'delete orders',
            
            // Appointment management
            'view appointments',
            'create appointments',
            'edit appointments',
            'delete appointments',
            
            // Booking management
            'view bookings',
            'create bookings',
            'edit bookings',
            'delete bookings',
            
            // Stock management
            'view stock',
            'create stock',
            'edit stock',
            'delete stock',
            
            // Support ticket management
            'view tickets',
            'create tickets',
            'edit tickets',
            'delete tickets',
            
            // Report access
            'view reports',
            'generate reports',
            
            // Settings
            'manage settings',
            'manage roles',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(
                ['name' => $permissionName, 'guard_name' => 'web']
            );
        }

        // Assign permissions to roles
        $admin = Role::findByName('admin');
        $admin->givePermissionTo(Permission::all()); // Admin gets all permissions

        $manager = Role::findByName('manager');
        $manager->givePermissionTo([
            'view users', 'create users', 'edit users',
            'view customers', 'create customers', 'edit customers',
            'view employees', 'edit employees',
            'view products', 'create products', 'edit products',
            'view orders', 'edit orders',
            'view appointments', 'create appointments', 'edit appointments',
            'view bookings', 'create bookings', 'edit bookings',
            'view stock', 'create stock', 'edit stock',
            'view tickets', 'edit tickets',
            'view reports', 'generate reports',
        ]);

        $employee = Role::findByName('employee');
        $employee->givePermissionTo([
            'view appointments',
            'view bookings',
            'view tickets', 'edit tickets',
            'view products',
            'view orders',
        ]);

        $customer = Role::findByName('customer');
        $customer->givePermissionTo([
            'view appointments', 'create appointments',
            'view bookings', 'create bookings',
            'view tickets', 'create tickets',
            'view products',
            'view orders', 'create orders',
        ]);

        $this->command->info('Roles and permissions created successfully!');
    }
}
