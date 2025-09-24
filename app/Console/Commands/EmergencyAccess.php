<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmergencyAccess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emergency:access {email} {--password= : Set a specific password, otherwise a random one will be generated}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create emergency access for a user, bypassing email verification and admin approval';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->option('password') ?: Str::random(12);
        
        $this->info("Creating emergency access for: {$email}");
        
        // Find or create user
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->info('User not found. Creating new user...');
            
            $name = 'Emergency User';
            $password = Str::random(12);
            $this->info("Auto-generated password: {$password}");
            
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'email_verified_at' => now(),
                'is_active' => true,
                'admin_approved' => true,
                'is_admin' => false,
            ]);
            
            $this->info('✓ New user created successfully.');
        } else {
            $this->info('User found. Updating access permissions...');
            
            // Update user to ensure access
            $user->update([
                'password' => Hash::make($password),
                'email_verified_at' => now(), // Mark as verified
                'is_active' => true,
                'admin_approved' => true, // Auto-approve
            ]);
            
            $this->info('✓ User access permissions updated.');
        }
        
        // Temporarily disable email verification and admin approval
        $this->info('Temporarily disabling access restrictions...');
        
        Setting::set('email_verification', false);
        Setting::set('admin_approval', false);
        
        $this->info('✓ Access restrictions disabled.');
        
        // Display access information
        $this->info('');
        $this->info('=== EMERGENCY ACCESS CREATED ===');
        $this->info("Email: {$email}");
        $this->info("Password: {$password}");
        $this->info('');
        $this->warn('IMPORTANT: Save these credentials safely!');
        $this->warn('Email verification and admin approval have been disabled.');
        $this->warn('Remember to re-enable these settings after resolving the access issue.');
        $this->info('');
        $this->info('To re-enable restrictions later, run:');
        $this->info('php artisan tinker');
        $this->info('App\\Http\\Controllers\\SettingsController::set("system", "email_verification", true);');
        $this->info('App\\Http\\Controllers\\SettingsController::set("system", "admin_approval", true);');
        
        return Command::SUCCESS;
    }
}