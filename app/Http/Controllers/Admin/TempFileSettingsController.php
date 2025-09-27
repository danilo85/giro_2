<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TempFileSetting;
use App\Services\TempFileService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TempFileSettingsController extends Controller
{
    /**
     * Display the temporary file settings page
     */
    public function index(TempFileService $tempFileService): View
    {
        $settings = TempFileSetting::getSettings();
        $statistics = $tempFileService->getStatistics();
        
        return view('admin.temp-file-settings.index', compact('settings', 'statistics'));
    }
    
    /**
     * Update temporary file settings
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'default_expiry_days' => 'required|integer|min:1|max:365',
            'max_expiry_days' => 'required|integer|min:1|max:365',
            'auto_cleanup_enabled' => 'boolean',
            'cleanup_time' => 'required|date_format:H:i'
        ]);
        
        // Validate that max_expiry_days is greater than or equal to default_expiry_days
        if ($request->max_expiry_days < $request->default_expiry_days) {
            return back()->withErrors([
                'max_expiry_days' => 'O máximo de dias deve ser maior ou igual ao padrão.'
            ]);
        }
        
        TempFileSetting::updateSettings([
            'default_expiry_days' => $request->default_expiry_days,
            'max_expiry_days' => $request->max_expiry_days,
            'auto_cleanup_enabled' => $request->boolean('auto_cleanup_enabled'),
            'cleanup_time' => $request->cleanup_time
        ]);
        
        return redirect()->route('admin.temp-file-settings.index')
            ->with('success', 'Configurações atualizadas com sucesso!');
    }
    
    /**
     * Get temporary file statistics (AJAX)
     */
    public function getStatistics(TempFileService $tempFileService): JsonResponse
    {
        $statistics = $tempFileService->getStatistics();
        
        return response()->json([
            'success' => true,
            'statistics' => $statistics
        ]);
    }
    
    /**
     * Manually trigger cleanup of expired files
     */
    public function triggerCleanup(TempFileService $tempFileService): JsonResponse
    {
        try {
            $result = $tempFileService->cleanupExpiredFiles();
            
            return response()->json([
                'success' => true,
                'message' => "Limpeza concluída! {$result['deleted_count']} arquivos removidos.",
                'deleted_count' => $result['deleted_count'],
                'freed_space' => $result['freed_space']
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro durante a limpeza: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Send expiration warnings manually
     */
    public function sendWarnings(TempFileService $tempFileService): JsonResponse
    {
        try {
            $result = $tempFileService->sendExpirationWarnings();
            
            return response()->json([
                'success' => true,
                'message' => "Avisos enviados! {$result['notifications_sent']} notificações enviadas.",
                'notifications_sent' => $result['notifications_sent']
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao enviar avisos: ' . $e->getMessage()
            ], 500);
        }
    }
}
