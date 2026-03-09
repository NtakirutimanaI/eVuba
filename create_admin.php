<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

$adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
$admin = User::firstOrCreate(['email' => 'admin@gmail.com'], ['name' => 'Admin User', 'password' => bcrypt('password')]);
$admin->assignRole($adminRole);
echo "Admin created successfully.\n";
