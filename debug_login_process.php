<?php

require_once __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DEBUG LOGIN PROCESS ===\n";

// Test request simulation
$request = new \Illuminate\Http\Request([
    'nidn' => '1234567890',
    'password' => 'password',
    '_token' => 'test-token'
]);

echo "Request data: nidn={$request->input('nidn')}\n";

// Test validation
$loginRequest = new \App\Http\Requests\Auth\LoginRequest();
$loginRequest->merge($request->all());

echo "Validation rules passed\n";

// Test authentication
try {
    $loginRequest->authenticate();
    echo "✅ Authentication SUCCESS!\n";
} catch (\Exception $e) {
    echo "❌ Authentication FAILED: " . $e->getMessage() . "\n";
}

echo "Session driver: " . config('session.driver') . "\n";
echo "Session lifetime: " . config('session.lifetime') . " minutes\n";
