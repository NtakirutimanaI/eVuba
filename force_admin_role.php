<?php

use App\Models\User;

$user = User::where('email', 'admin@gmail.com')->first();
$user->role = 'admin';
$user->save();
echo "Admin role set to: " . $user->role . "\n";
