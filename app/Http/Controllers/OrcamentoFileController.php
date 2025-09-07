<?php

namespace App\Http\Controllers;

use App\Models\Orcamento;
use App\Models\OrcamentoFile;
use App\Utils\MimeTypeDetector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OrcamentoFileController extends Controller
{
    /**
     * Upload de arquivo para orçamento
     */
    public function upload(Request $request, Orcamento $orcamento)
    {
        \Log::info('=== INÍCIO DO UPLOAD ===', [
            'orcamento_id' => $orcamento->id,
            'request_files' => $request->hasFile('file') ? 'SIM' : 'NÃO'
        ]);
        
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
        ]);

        $file = $request->file('file');
        
        \Log::info('Arquivo recebido', [
            'original_name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'extension' => $file->getClientOriginalExtension(),
            'path' => $file->getPathname()
        ]);
        
        // Detectar MIME type usando nossa classe personalizada
        try {
            \Log::info('Iniciando detecção de MIME type...');
            $mimeType = MimeTypeDetector::detect($file);
            \Log::info('MIME type detectado com sucesso', ['mime_type' => $mimeType]);
        } catch (\Exception $e) {
            \Log::error('ERRO na detecção de MIME type', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
        
        // Validar tipo de arquivo
        $allowedTypes = [
            'application/pdf',
            'image/jpeg',
            'image/png',
            'image/gif',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/plain',
            'text/csv'
        ];
        
        \Log::info('Validando tipo de arquivo', [
            'detected_mime' => $mimeType,
            'is_allowed' => in_array($mimeType, $allowedTypes)
        ]);
        
        if (!in_array($mimeType, $allowedTypes)) {
            \Log::warning('Tipo de arquivo não permitido', [
                'detected_mime' => $mimeType,
                'allowed_types' => $allowedTypes
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Tipo de arquivo não permitido. MIME type detectado: ' . $mimeType
            ], 422);
        }

        // Gerar nome único para o arquivo
        $fileName = time() . '_' . $file->getClientOriginalName();
        \Log::info('Nome do arquivo gerado', ['file_name' => $fileName]);
        
        // Armazenar o arquivo
        try {
            $path = $file->storeAs('orcamentos', $fileName, 'public');
            \Log::info('Arquivo armazenado com sucesso', ['path' => $path]);
        } catch (\Exception $e) {
            \Log::error('ERRO ao armazenar arquivo', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }

        // Registrar no banco de dados
        try {
            $orcamentoFile = new OrcamentoFile([
                'orcamento_id' => $orcamento->id,
                'user_id' => Auth::id(),
                'nome_arquivo' => $fileName,
                'url_arquivo' => Storage::url($path),
                'tipo_arquivo' => $mimeType,
                'tamanho' => $file->getSize(),
                'categoria' => $request->input('categoria', 'anexo'),
            ]);
            
            $orcamentoFile->save();
            \Log::info('Arquivo registrado no banco de dados', ['file_id' => $orcamentoFile->id]);
        } catch (\Exception $e) {
            \Log::error('ERRO ao registrar no banco de dados', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }

        \Log::info('=== UPLOAD CONCLUÍDO COM SUCESSO ===');
        
        return response()->json([
            'success' => true,
            'message' => 'Arquivo enviado com sucesso!',
            'file' => $orcamentoFile
        ]);
    }
    
    /**
     * Obter arquivos de um orçamento
     */
    public function getFiles($orcamentoId, Request $request)
    {
        // Verificar se o usuário tem acesso ao orçamento
        $orcamento = Orcamento::findOrFail($orcamentoId);
        if ($orcamento->cliente->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Acesso negado ao orçamento'
            ], 403);
        }

        $query = OrcamentoFile::where('orcamento_id', $orcamentoId);
        
        // Filtrar por categoria se especificada
        if ($request->has('categoria')) {
            $query->where('categoria', $request->categoria);
        }
        
        $files = $query->orderBy('created_at', 'desc')->get();
        
        $filesData = $files->map(function ($file) {
            return [
                'id' => $file->id,
                'name' => $file->nome_arquivo,
                'size' => $file->formatted_size,
                'type' => $file->tipo_arquivo,
                'url' => $file->url,
                'categoria' => $file->categoria,
                'descricao' => $file->descricao,
                'is_image' => $file->is_image,
                'icon' => $file->icon,
                'created_at' => $file->created_at->format('d/m/Y H:i')
            ];
        });
        
        return response()->json([
            'success' => true,
            'files' => $filesData
        ]);
    }
    
    /**
     * Excluir arquivo
     */
    public function delete($fileId)
    {
        try {
            $file = OrcamentoFile::findOrFail($fileId);
            
            // Verificar se o usuário tem acesso ao arquivo
            if ($file->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Acesso negado ao arquivo'
                ], 403);
            }
            
            // Excluir arquivo do storage
            $disk = config('filesystems.default');
            if (str_contains($file->url_arquivo, 's3.amazonaws.com') || str_contains($file->url_arquivo, 'amazonaws.com')) {
                $path = parse_url($file->url_arquivo, PHP_URL_PATH);
                if ($path) {
                    Storage::disk('s3')->delete(ltrim($path, '/'));
                }
            } else {
                $path = str_replace('/storage/', '', $file->url_arquivo);
                Storage::disk('public')->delete($path);
            }
            
            // Excluir registro do banco
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
        $file = OrcamentoFile::findOrFail($fileId);
        
        // Verificar se o usuário tem acesso ao arquivo
        if ($file->user_id !== Auth::id()) {
            abort(403, 'Acesso negado ao arquivo');
        }
        
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
            abort(500, 'Erro ao fazer download do arquivo: ' . $e->getMessage());
        }
    }

    /**
     * Atualizar descrição do arquivo
     */
    public function updateDescription(Request $request, $fileId)
    {
        $request->validate([
            'descricao' => 'nullable|string|max:255'
        ]);

        $file = OrcamentoFile::findOrFail($fileId);
        
        // Verificar se o usuário tem acesso ao arquivo
        if ($file->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Acesso negado ao arquivo'
            ], 403);
        }

        $file->update([
            'descricao' => $request->descricao
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Descrição atualizada com sucesso',
            'file' => [
                'id' => $file->id,
                'descricao' => $file->descricao
            ]
        ]);
    }
    

}