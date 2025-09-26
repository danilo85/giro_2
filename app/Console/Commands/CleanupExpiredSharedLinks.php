<?php

namespace App\Console\Commands;

use App\Models\SharedLink;
use App\Models\ActivityLog;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CleanupExpiredSharedLinks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'files:cleanup-expired-links {--dry-run : Show what would be cleaned up without actually doing it}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup expired shared links and log the activity';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting cleanup of expired shared links...');
        
        // Find expired shared links that are still active
        $expiredLinks = SharedLink::where('is_active', true)
            ->where('expires_at', '<', Carbon::now())
            ->with(['file', 'file.user'])
            ->get();
            
        if ($expiredLinks->isEmpty()) {
            $this->info('No expired shared links found.');
            return 0;
        }
        
        $this->info("Found {$expiredLinks->count()} expired shared links.");
        
        if ($this->option('dry-run')) {
            $this->warn('DRY RUN MODE - No changes will be made.');
            $this->table(
                ['ID', 'File', 'Owner', 'Expired At', 'Downloads'],
                $expiredLinks->map(function ($link) {
                    return [
                        $link->id,
                        $link->file->original_name,
                        $link->file->user->name,
                        $link->expires_at->format('Y-m-d H:i:s'),
                        $link->download_count
                    ];
                })->toArray()
            );
            return 0;
        }
        
        $cleanedCount = 0;
        
        foreach ($expiredLinks as $link) {
            try {
                // Deactivate the expired link
                $link->update(['is_active' => false]);
                
                // Log the cleanup activity
                ActivityLog::create([
                    'file_id' => $link->file_id,
                    'user_id' => null, // System action
                    'action' => 'shared_link_expired',
                    'details' => [
                        'shared_link_id' => $link->id,
                        'expired_at' => $link->expires_at->toISOString(),
                        'download_count' => $link->download_count,
                        'cleanup_at' => Carbon::now()->toISOString(),
                        'cleanup_reason' => 'Automatic cleanup of expired shared link'
                    ]
                ]);
                
                $cleanedCount++;
                $this->line("✓ Cleaned up expired link for: {$link->file->original_name}");
                
            } catch (\Exception $e) {
                $this->error("✗ Failed to cleanup link ID {$link->id}: {$e->getMessage()}");
            }
        }
        
        $this->info("\nCleanup completed! Deactivated {$cleanedCount} expired shared links.");
        
        // Also cleanup old access logs (older than 90 days)
        $this->info('\nCleaning up old access logs...');
        
        $oldLogsCount = \App\Models\AccessLog::where('accessed_at', '<', Carbon::now()->subDays(90))->count();
        
        if ($oldLogsCount > 0) {
            if (!$this->option('dry-run')) {
                \App\Models\AccessLog::where('accessed_at', '<', Carbon::now()->subDays(90))->delete();
                $this->info("Deleted {$oldLogsCount} old access logs (older than 90 days).");
            } else {
                $this->info("Would delete {$oldLogsCount} old access logs (older than 90 days).");
            }
        } else {
            $this->info('No old access logs to cleanup.');
        }
        
        return 0;
    }
}
