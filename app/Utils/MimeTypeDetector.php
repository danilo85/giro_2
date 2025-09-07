<?php

namespace App\Utils;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class MimeTypeDetector
{
    /**
     * Mapa de extensões para MIME types
     */
    private static $mimeTypes = [
        // Documentos
        'pdf' => 'application/pdf',
        'doc' => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'xls' => 'application/vnd.ms-excel',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'ppt' => 'application/vnd.ms-powerpoint',
        'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'txt' => 'text/plain',
        'rtf' => 'application/rtf',
        
        // Imagens
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'bmp' => 'image/bmp',
        'webp' => 'image/webp',
        'svg' => 'image/svg+xml',
        'ico' => 'image/x-icon',
        
        // Áudio
        'mp3' => 'audio/mpeg',
        'wav' => 'audio/wav',
        'ogg' => 'audio/ogg',
        
        // Vídeo
        'mp4' => 'video/mp4',
        'avi' => 'video/x-msvideo',
        'mov' => 'video/quicktime',
        
        // Arquivos compactados
        'zip' => 'application/zip',
        'rar' => 'application/x-rar-compressed',
        '7z' => 'application/x-7z-compressed',
        
        // Outros
        'json' => 'application/json',
        'xml' => 'application/xml',
        'csv' => 'text/csv',
    ];

    /**
     * Detecta o MIME type de um arquivo usando múltiplos métodos
     */
    public static function detect($file): string
    {
        $methods = [
            'detectFromLaravel',
            'detectFromMimeContentType',
            'detectFromExtension',
            'detectFromFileSignature'
        ];

        Log::info('=== INICIANDO DETECÇÃO DE MIME TYPE ===', [
            'file_name' => $file instanceof UploadedFile ? $file->getClientOriginalName() : (is_string($file) ? basename($file) : 'unknown'),
            'file_size' => $file instanceof UploadedFile ? $file->getSize() : (is_string($file) && file_exists($file) ? filesize($file) : 0),
            'file_extension' => $file instanceof UploadedFile ? $file->getClientOriginalExtension() : (is_string($file) ? pathinfo($file, PATHINFO_EXTENSION) : ''),
            'file_path' => $file instanceof UploadedFile ? $file->getPathname() : $file,
            'methods_available' => count($methods)
        ]);
        
        // Tentar cada método de detecção
        foreach ($methods as $method) {
            Log::info("Tentando método: {$method}");
            try {
                $mimeType = self::$method($file);
                Log::info("Resultado do método {$method}", [
                    'result' => $mimeType,
                    'is_valid' => $mimeType && $mimeType !== 'application/octet-stream'
                ]);
                
                if ($mimeType && $mimeType !== 'application/octet-stream') {
                    Log::info("✅ MIME type detectado com sucesso usando método: {$method}", [
                        'mime_type' => $mimeType,
                        'file' => $file instanceof UploadedFile ? $file->getClientOriginalName() : (is_string($file) ? basename($file) : 'unknown')
                    ]);
                    return $mimeType;
                }
            } catch (\Exception $e) {
                Log::warning("❌ Método {$method} falhou", [
                    'error' => $e->getMessage(),
                    'file' => $file instanceof UploadedFile ? $file->getClientOriginalName() : (is_string($file) ? basename($file) : 'unknown'),
                    'trace' => $e->getTraceAsString()
                ]);
                continue;
            }
        }

        // Fallback final
        $fallback = 'application/octet-stream';
        Log::warning("Todos os métodos de detecção falharam, usando fallback: {$fallback}");
        return $fallback;
    }

    /**
     * Método 1: Usar getMimeType() do Laravel (com verificação de segurança)
     */
    private static function detectFromLaravel($file): ?string
    {
        if ($file instanceof UploadedFile) {
            try {
                // Verificar se o método getMimeType() funciona sem finfo
                $mimeType = $file->getMimeType();
                return $mimeType;
            } catch (\Exception $e) {
                // Se falhar (provavelmente por falta de finfo), retornar null
                Log::warning('Laravel getMimeType() falhou: ' . $e->getMessage());
                return null;
            }
        }
        return null;
    }

    /**
     * Método 2: Usar finfo diretamente (REMOVIDO - não disponível)
     * Este método foi removido pois a extensão php_fileinfo não está disponível
     */

