<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$email = 'admin@gmail.com';
echo "Searching for user with email: $email\n";

$user = User::withTrashed()->where('email', $email)->first();

if ($user) {
    echo "User found. ID: " . $user->id . "\n";
    if ($user->trashed()) { 
        $user->restore();
        echo "User was soft-deleted. Restored successfully.\n";
    } else {
        echo "User was NOT soft-deleted.\n";
    }

    $user->password = Hash::make('password');
    $user->save();
    echo "Password reset to 'password'.\n";
} else {
    echo "User not found!\n";
}
