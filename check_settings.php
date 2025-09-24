<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\SettingsController;

echo "=== CLEARING ALL CACHE ===\n";
Cache::flush();
echo "All cache cleared!\n\n";

echo "=== TESTING Setting::get DIRECTLY ===\n";
$dbValue = Setting::get('system.admin_approval');
echo "Setting::get('system.admin_approval'): " . var_export($dbValue, true) . "\n";
echo "Type: " . gettype($dbValue) . "\n\n";

echo "=== TESTING SettingsController::get ===\n";
$result = SettingsController::get('system', 'admin_approval', false);
echo "SettingsController::get('system', 'admin_approval', false): " . var_export($result, true) . "\n";
echo "Type: " . gettype($result) . "\n\n";

echo "=== CHECKING CACHE AFTER SettingsController::get ===\n";
$cacheData = Cache::get('app_settings');
if ($cacheData) {
    echo "Cache exists!\n";
    echo "Cache system.admin_approval: " . var_export($cacheData['system']['admin_approval'] ?? 'NOT_FOUND', true) . "\n";
} else {
    echo "No cache found\n";
}

echo "\n=== MANUALLY CALLING getSettings ===\n";
// Use reflection to call private method
$controller = new SettingsController();
$reflection = new ReflectionClass($controller);
$method = $reflection->getMethod('getSettings');
$method->setAccessible(true);
$settings = $method->invoke($controller);

echo "Manual getSettings admin_approval: " . var_export($settings['system']['admin_approval'] ?? 'NOT_FOUND', true) . "\n";

echo "\n=== TESTING USER CREATION NOW ===\n";
$adminApprovalRequired = SettingsController::get('system', 'admin_approval', false);
echo "Admin approval required: " . ($adminApprovalRequired ? 'YES' : 'NO') . "\n";

// Create a test user
$testEmail = 'test_user_final_' . time() . '@test.com';
echo "Creating test user: {$testEmail}\n";

$user = App\Models\User::create([
    'name' => 'Test User Final',
    'email' => $testEmail,
    'password' => Hash::make('password'),
    'is_active' => true,
    'is_online' => false,
    'admin_approved' => false, // Always start as not approved
]);

// If admin approval is not required, auto-approve the user
if (!$adminApprovalRequired) {
    $user->update(['admin_approved' => true]);
    echo "User auto-approved because admin approval is not required\n";
} else {
    echo "User created with admin_approved=false because admin approval is required\n";
}

echo "User created with ID: {$user->id}\n";
echo "User admin_approved status: " . ($user->admin_approved ? 'YES' : 'NO') . "\n";

// Test canLogin method
echo "\nTesting canLogin method...\n";
echo "User canLogin: " . ($user->canLogin() ? 'YES' : 'NO') . "\n";

?>