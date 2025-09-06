<?php

namespace App\Http\Controllers;

use App\Models\Orcamento;
use App\Models\OrcamentoFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OrcamentoFileController extends Controller
{
    /**
     * Upload de arquivo para orçamento
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:10240', // 10MB max
            'orcamento_id' => 'required|integer|exists:orcamentos,id',
            'categoria' => 'required|in:anexo,avatar,logo',
            'descricao' => 'nullable|string|max:255'
        ]);

        // Verificar se o usuário tem acesso ao orçamento
        $orcamento = Orcamento::findOrFail($request->orcamento_id);
        if ($orcamento->cliente->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Acesso negado ao orçamento'
            ], 403);
        }

        $file = $request->file('file');
        $categoria = $request->categoria;
        $orcamentoId = $request->orcamento_id;

        // Gerar nome único para o arquivo
        $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $filePath = "orcamento-files/{$categoria}/{$fileName}";

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

            // Criar registro no banco de dados
            $orcamentoFile = OrcamentoFile::create([
                'orcamento_id' => $orcamentoId,
                'user_id' => Auth::id(),
                'nome_arquivo' => $file->getClientOriginalName(),
                'url_arquivo' => $fileUrl,
                'tipo_arquivo' => $file->getMimeType(),
                'tamanho' => $file->getSize(),
                'categoria' => $categoria,
                'descricao' => $request->descricao
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Arquivo enviado com sucesso',
                'file' => [
                    'id' => $orcamentoFile->id,
                    'filename' => $orcamentoFile->nome_arquivo,
                    'size' => $orcamentoFile->formatted_size,
                    'mime_type' => $orcamentoFile->tipo_arquivo,
                    'url' => $fileUrl,
                    'categoria' => $orcamentoFile->categoria,
                    'descricao' => $orcamentoFile->descricao,
                    'is_image' => $orcamentoFile->is_image,
                    'icon' => $orcamentoFile->icon,
                    'uploaded_at' => $orcamentoFile->created_at->format('d/m/Y H:i')
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