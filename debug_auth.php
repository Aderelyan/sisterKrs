<?php

require_once __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

echo "=== TESTING MANUAL AUTH ===\n";

// Test dengan user pertama
$user = User::find(1);
echo "Testing with: NIDN={$user->nidn}, Name={$user->name}\n";

$credentials = [
    'nidn' => $user->nidn,
    'password' => 'password' // ganti dengan password yang benar
];

echo "Credentials: " . json_encode($credentials) . "\n";

// Test Auth::attempt
if (Auth::attempt($credentials)) {
    echo "✅ Auth::attempt SUCCESS!\n";
    echo "Authenticated user ID: " . Auth::id() . "\n";
    Auth::logout();
} else {
    echo "❌ Auth::attempt FAILED!\n";
}

// Test password verification
echo "Password verification: " . (Hash::check('password', $user->password) ? 'MATCH' : 'NO MATCH') . "\n";
echo "Stored password hash: " . substr($user->password, 0, 20) . "...\n";
