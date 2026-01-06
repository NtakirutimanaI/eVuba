<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== USER CREDENTIALS CHECK ===" . PHP_EOL . PHP_EOL;

$users = \App\Models\User::select('id', 'name', 'email', 'role')->limit(10)->get();

foreach ($users as $u) {
    echo sprintf("%-10s | %-30s | %s", $u->role, $u->email, $u->name) . PHP_EOL;
}

echo PHP_EOL . "Try logging in with:" . PHP_EOL;
echo "  admin@gmail.com / password" . PHP_EOL;
