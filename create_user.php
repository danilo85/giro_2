<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Create user@giro.com with correct password
$user = new User();
$user->name = 'Test User';
$user->email = 'user@giro.com';
$user->password = Hash::make('user123');
$user->is_active = 1;
$user->save();

echo "User user@giro.com created successfully with password 'user123'\n";

// Also update admin password to admin123
$admin = User::where('email', 'admin@giro.com')->first();
if ($admin) {
    $admin->password = Hash::make('admin123');
    $admin->save();
    echo "Admin password updated to 'admin123'\n";
} else {
    echo "Admin user not found\n";
}