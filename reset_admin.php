<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$email = 'admin@gmail.com';
$password = 'password';

$user = User::where('email', $email)->first();

if ($user) {
    $user->password = Hash::make($password);
    $user->save();
    echo "Password for {$email} has been reset to: {$password}\n";
} else {
    echo "User {$email} not found. Creating it...\n";
    $user = User::create([
        'name' => 'Admin User',
        'email' => $email,
        'password' => Hash::make($password),
        'role' => 'admin',
    ]);
    // Assign role if Spatie is used
    try {
        $user->assignRole('admin');
    } catch (\Exception $e) {
        echo "Could not assign role: " . $e->getMessage() . "\n";
    }
    echo "User {$email} created with password: {$password}\n";
}
