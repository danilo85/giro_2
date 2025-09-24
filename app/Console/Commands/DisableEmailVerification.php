<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Setting;

class DisableEmailVerification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:disable-verification {--mark-all-verified : Mark all existing users as email verified}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Disable email verification requirement and optionally mark all users as verified';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Disabling email verification requirement...');
        
        // Disable email verification in system settings
        Setting::set('email_verification', false);
        
        $this->info('✓ Email verification requirement has been disabled.');
        
        // Check if we should mark all users as verified
        if ($this->option('mark-all-verified')) {
            $this->info('Marking all users as email verified...');
            
            $unverifiedUsers = User::whereNull('email_verified_at')->get();
            $count = $unverifiedUsers->count();
            
            if ($count > 0) {
                foreach ($unverifiedUsers as $user) {
                    $user->forceFill([
                        'email_verified_at' => now(),
                    ])->save();
                }
                
                $this->info("✓ Marked {$count} users as email verified.");
            } else {
                $this->info('✓ All users are already email verified.');
            }
        }
        
        $this->info('');
        $this->info('Email verification has been successfully disabled!');
        $this->info('Users can now access the system without email verification.');
        
        return Command::SUCCESS;
    }
}