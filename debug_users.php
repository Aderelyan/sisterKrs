<?php

require_once __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

echo "=== DEBUG USERS ===\n";
$users = User::all();
echo "Total users: " . $users->count() . "\n";

foreach ($users as $user) {
    echo "ID: {$user->id}, NIDN: {$user->nidn}, Name: {$user->name}\n";
}

echo "\n=== TESTING AUTH ===\n";
if ($users->count() > 0) {
    $testUser = $users->first();
    echo "First user NIDN: {$testUser->nidn}\n";
    echo "Password check: " . (\Illuminate\Support\Facades\Hash::check('password', $testUser->password) ? 'MATCH' : 'NO MATCH') . "\n";
}
