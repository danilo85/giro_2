<?php

use Illuminate\Support\Facades\Route;
use App\Utils\MimeTypeDetector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

// Rota para testar PNG sem finfo
Route::get('/test-png-upload', function () {
    $output = [];
    
    $output[] = "=== TESTE PNG SEM FINFO ===";
    $output[] = "";
    
    // Verificar se finfo está disponível
    if (class_exists('finfo')) {
        $output[] = "⚠️  Classe finfo está disponível (mas não deveria ser usada)";
    } else {
        $output[] = "✓ Classe finfo não está disponível (como esperado)";
    }
    
    if (function_exists('finfo_open')) {
        $output[] = "⚠️  Função finfo_open está disponível (mas não deveria ser usada)";
    } else {
        $output[] = "✓ Função finfo_open não está disponível (como esperado)";
    }
    
    $output[] = "";
    $output[] = "Simulando arquivo PNG 'ChatGPT Image 4 de abr. de 2025, 09_13_32.png'...";
    
    // Criar um arquivo PNG temporário para teste
    $tempPngFile = tempnam(sys_get_temp_dir(), 'test_png_');
    
    // Escrever assinatura PNG no arquivo temporário
    $pngSignature = hex2bin('89504e470d0a1a0a'); // Assinatura PNG
    $pngData = $pngSignature . str_repeat("\x00", 100); // Dados fictícios
    file_put_contents($tempPngFile, $pngData);
    
    $output[] = "Arquivo PNG temporário criado: {$tempPngFile}";
    $output[] = "";
    
    // Testar detecção por extensão
    $testFileName = 'ChatGPT Image 4 de abr. de 2025, 09_13_32.png';
    $extension = strtolower(pathinfo($testFileName, PATHINFO_EXTENSION));
    $output[] = "Extensão detectada: {$extension}";
    $output[] = "";
    
    // Testar nossa classe MimeTypeDetector
    $output[] = "Testando MimeTypeDetector::detect()...";
    try {
        $detectedMime = MimeTypeDetector::detect($tempPngFile);
        $output[] = "✓ MIME type detectado: {$detectedMime}";
        
        if ($detectedMime === 'image/png') {
            $output[] = "✅ SUCESSO: PNG detectado corretamente!";
        } else {
            $output[] = "❌ ERRO: Esperado 'image/png', obtido '{$detectedMime}'";
        }
    } catch (Exception $e) {
        $output[] = "❌ ERRO na detecção: " . $e->getMessage();
        $output[] = "Stack trace: " . $e->getTraceAsString();
    }
    
    $output[] = "";
    $output[] = "Testando métodos individuais...";
    
    // Testar detecção por assinatura
    $output[] = "- Testando detecção por assinatura...";
    $handle = fopen($tempPngFile, 'rb');
    $bytes = fread($handle, 16);
    fclose($handle);
    $hex = bin2hex($bytes);
    $output[] = "  Assinatura hex: {$hex}";
    if (strpos($hex, '89504e470d0a1a0a') === 0) {
        $output[] = "  ✓ Assinatura PNG detectada corretamente";
    } else {
        $output[] = "  ❌ Assinatura PNG não detectada";
    }
    
    // Testar mime_content_type se disponível
    $output[] = "- Testando mime_content_type...";
    if (function_exists('mime_content_type')) {
        try {
            $mimeFromFunction = mime_content_type($tempPngFile);
            $output[] = "  mime_content_type resultado: " . ($mimeFromFunction ?: 'null');
        } catch (Exception $e) {
            $output[] = "  mime_content_type falhou: " . $e->getMessage();
        }
    } else {
        $output[] = "  mime_content_type não está disponível";
    }
    
    // Limpeza
    unlink($tempPngFile);
    $output[] = "";
    $output[] = "Arquivo temporário removido.";
    $output[] = "";
    $output[] = "=== TESTE CONCLUÍDO ===";
    
    return '<pre>' . implode("\n", $output) . '</pre>';
});

// Rota para testar upload real
Route::post('/test-png-upload', function (Request $request) {
    $output = [];
    
    $output[] = "=== TESTE UPLOAD REAL PNG ===";
    $output[] = "";
    
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        
        $output[] = "Arquivo recebido: " . $file->getClientOriginalName();
        $output[] = "Tamanho: " . $file->getSize() . " bytes";
        $output[] = "Extensão: " . $file->getClientOriginalExtension();
        $output[] = "";
        
        try {
            $detectedMime = MimeTypeDetector::detect($file);
            $output[] = "✓ MIME type detectado: {$detectedMime}";
            
            if ($detectedMime === 'image/png') {
                $output[] = "✅ SUCESSO: PNG detectado corretamente!";
            } else {
                $output[] = "❌ ERRO: Esperado 'image/png', obtido '{$detectedMime}'";
            }
        } catch (Exception $e) {
            $output[] = "❌ ERRO na detecção: " . $e->getMessage();
        }
    } else {
        $output[] = "❌ Nenhum arquivo foi enviado";
    }
    
    $output[] = "";
    $output[] = "=== TESTE CONCLUÍDO ===";
    
    return '<pre>' . implode("\n", $output) . '</pre>';
});

// Formulário para teste
Route::get('/test-png-form', function () {
    return '
    <!DOCTYPE html>
    <html>
    <head>
        <title>Teste PNG Upload</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 40px; }
            .container { max-width: 600px; }
            .form-group { margin: 20px 0; }
            input[type="file"] { padding: 10px; }
            button { padding: 10px 20px; background: #007cba; color: white; border: none; cursor: pointer; }
            button:hover { background: #005a87; }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Teste PNG Upload (Sem finfo)</h1>
            <p>Use este formulário para testar o upload do arquivo PNG mencionado no erro.</p>
            
            <div class="form-group">
                <a href="/test-png-upload" target="_blank">
                    <button type="button">Executar Teste Automático</button>
                </a>
            </div>
            
            <form action="/test-png-upload" method="POST" enctype="multipart/form-data">
                ' . csrf_field() . '
                <div class="form-group">
                    <label>Selecione o arquivo PNG:</label><br>
                    <input type="file" name="file" accept=".png,image/png" required>
                </div>
                <div class="form-group">
                    <button type="submit">Testar Upload</button>
                </div>
            </form>
        </div>
    </body>
    </html>
    ';
});