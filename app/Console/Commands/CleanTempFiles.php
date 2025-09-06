<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TempFile;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CleanTempFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'temp-files:clean {--hours=24 : Hours after which temp files should be deleted}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean temporary files older than specified hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hours = $this->option('hours');
        $cutoffTime = Carbon::now()->subHours($hours);
        
        $this->info("Cleaning temporary files older than {$hours} hours...");
        
        // Buscar arquivos temporários antigos
        $oldTempFiles = TempFile::where('created_at', '<', $cutoffTime)
            ->get();
        
        $deletedCount = 0;
        $errorCount = 0;
        
        foreach ($oldTempFiles as $tempFile) {
            try {
                // Deletar arquivo do storage
                if (Storage::disk('public')->exists($tempFile->url_arquivo)) {
                    Storage::disk('public')->delete($tempFile->url_arquivo);
                }
                
                // Deletar registro do banco
                $tempFile->delete();
                $deletedCount++;
                
                $this->line("Deleted: {$tempFile->nome_arquivo}");
                
            } catch (\Exception $e) {
                $errorCount++;
                $this->error("Error deleting {$tempFile->nome_arquivo}: {$e->getMessage()}");
            }
        }
        
        $this->info("Cleanup completed!");
        $this->info("Files deleted: {$deletedCount}");
        
        if ($errorCount > 0) {
            $this->warn("Errors encountered: {$errorCount}");
        }
        
        return 0;
    }
}
