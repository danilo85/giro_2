<?php

namespace App\Http\Controllers;

use App\Models\HistoricoEntry;
use App\Models\HistoricoFile;
use App\Models\Orcamento;
use App\Utils\MimeTypeDetector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HistoricoController extends Controller
{
    /**
     * Exibir timeline do histórico do projeto
     */
    public function index(Orcamento $orcamento)
    {
        // Verificar se o orçamento pertence ao usuário
        if ($orcamento->cliente->user_id !== Auth::id()) {
            abort(403);
        }

        $entries = HistoricoEntry::with(['user', 'files'])
            ->forOrcamento($orcamento->id)
            ->orderByEntryDate('desc')
            ->paginate(20);

        return view('historico.index', compact('orcamento', 'entries'));
    }

    /**
     * Exibir formulário para criar nova entrada
     */
    public function create(Orcamento $orcamento)
    {
        // Verificar se o orçamento pertence ao usuário
        if ($orcamento->cliente->user_id !== Auth::id()) {
            abort(403);
        }

        return view('historico.create', compact('orcamento'));
    }

    /**
     * Armazenar nova entrada no histórico
     */
    public function store(Request $request, Orcamento $orcamento)
    {
        // Verificar se o orçamento pertence ao usuário
        if ($orcamento->cliente->user_id !== Auth::id()) {
            abort(403);
        }

        $maxFileSize = config('upload.max_file_size', 20480); // Default 20MB
        
        $validated = $request->validate([
            'type' => 'required|string|in:manual,system,status_change,payment,project_start',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'entry_date' => 'required|date',
            'files.*' => "file|max:{$maxFileSize}" // Dynamic max file size from config
        ]);

        $entry = HistoricoEntry::create([
            'orcamento_id' => $orcamento->id,
            'user_id' => Auth::id(),
            'type' => $validated['type'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'entry_date' => $validated['entry_date'],
            'metadata' => [
                'created_via' => 'manual',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]
        ]);

        // Processar arquivos anexados
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $this->storeFile($entry, $file);
            }
        }

        return redirect()
            ->route('orcamentos.historico.index', $orcamento)
            ->with('success', 'Entrada adicionada ao histórico com sucesso!');
    }

    /**
     * Upload de arquivos via AJAX
     */
    public function upload(Request $request, Orcamento $orcamento)
    {
        // Verificar se o orçamento pertence ao usuário
        if ($orcamento->cliente->user_id !== Auth::id()) {
            abort(403);
        }

        $maxFileSize = config('upload.max_file_size', 20480); // Default 20MB
        
        $request->validate([
            'file' => "required|file|max:{$maxFileSize}",
            'entry_id' => 'required|exists:historico_entries,id'
        ]);

        $entry = HistoricoEntry::findOrFail($request->entry_id);
        
        if ($entry->orcamento_id !== $orcamento->id) {
            abort(403);
        }

        $file = $this->storeFile($entry, $request->file('file'));

        return response()->json([
            'success' => true,
            'file' => [
                'id' => $file->id,
                'name' => $file->original_name,
                'size' => $file->formatted_size,
                'download_url' => $file->download_url
            ]
        ]);
    }

    /**
     * Download de arquivo
     */
    public function download(Orcamento $orcamento, HistoricoFile $file)
    {
        // Verificar se o orçamento pertence ao usuário
        if ($orcamento->cliente->user_id !== Auth::id()) {
            abort(403);
        }

        if ($file->historicoEntry->orcamento_id !== $orcamento->id) {
            abort(403);
        }

        if (!$file->exists()) {
            abort(404, 'Arquivo não encontrado.');
        }

        return Storage::download($file->file_path, $file->original_name);
    }

    /**
     * Armazenar arquivo no storage
     */
    private function storeFile(HistoricoEntry $entry, $file)
    {
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $fileName = Str::uuid() . '.' . $extension;
        $filePath = 'historico/' . $entry->orcamento_id . '/' . $fileName;

        // Armazenar arquivo
        Storage::putFileAs(
            'historico/' . $entry->orcamento_id,
            $file,
            $fileName
        );

        // Obter tamanho do arquivo de forma segura
        $fileSize = 0;
        try {
            if ($file->isValid() && $file->getRealPath() && file_exists($file->getRealPath())) {
                $fileSize = $file->getSize();
            } else {
                // Tentar obter o tamanho do arquivo já salvo
                $savedFilePath = storage_path('app/' . $filePath);
                if (file_exists($savedFilePath)) {
                    $fileSize = filesize($savedFilePath);
                }
            }
        } catch (\Exception $e) {
            \Log::warning('Erro ao obter tamanho do arquivo no histórico', ['error' => $e->getMessage()]);
            // Tentar obter o tamanho do arquivo já salvo
            $savedFilePath = storage_path('app/' . $filePath);
            if (file_exists($savedFilePath)) {
                $fileSize = filesize($savedFilePath);
            }
        }

        // Criar registro no banco
        return HistoricoFile::create([
            'historico_entry_id' => $entry->id,
            'original_name' => $originalName,
            'file_path' => $filePath,
            'mime_type' => MimeTypeDetector::detect($file),
            'file_size' => $fileSize
        ]);
    }

    /**
     * Atualizar entrada do histórico
     */
    public function update(Request $request, Orcamento $orcamento, HistoricoEntry $historico)
    {
        // Verificar se o orçamento pertence ao usuário
        if ($orcamento->cliente->user_id !== Auth::id()) {
            abort(403);
        }

        // Verificar se a entrada pertence ao orçamento
        if ($historico->orcamento_id !== $orcamento->id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string'
        ]);

        $historico->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Entrada atualizada com sucesso!',
                'data' => [
                    'id' => $historico->id,
                    'title' => $historico->title,
                    'description' => $historico->description
                ]
            ]);
        }

        return redirect()
            ->route('orcamentos.historico.index', $orcamento)
            ->with('success', 'Entrada atualizada com sucesso!');
    }

    /**
     * Excluir entrada do histórico
     */
    public function destroy(Request $request, Orcamento $orcamento, HistoricoEntry $historico)
    {
        // Verificar se o orçamento pertence ao usuário
        if ($orcamento->cliente->user_id !== Auth::id()) {
            abort(403);
        }

        // Verificar se a entrada pertence ao orçamento
        if ($historico->orcamento_id !== $orcamento->id) {
            abort(403);
        }

        // Excluir arquivos associados
        foreach ($historico->files as $file) {
            if (Storage::exists($file->file_path)) {
                Storage::delete($file->file_path);
            }
            $file->delete();
        }

        $historico->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Entrada excluída com sucesso!'
            ]);
        }

        return redirect()
            ->route('orcamentos.historico.index', $orcamento)
            ->with('success', 'Entrada excluída com sucesso!');
    }

    /**
     * Alternar status de conclusão da entrada
     */
    public function toggleStatus(Request $request, Orcamento $orcamento, HistoricoEntry $historico)
    {
        // Verificar se o orçamento pertence ao usuário
        if ($orcamento->cliente->user_id !== Auth::id()) {
            abort(403);
        }

        // Verificar se a entrada pertence ao orçamento
        if ($historico->orcamento_id !== $orcamento->id) {
            abort(403);
        }

        $historico->update([
            'completed' => !$historico->completed
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $historico->completed ? 'Item marcado como concluído!' : 'Item desmarcado como concluído!',
                'completed' => $historico->completed
            ]);
        }

        return redirect()
            ->route('orcamentos.historico.index', $orcamento)
            ->with('success', $historico->completed ? 'Item marcado como concluído!' : 'Item desmarcado como concluído!');
    }

    /**
     * Excluir arquivo individual
     */
    public function deleteFile(Request $request, Orcamento $orcamento, HistoricoFile $file)
    {
        // Verificar se o orçamento pertence ao usuário
        if ($orcamento->cliente->user_id !== Auth::id()) {
            abort(403);
        }

        // Verificar se o arquivo pertence ao orçamento
        if ($file->historicoEntry->orcamento_id !== $orcamento->id) {
            abort(403);
        }

        // Excluir arquivo do storage
        if (Storage::exists($file->file_path)) {
            Storage::delete($file->file_path);
        }

        // Excluir registro do banco
        $file->delete();

        return response()->json([
            'success' => true,
            'message' => 'Arquivo excluído com sucesso!'
        ]);
    }

    /**
     * Obter arquivos de uma entrada específica do histórico
     */
    public function getFiles(Orcamento $orcamento, HistoricoEntry $historico)
    {
        // Verificar se o orçamento pertence ao usuário
        if ($orcamento->cliente->user_id !== Auth::id()) {
            abort(403);
        }

        // Verificar se a entrada pertence ao orçamento
        if ($historico->orcamento_id !== $orcamento->id) {
            abort(403);
        }

        $files = $historico->files;

        return response()->json([
            'success' => true,
            'files' => $files->map(function ($file) {
                return [
                    'id' => $file->id,
                    'name' => $file->original_name,
                    'size' => $file->formatted_size,
                    'mime_type' => $file->mime_type,
                    'download_url' => $file->download_url,
                    'icon' => $file->icon_class
                ];
            })
        ]);
    }

    /**
     * Obter dados para API (usado em modais, etc.)
     */
    public function api(Orcamento $orcamento)
    {
        // Verificar se o orçamento pertence ao usuário
        if ($orcamento->cliente->user_id !== Auth::id()) {
            abort(403);
        }

        $entries = HistoricoEntry::with(['user', 'files'])
            ->forOrcamento($orcamento->id)
            ->orderByEntryDate('desc')
            ->limit(10)
            ->get();

        return response()->json([
            'entries' => $entries->map(function ($entry) {
                return [
                    'id' => $entry->id,
                    'type' => $entry->type,
                    'title' => $entry->title,
                    'description' => $entry->description,
                    'entry_date' => $entry->entry_date->format('d/m/Y H:i'),
                    'user' => $entry->user->name,
                    'files_count' => $entry->files->count(),
                    'completed' => $entry->completed ?? false
                ];
            })
        ]);
    }
}