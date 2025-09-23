<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    protected $signature = 'user:create-admin';
    protected $description = 'Create admin user with default credentials';

    public function handle()
    {
        // Create or update admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@giro.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );

        // Create or update regular user
        $user = User::updateOrCreate(
            ['email' => 'user@giro.com'],
            [
                'name' => 'User',
                'password' => Hash::make('user123'),
                'email_verified_at' => now(),
            ]
        );

        $this->info('Admin user created successfully!');
        $this->info('Email: admin@giro.com');
        $this->info('Password: admin123');
        $this->info('');
        $this->info('Regular user created successfully!');
        $this->info('Email: user@giro.com');
        $this->info('Password: user123');

        return 0;
    }
}