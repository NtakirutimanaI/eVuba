<?php

use App\Models\User;

$user = User::where('email', 'admin@gmail.com')->first();
echo "Admin role: " . $user->role . "\n";
