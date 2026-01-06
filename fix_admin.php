<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Fix admin account
$admin = \App\Models\User::where('email', 'admin@gmail.com')->first();

if ($admin) {
    $admin->role = 'admin';
    $admin->save();
    echo "✓ Fixed admin@gmail.com role to 'admin'" . PHP_EOL;

    // Assign admin role via Spatie if exists
    try {
        if (class_exists('\Spatie\Permission\Models\Role')) {
            $adminRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);
            $admin->syncRoles(['admin']);
            echo "✓ Assigned Spatie admin role" . PHP_EOL;
        }
    } catch (\Exception $e) {
        // Skip if Spatie not fully configured
    }
} else {
    echo "✗ Admin user not found" . PHP_EOL;
}

echo PHP_EOL . "Now try logging in with: admin@gmail.com / password" . PHP_EOL;
