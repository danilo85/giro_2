<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class FileUploadHelper
{
    /**
     * Salva um arquivo sem usar o Symfony MimeTypes
     * 
     * @param UploadedFile $file
     * @param string $directory
     * @param string|null $filename
     * @return string|false
     */
    public static function storeFile(UploadedFile $file, string $directory, ?string $filename = null)
    {
        if (!$file->isValid()) {
            Log::error('Arquivo inválido para upload', ['file' => $file->getClientOriginalName()]);
            return false;
        }

        // Gerar nome do arquivo se não fornecido
        if (!$filename) {
            $filename = time() . '_' . $file->getClientOriginalName();
        }

        // Criar diretório se não existir
        $fullDirectory = storage_path('app/public/' . $directory);
        if (!file_exists($fullDirectory)) {
            mkdir($fullDirectory, 0755, true);
        }

        $fullPath = $fullDirectory . '/' . $filename;
        $relativePath = $directory . '/' . $filename;

        // Mover arquivo manualmente
        $success = false;
        
        Log::info('FileUploadHelper: Tentando salvar arquivo', [
            'filename' => $filename,
            'from' => $file->getRealPath(),
            'to' => $fullPath,
            'is_valid' => $file->isValid(),
            'is_uploaded_file' => is_uploaded_file($file->getRealPath()),
            'directory_exists' => file_exists($fullDirectory),
            'directory_writable' => is_writable($fullDirectory)
        ]);
        
        // Tentar move_uploaded_file primeiro (para uploads reais)
        if ($file->isValid() && is_uploaded_file($file->getRealPath())) {
            Log::info('FileUploadHelper: Usando move_uploaded_file');
            $success = move_uploaded_file($file->getRealPath(), $fullPath);
        } else {
            Log::info('FileUploadHelper: Usando copy');
            // Para testes ou arquivos que não são uploads, usar copy
            $success = copy($file->getRealPath(), $fullPath);
        }
        
        Log::info('FileUploadHelper: Resultado do salvamento', [
            'success' => $success,
            'file_exists_after' => file_exists($fullPath)
        ]);
        
        if ($success) {
            // Verificar se o arquivo ainda existe antes de tentar obter o tamanho
            $fileSize = 0;
            try {
                if ($file->isValid() && $file->getRealPath() && file_exists($file->getRealPath())) {
                    $fileSize = $file->getSize();
                } else {
                    // Se o arquivo temporário não existe mais, usar o tamanho do arquivo salvo
                    $fileSize = file_exists($fullPath) ? filesize($fullPath) : 0;
                }
            } catch (\Exception $e) {
                Log::warning('Erro ao obter tamanho do arquivo', ['error' => $e->getMessage()]);
                $fileSize = file_exists($fullPath) ? filesize($fullPath) : 0;
            }
            
            Log::info('Arquivo salvo com sucesso', [
                'filename' => $filename,
                'path' => $relativePath,
                'size' => $fileSize
            ]);
            return $relativePath;
        } else {
            Log::error('Erro ao mover arquivo', [
                'from' => $file->getRealPath(),
                'to' => $fullPath
            ]);
            return false;
        }
    }

    /**
     * Salva um arquivo com nome específico
     * 
     * @param UploadedFile $file
     * @param string $directory
     * @param string $filename
     * @return string|false
     */
    public static function storeAsFile(UploadedFile $file, string $directory, string $filename)
    {
        return self::storeFile($file, $directory, $filename);
    }
}