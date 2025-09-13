<?php

namespace App\Http\Controllers\Financial;

use App\Http\Controllers\Controller;
use App\Models\TransactionFile;
use App\Models\TempFile;
use App\Utils\MimeTypeDetector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FileUploadController extends Controller
{
    /**
     * Upload file to S3 and create database record
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
            'transaction_id' => 'required|integer',
            'description' => 'nullable|string|max:255'
        ]);

        $file = $request->file('file');
        $transactionId = $request->transaction_id;

        // Gerar nome único para o arquivo
        $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $filePath = 'transaction-files/' . $fileName;

        // Escolher disco de armazenamento
        $disk = config('filesystems.default') === 's3' ? 's3' : 'public';
        
        try {
            // Upload do arquivo
            if ($disk === 's3') {
                Storage::disk('s3')->put($filePath, file_get_contents($file));
                $fileUrl = Storage::disk('s3')->url($filePath);
            } else {
                Storage::disk('public')->put($filePath, file_get_contents($file));
                $fileUrl = Storage::disk('public')->url($filePath);
            }

            // Detectar MIME type usando classe utilitária robusta
            $mimeType = MimeTypeDetector::detect($file);
            
            // Obter tamanho do arquivo de forma segura
            $fileSize = 0;
            try {
                if ($file->isValid() && $file->getRealPath() && file_exists($file->getRealPath())) {
                    $fileSize = $file->getSize();
                }
            } catch (\Exception $e) {
                Log::warning('Erro ao obter tamanho do arquivo financeiro', ['error' => $e->getMessage()]);
            }
            
            // Log para debug
            Log::info('Upload de arquivo financeiro', [
                'nome' => $file->getClientOriginalName(),
                'mime_type_detectado' => $mimeType,
                'tamanho' => $fileSize,
                'transaction_id' => $transactionId
            ]);
            
            // Criar registro no banco de dados
            $transactionFile = TransactionFile::create([
                'transaction_id' => $transactionId,
                'nome_arquivo' => $file->getClientOriginalName(),
                'url_arquivo' => $fileUrl,
                'tipo_arquivo' => $mimeType,
                'tamanho' => $fileSize
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Arquivo enviado com sucesso',
                'file' => [
                    'id' => $transactionFile->id,
                    'filename' => $transactionFile->nome_arquivo,
                    'size' => $this->formatFileSize($transactionFile->tamanho),
                    'mime_type' => $transactionFile->tipo_arquivo,
                    'url' => $fileUrl,
                    'uploaded_at' => $transactionFile->created_at->format('d/m/Y H:i')
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Obter arquivos de uma transação
     */
    public function getFiles($transactionId)
    {
        $files = TransactionFile::where('transaction_id', $transactionId)->get();
        
        $filesData = $files->map(function ($file) {
            return [
                'id' => $file->id,
                'name' => $file->nome_arquivo,
                'size' => $file->tamanho_formatado,
                'type' => $file->tipo_arquivo,
                'url' => $file->url_arquivo,
                'is_image' => $file->isImagem(),
                'icon' => $file->isPdf() ? 'pdf' : ($file->isImagem() ? 'image' : 'file'),
                'created_at' => $file->created_at->format('d/m/Y H:i')
            ];
        });
        
        return response()->json([
            'success' => true,
            'files' => $filesData
        ]);
    }
    
    /**
     * Delete file
     */
    public function delete($fileId)
    {
        try {
            $file = TransactionFile::findOrFail($fileId);
            
            // Delete from storage
            $disk = config('filesystems.default');
            if (Storage::disk($disk)->exists($file->file_path)) {
                Storage::disk($disk)->delete($file->file_path);
            }
            
            // Delete from database
            $file->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Arquivo excluído com sucesso'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir arquivo: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Download de arquivo
     */
    public function download($fileId)
    {
        $file = TransactionFile::findOrFail($fileId);
        
        try {
            // Se for URL do S3, redirecionar diretamente
            if (str_contains($file->url_arquivo, 's3.amazonaws.com') || str_contains($file->url_arquivo, 'amazonaws.com')) {
                return redirect($file->url_arquivo);
            } else {
                // Para arquivos locais, fazer download
                $path = str_replace('/storage/', '', $file->url_arquivo);
                return Storage::disk('public')->download($path, $file->nome_arquivo);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao fazer download do arquivo: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Upload temporário de arquivo (para create de transações)
     */
    public function uploadTemp(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
            'temp_id' => 'required|string',
            'description' => 'nullable|string|max:255'
        ]);

        $file = $request->file('file');
        $tempId = $request->temp_id;

        // Gerar nome único para o arquivo
        $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $filePath = 'temp-files/' . $fileName;

        // Escolher disco de armazenamento
        $disk = config('filesystems.default') === 's3' ? 's3' : 'public';
        
        try {
            // Upload do arquivo
            if ($disk === 's3') {
                Storage::disk('s3')->put($filePath, file_get_contents($file));
                $fileUrl = Storage::disk('s3')->url($filePath);
            } else {
                Storage::disk('public')->put($filePath, file_get_contents($file));
                $fileUrl = Storage::disk('public')->url($filePath);
            }

            // Detectar MIME type usando classe utilitária robusta
            $mimeType = MimeTypeDetector::detect($file);
            
            // Obter tamanho do arquivo de forma segura
            $fileSizeTemp = 0;
            try {
                if ($file->isValid() && $file->getRealPath() && file_exists($file->getRealPath())) {
                    $fileSizeTemp = $file->getSize();
                }
            } catch (\Exception $e) {
                Log::warning('Erro ao obter tamanho do arquivo temporário', ['error' => $e->getMessage()]);
            }
            
            // Log para debug
            Log::info('Upload temporário de arquivo', [
                'nome' => $file->getClientOriginalName(),
                'mime_type_detectado' => $mimeType,
                'tamanho' => $fileSizeTemp,
                'temp_id' => $tempId
            ]);
            
            // Criar registro temporário no banco de dados
            $tempFile = TempFile::create([
                'temp_id' => $tempId,
                'nome_arquivo' => $file->getClientOriginalName(),
                'url_arquivo' => $fileUrl,
                'tipo_arquivo' => $mimeType,
                'tamanho' => $fileSizeTemp,
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Arquivo enviado temporariamente com sucesso',
                'file' => [
                    'id' => $tempFile->id,
                    'filename' => $tempFile->nome_arquivo,
                    'size' => $this->formatFileSize($tempFile->tamanho),
                    'mime_type' => $tempFile->tipo_arquivo,
                    'url' => $fileUrl,
                    'uploaded_at' => $tempFile->created_at->format('d/m/Y H:i')
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obter arquivos temporários
     */
    public function getTempFiles($tempId)
    {
        $files = TempFile::where('temp_id', $tempId)
                        ->where('user_id', Auth::id())
                        ->get();
        
        $filesData = $files->map(function ($file) {
            return [
                'id' => $file->id,
                'name' => $file->nome_arquivo,
                'size' => $file->tamanho_formatado,
                'type' => $file->tipo_arquivo,
                'url' => $file->url_arquivo,
                'is_image' => $file->isImagem(),
                'icon' => $file->isPdf() ? 'pdf' : ($file->isImagem() ? 'image' : 'file'),
                'created_at' => $file->created_at->format('d/m/Y H:i')
            ];
        });
        
        return response()->json([
            'success' => true,
            'files' => $filesData
        ]);
    }

    /**
     * Mover arquivos temporários para transação definitiva
     */
    public function moveTempFilesToTransaction(Request $request)
    {
        $request->validate([
            'temp_id' => 'required|string',
            'transaction_id' => 'required|integer'
        ]);

        $tempFiles = TempFile::where('temp_id', $request->temp_id)
                            ->where('user_id', Auth::id())
                            ->get();

        $movedFiles = [];
        foreach ($tempFiles as $tempFile) {
            $transactionFile = $tempFile->moveToTransaction($request->transaction_id);
            $movedFiles[] = $transactionFile;
        }

        return response()->json([
            'success' => true,
            'message' => 'Arquivos movidos com sucesso',
            'files_count' => count($movedFiles)
        ]);
    }

    /**
     * Delete arquivo temporário
     */
    public function deleteTempFile($fileId)
    {
        try {
            $file = TempFile::where('id', $fileId)
                           ->where('user_id', Auth::id())
                           ->firstOrFail();
            
            $file->deletarArquivo();
            
            return response()->json([
                'success' => true,
                'message' => 'Arquivo excluído com sucesso'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir arquivo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Format file size in human readable format
     */
    private function formatFileSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }


}