<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Utils\MimeTypeDetector;
use Illuminate\Support\Facades\Log;

Route::post('/debug/upload', function (Request $request) {
    Log::info('=== DEBUG UPLOAD INICIADO ===');
    
    try {
        if (!$request->hasFile('file')) {
            return response()->json(['error' => 'Nenhum arquivo enviado'], 400);
        }
        
        $file = $request->file('file');
        Log::info('Arquivo recebido: ' . $file->getClientOriginalName());
        Log::info('Tamanho do arquivo: ' . $file->getSize() . ' bytes');
        Log::info('Extensão original: ' . $file->getClientOriginalExtension());
        
        // Testar cada método individualmente
        Log::info('=== TESTANDO MÉTODOS DE DETECÇÃO ===');
        
        // Método 1: Laravel getMimeType()
        try {
            $laravelMime = $file->getMimeType();
            Log::info('Laravel getMimeType(): ' . ($laravelMime ?: 'NULL'));
        } catch (\Exception $e) {
            Log::error('Laravel getMimeType() falhou: ' . $e->getMessage());
        }
        
        // Método 2: finfo
        try {
            if (function_exists('finfo_open')) {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                if ($finfo) {
                    $finfoMime = finfo_file($finfo, $file->getRealPath());
                    Log::info('finfo_file(): ' . ($finfoMime ?: 'NULL'));
                    finfo_close($finfo);
                } else {
                    Log::warning('finfo_open() retornou false');
                }
            } else {
                Log::warning('Função finfo_open() não existe');
            }
        } catch (\Exception $e) {
            Log::error('finfo falhou: ' . $e->getMessage());
        }
        
        // Método 3: mime_content_type
        try {
            if (function_exists('mime_content_type')) {
                $mimeContentType = mime_content_type($file->getRealPath());
                Log::info('mime_content_type(): ' . ($mimeContentType ?: 'NULL'));
            } else {
                Log::warning('Função mime_content_type() não existe');
            }
        } catch (\Exception $e) {
            Log::error('mime_content_type() falhou: ' . $e->getMessage());
        }
        
        // Método 4: Extensão
        $extension = strtolower($file->getClientOriginalExtension());
        Log::info('Detecção por extensão (.{$extension}): application/pdf');
        
        // Método 5: Assinatura do arquivo
        try {
            $handle = fopen($file->getRealPath(), 'rb');
            if ($handle) {
                $bytes = fread($handle, 8);
                fclose($handle);
                $hex = bin2hex($bytes);
                Log::info('Primeiros 8 bytes (hex): ' . $hex);
                
                if (strpos($bytes, '\x25\x50\x44\x46') === 0) {
                    Log::info('Assinatura PDF detectada!');
                }
            }
        } catch (\Exception $e) {
            Log::error('Leitura de assinatura falhou: ' . $e->getMessage());
        }
        
        // Testar nossa classe MimeTypeDetector
        Log::info('=== TESTANDO MimeTypeDetector ===');
        try {
            $detectedMime = MimeTypeDetector::detect($file);
            Log::info('MimeTypeDetector::detect(): ' . $detectedMime);
        } catch (\Exception $e) {
            Log::error('MimeTypeDetector::detect() falhou: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
        }
        
        // Verificar informações do PHP
        Log::info('=== INFORMAÇÕES DO PHP ===');
        Log::info('PHP Version: ' . phpversion());
        Log::info('fileinfo extension loaded: ' . (extension_loaded('fileinfo') ? 'SIM' : 'NÃO'));
        Log::info('finfo_open function exists: ' . (function_exists('finfo_open') ? 'SIM' : 'NÃO'));
        Log::info('mime_content_type function exists: ' . (function_exists('mime_content_type') ? 'SIM' : 'NÃO'));
        
        Log::info('=== DEBUG UPLOAD FINALIZADO ===');
        
        return response()->json([
            'success' => true,
            'message' => 'Debug completo. Verifique os logs.',
            'file_info' => [
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'extension' => $file->getClientOriginalExtension()
            ]
        ]);
        
    } catch (\Exception $e) {
        Log::error('ERRO GERAL NO DEBUG: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return response()->json([
            'error' => 'Erro durante debug: ' . $e->getMessage()
        ], 500);
    }
});

Route::get('/debug/upload-form', function () {
    return '
    <!DOCTYPE html>
    <html>
    <head>
        <title>Debug Upload</title>
        <meta charset="utf-8">
    </head>
    <body>
        <h1>Debug Upload de Arquivos</h1>
        <form action="/debug/upload" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="' . csrf_token() . '">
            <p>
                <label>Selecione um arquivo:</label><br>
                <input type="file" name="file" required>
            </p>
            <p>
                <button type="submit">Upload e Debug</button>
            </p>
        </form>
        
        <h2>Instruções:</h2>
        <ol>
            <li>Selecione o arquivo PDF que está causando problema</li>
            <li>Clique em "Upload e Debug"</li>
            <li>Verifique os logs em storage/logs/laravel.log</li>
        </ol>
    </body>
    </html>
    ';
});