    /**
     * Método 2: Usar mime_content_type
     */
    private static function detectFromMimeContentType($file): ?string
    {
        if (!function_exists('mime_content_type')) {
            Log::info('Função mime_content_type não está disponível');
            return null;
        }

        $filePath = null;
        if ($file instanceof UploadedFile) {
            $filePath = $file->getRealPath();
        } elseif (is_string($file) && file_exists($file)) {
            $filePath = $file;
        }

        if ($filePath && file_exists($filePath)) {
            try {
                $mimeType = mime_content_type($filePath);
                Log::info('mime_content_type detectou: ' . ($mimeType ?: 'null'));
                return $mimeType ?: null;
            } catch (\Exception $e) {
                Log::warning('mime_content_type falhou: ' . $e->getMessage());
                return null;
            }
        }

        return null;
    }

    /**
     * Método 3: Detectar pela extensão do arquivo
     */
    private static function detectFromExtension($file): ?string
    {
        $extension = null;
        $fileName = null;
        
        if ($file instanceof UploadedFile) {
            $extension = strtolower($file->getClientOriginalExtension());
            $fileName = $file->getClientOriginalName();
        } elseif (is_string($file)) {
            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            $fileName = basename($file);
        }

        Log::info('Detectando por extensão', [
            'file_name' => $fileName,
            'extension' => $extension,
            'mime_type_found' => $extension ? (self::$mimeTypes[$extension] ?? 'não encontrado') : 'extensão vazia'
        ]);

        return $extension ? (self::$mimeTypes[$extension] ?? null) : null;
    }

    /**
     * Método 4: Detectar pela assinatura do arquivo (magic bytes)
     */
    private static function detectFromFileSignature($file): ?string
    {
        $filePath = null;
        if ($file instanceof UploadedFile) {
            $filePath = $file->getRealPath();
        } elseif (is_string($file) && file_exists($file)) {
            $filePath = $file;
        }

        if (!$filePath || !file_exists($filePath)) {
            return null;
        }

        $handle = fopen($filePath, 'rb');
        if (!$handle) {
            return null;
        }

        $bytes = fread($handle, 16); // Ler mais bytes para melhor detecção
        fclose($handle);

        if (strlen($bytes) < 4) {
            return null;
        }

        // Converter para hexadecimal para comparação mais fácil
        $hex = bin2hex($bytes);
        
        // Verificar assinaturas conhecidas (em hexadecimal)
        $signatures = [
            '25504446' => 'application/pdf', // PDF (%PDF)
            'ffd8ff' => 'image/jpeg', // JPEG
            '89504e470d0a1a0a' => 'image/png', // PNG
            '474946383761' => 'image/gif', // GIF87a
            '474946383961' => 'image/gif', // GIF89a
            '504b0304' => 'application/zip', // ZIP/DOCX/XLSX
            '504b0506' => 'application/zip', // ZIP (empty)
            '504b0708' => 'application/zip', // ZIP (spanned)
            'd0cf11e0a1b11ae1' => 'application/msword', // DOC/XLS (OLE2)
            '424d' => 'image/bmp', // BMP
            '52494646' => 'audio/wav', // WAV (RIFF)
        ];

        foreach ($signatures as $signature => $mimeType) {
            if (strpos($hex, strtolower($signature)) === 0) {
                return $mimeType;
            }
        }

        return null;
    }

    /**
     * Verifica se um MIME type é de imagem
     */
    public static function isImage(string $mimeType): bool
    {
        return strpos($mimeType, 'image/') === 0;
    }

    /**
     * Verifica se um MIME type é de documento
     */
    public static function isDocument(string $mimeType): bool
    {
        $documentTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/plain',
        ];

        return in_array($mimeType, $documentTypes);
    }

    /**
     * Obtém uma descrição amigável do tipo de arquivo
     */
    public static function getTypeDescription(string $mimeType): string
    {
        $descriptions = [
            'application/pdf' => 'Documento PDF',
            'application/msword' => 'Documento Word',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'Documento Word',
            'application/vnd.ms-excel' => 'Planilha Excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'Planilha Excel',
            'image/jpeg' => 'Imagem JPEG',
            'image/png' => 'Imagem PNG',
            'image/gif' => 'Imagem GIF',
            'text/plain' => 'Arquivo de Texto',
        ];

        return $descriptions[$mimeType] ?? 'Arquivo';
    }
}