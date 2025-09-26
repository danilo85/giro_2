<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Test the ConditionalEmailVerification middleware directly
use App\Http\Middleware\ConditionalEmailVerification;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\SettingsController;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

echo "Testing ConditionalEmailVerification middleware..." . PHP_EOL;
echo "---" . PHP_EOL;

// Temporarily enable email verification for testing
$originalEmailVerificationSetting = SettingsController::get('system', 'email_verification', false);
Setting::set('system.email_verification', true);
// Clear cache to ensure new setting is used
Cache::forget('app_settings');
echo "Email verification temporarily enabled for testing." . PHP_EOL;

// Create a test request that expects JSON
$request = Request::create('/test', 'POST');
$request->headers->set('X-Requested-With', 'XMLHttpRequest');
$request->headers->set('Accept', 'application/json');

echo "Request expects JSON: " . ($request->expectsJson() ? 'Yes' : 'No') . PHP_EOL;

// Find admin user and force email as unverified
$admin = User::where('is_admin', true)->first();

if (!$admin) {
    echo "Creating admin user..." . PHP_EOL;
    $admin = User::create([
        'name' => 'Admin Test',
        'email' => 'admin@test.com',
        'password' => bcrypt('password'),
        'is_admin' => true,
        'is_active' => true,
        'email_verified_at' => null // Not verified
    ]);
} else {
    // Store original email verification status
    $originalEmailVerified = $admin->email_verified_at;
    echo "Original email_verified_at: " . ($originalEmailVerified ? $originalEmailVerified : 'null') . PHP_EOL;
    
    // Force admin email as not verified for this test
    $admin->email_verified_at = null;
    $admin->save();
    
    // Verify the update worked
    $admin->refresh(); // Reload from database
    echo "After setting to null: " . ($admin->email_verified_at ? $admin->email_verified_at : 'null') . PHP_EOL;
    
    // Double check with fresh query
    $freshAdmin = User::find($admin->id);
    echo "Fresh query result: " . ($freshAdmin->email_verified_at ? $freshAdmin->email_verified_at : 'null') . PHP_EOL;
}

echo "Admin user:" . PHP_EOL;
echo "- ID: " . $admin->id . PHP_EOL;
echo "- Email: " . $admin->email . PHP_EOL;
echo "- Is Admin: " . ($admin->is_admin ? 'Yes' : 'No') . PHP_EOL;
echo "- Email Verified (raw): " . ($admin->email_verified_at ? 'Yes (' . $admin->email_verified_at . ')' : 'No (null)') . PHP_EOL;
echo "- Email Verified (method): " . ($admin->hasVerifiedEmail() ? 'Yes' : 'No') . PHP_EOL;
echo "- Should Verify Email: " . ($admin->shouldVerifyEmail() ? 'Yes' : 'No') . PHP_EOL;

// Check system setting for email verification
$emailVerificationEnabled = SettingsController::get('system', 'email_verification', false);
echo "- Email Verification System Setting: " . ($emailVerificationEnabled ? 'Enabled' : 'Disabled') . PHP_EOL;
echo "---" . PHP_EOL;

// Set the authenticated user
$request->setUserResolver(function () use ($admin) {
    return $admin;
});

// Test the middleware
$middleware = new ConditionalEmailVerification();

try {
    $response = $middleware->handle($request, function ($req) {
        return response()->json(['success' => true, 'message' => 'Request passed through middleware']);
    });
    
    echo "Response Status: " . $response->getStatusCode() . PHP_EOL;
    echo "Response Headers: " . PHP_EOL;
    foreach ($response->headers->all() as $key => $values) {
        echo "  {$key}: " . implode(', ', $values) . PHP_EOL;
    }
    echo "Response Content: " . $response->getContent() . PHP_EOL;
    
    // Check if it's valid JSON
    $content = $response->getContent();
    $json = json_decode($content, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        echo "✓ Response is valid JSON" . PHP_EOL;
        if (isset($json['error']) && $json['error'] === 'email_not_verified') {
            echo "✓ Correct error type returned for unverified email" . PHP_EOL;
            echo "✓ Middleware fix is working correctly!" . PHP_EOL;
        } elseif (isset($json['success']) && $json['success'] === true) {
            echo "✓ Request passed through (email verification not required or user verified)" . PHP_EOL;
        }
    } else {
        echo "✗ Response is not valid JSON" . PHP_EOL;
        echo "JSON Error: " . json_last_error_msg() . PHP_EOL;
    }
    
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . PHP_EOL;
    echo "File: " . $e->getFile() . ":" . $e->getLine() . PHP_EOL;
}

echo "---" . PHP_EOL;
echo "Test completed." . PHP_EOL;

// Restore original settings
if (isset($originalEmailVerified)) {
    $admin->email_verified_at = $originalEmailVerified;
    $admin->save();
    echo "Admin email verification status restored." . PHP_EOL;
}

// Restore original email verification setting
Setting::set('system.email_verification', $originalEmailVerificationSetting);
Cache::forget('app_settings');
echo "Email verification setting restored to: " . ($originalEmailVerificationSetting ? 'Enabled' : 'Disabled') . PHP_EOL;