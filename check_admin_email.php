<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Create a request instance
$request = Illuminate\Http\Request::capture();
$response = $kernel->handle($request);

// Get admin user
$admin = App\Models\User::where('is_admin', true)->first();

if ($admin) {
    echo "Admin ID: " . $admin->id . PHP_EOL;
    echo "Email: " . $admin->email . PHP_EOL;
    echo "Email Verified: " . ($admin->hasVerifiedEmail() ? 'Yes' : 'No') . PHP_EOL;
    echo "Email Verified At: " . $admin->email_verified_at . PHP_EOL;
    
    // Check email verification setting
    $emailVerificationRequired = App\Http\Controllers\SettingsController::get('system', 'email_verification', false);
    echo "Email Verification Required: " . ($emailVerificationRequired ? 'Yes' : 'No') . PHP_EOL;
} else {
    echo "No admin user found" . PHP_EOL;
}

$kernel->terminate($request, $response);