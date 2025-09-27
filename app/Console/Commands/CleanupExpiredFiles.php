<?php

namespace App\Console\Commands;

use App\Services\TempFileService;
use Illuminate\Console\Command;

class CleanupExpiredFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'files:cleanup-expired 
                            {--dry-run : Show what would be deleted without actually deleting}
                            {--send-warnings : Send expiration warnings before cleanup}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired temporary files and send expiration warnings';

    /**
     * Execute the console command.
     */
    public function handle(TempFileService $tempFileService)
    {
        $this->info('Starting expired files cleanup...');
        
        // Send warnings if requested
        if ($this->option('send-warnings')) {
            $this->info('Sending expiration warnings...');
            $tempFileService->sendExpirationWarnings();
            $this->info('Expiration warnings sent.');
        }
        
        // Get statistics before cleanup
        $stats = $tempFileService->getStatistics();
        $this->info("Found {$stats['expired_files']} expired files.");
        
        if ($stats['expired_files'] === 0) {
            $this->info('No expired files to clean up.');
            return 0;
        }
        
        // Show what would be deleted in dry-run mode
        if ($this->option('dry-run')) {
            $expiredFiles = $tempFileService->getExpiredFiles();
            
            $this->info('Files that would be deleted (dry-run mode):');
            $this->table(
                ['ID', 'Name', 'Size', 'Expired At', 'Owner'],
                $expiredFiles->map(function ($file) {
                    return [
                        $file->id,
                        $file->original_name,
                        $this->formatBytes($file->file_size),
                        $file->expires_at->format('Y-m-d H:i:s'),
                        $file->user->name ?? 'Unknown'
                    ];
                })->toArray()
            );
            
            $totalSize = $expiredFiles->sum('file_size');
            $this->info("Total size to be freed: {$this->formatBytes($totalSize)}");
            
            return 0;
        }
        
        // Confirm deletion in interactive mode
        if (!$this->option('no-interaction')) {
            if (!$this->confirm("Are you sure you want to delete {$stats['expired_files']} expired files?")) {
                $this->info('Cleanup cancelled.');
                return 0;
            }
        }
        
        // Perform cleanup
        $this->info('Cleaning up expired files...');
        $result = $tempFileService->cleanupExpiredFiles();
        
        // Display results
        $this->info("Cleanup completed successfully!");
        $this->info("Files deleted: {$result['deleted_count']}");
        
        if (!empty($result['errors'])) {
            $this->warn("Errors encountered: " . count($result['errors']));
            foreach ($result['errors'] as $error) {
                $this->error("File ID {$error['file_id']}: {$error['error']}");
            }
        }
        
        // Show final statistics
        $finalStats = $tempFileService->getStatistics();
        $this->info("Remaining temporary files: {$finalStats['total_temporary_files']}");
        $this->info("Files expiring in 24h: {$finalStats['files_expiring_24h']}");
        
        return 0;
    }
    
    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
