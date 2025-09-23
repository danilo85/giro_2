<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class TestLogin extends Command
{
    protected $signature = 'user:test-login {email} {password}';
    protected $description = 'Test user login credentials';

    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error('User not found!');
            return 1;
        }

        $this->info('User found: ' . $user->email);
        $this->info('User name: ' . $user->name);
        
        if (Hash::check($password, $user->password)) {
            $this->info('✅ Password is VALID!');
            
            // Test actual authentication
            if (Auth::attempt(['email' => $email, 'password' => $password])) {
                $this->info('✅ Authentication successful!');
                Auth::logout();
            } else {
                $this->error('❌ Authentication failed!');
            }
        } else {
            $this->error('❌ Password is INVALID!');
        }

        return 0;
    }
}