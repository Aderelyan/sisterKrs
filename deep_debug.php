<?php

require_once __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DEEP DEBUG LOGIN ===\n";

// 1. Test AuthenticatedSessionController
$controller = new \App\Http\Controllers\Auth\AuthenticatedSessionController();

echo "✅ Controller instantiated\n";

// 2. Test LoginRequest validation
$request = new \Illuminate\Http\Request([
    'nidn' => '1234567890',
    'password' => 'password',
    '_token' => 'test-token'
]);

$loginRequest = new \App\Http\Requests\Auth\LoginRequest();
$loginRequest->merge($request->all());

echo "✅ LoginRequest created\n";

// 3. Test validation
try {
    $loginRequest->validate();
    echo "✅ Validation passed\n";
} catch (\Exception $e) {
    echo "❌ Validation failed: " . $e->getMessage() . "\n";
}

// 4. Test authentication
try {
    $loginRequest->authenticate();
    echo "✅ Authentication passed\n";
} catch (\Exception $e) {
    echo "❌ Authentication failed: " . $e->getMessage() . "\n";
}

// 5. Test session
echo "Session driver: " . config('session.driver') . "\n";
echo "Session path: " . config('session.path') . "\n";
echo "Session domain: " . config('session.domain') . "\n";

// 6. Test manual Auth
if (\Illuminate\Support\Facades\Auth::attempt(['nidn' => '1234567890', 'password' => 'password'])) {
    echo "✅ Manual Auth SUCCESS\n";
    echo "User ID: " . \Illuminate\Support\Facades\Auth::id() . "\n";
    \Illuminate\Support\Facades\Auth::logout();
} else {
    echo "❌ Manual Auth FAILED\n";
}
