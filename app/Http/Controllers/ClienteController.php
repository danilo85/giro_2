<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Cliente::forUser(Auth::id())->withCount('orcamentos')
            ->with(['orcamentos' => function($query) {
                $query->select('cliente_id', 'valor_total');
            }])
            ->orderBy('nome');

        // Filtro de busca
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('pessoa_contato', 'like', "%{$search}%")
                  ->orWhere('telefone', 'like', "%{$search}%");
            });
        }

        $clientes = $query->paginate(15);
        
        // Calcular estatísticas separadamente
        $totalOrcamentos = Cliente::forUser(Auth::id())->withCount('orcamentos')->get()->sum('orcamentos_count');
        $valorTotal = \App\Models\Orcamento::whereHas('cliente', function($q) {
            $q->where('user_id', Auth::id());
        })->sum('valor_total');
        $novosEsteMes = Cliente::forUser(Auth::id())->where('created_at', '>=', now()->startOfMonth())->count();

        return view('clientes.index', compact('clientes', 'totalOrcamentos', 'valorTotal', 'novosEsteMes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'pessoa_contato' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->only(['nome', 'pessoa_contato', 'email', 'telefone', 'whatsapp']);
        $data['user_id'] = Auth::id();

        // Upload do avatar
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars/clientes', 'public');
            $data['avatar'] = $avatarPath;
        }

        $cliente = Cliente::create($data);

        return redirect()->route('clientes.show', $cliente)
                       ->with('success', 'Cliente criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cliente $cliente)
    {
        // Verificar se o cliente pertence ao usuário
        if ($cliente->user_id !== Auth::id()) {
            abort(403);
        }

        $cliente->load(['orcamentos' => function($query) {
            $query->orderBy('created_at', 'desc');
        }]);

        return view('clientes.show', compact('cliente'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cliente $cliente)
    {
        // Verificar se o cliente pertence ao usuário
        if ($cliente->user_id !== Auth::id()) {
            abort(403);
        }

        return view('clientes.edit', compact('cliente'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cliente $cliente)
    {
        // Verificar se o cliente pertence ao usuário
        if ($cliente->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'nome' => 'required|string|max:255',
            'pessoa_contato' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->only(['nome', 'pessoa_contato', 'email', 'telefone', 'whatsapp']);

        // Upload do avatar
        if ($request->hasFile('avatar')) {
            // Deletar avatar anterior se existir
            if ($cliente->avatar) {
                Storage::disk('public')->delete($cliente->avatar);
            }
            
            $avatarPath = $request->file('avatar')->store('avatars/clientes', 'public');
            $data['avatar'] = $avatarPath;
        }

        $cliente->update($data);

        return redirect()->route('clientes.show', $cliente)
                       ->with('success', 'Cliente atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cliente $cliente)
    {
        // Verificar se o cliente pertence ao usuário
        if ($cliente->user_id !== Auth::id()) {
            abort(403);
        }

        // Verificar se há orçamentos associados
        if ($cliente->orcamentos()->count() > 0) {
            return back()->withErrors(['error' => 'Não é possível excluir cliente com orçamentos associados.']);
        }

        // Deletar avatar se existir
        if ($cliente->avatar) {
            Storage::disk('public')->delete($cliente->avatar);
        }

        $cliente->delete();

        return redirect()->route('clientes.index')
                       ->with('success', 'Cliente excluído com sucesso!');
    }

    /**
     * API para autocomplete de clientes
     */
    public function autocomplete(Request $request)
    {
        $search = $request->get('q', '');
        
        $clientes = Cliente::forUser(Auth::id())
            ->where('nome', 'like', "%{$search}%")
            ->orderBy('nome')
            ->limit(10)
            ->get(['id', 'nome', 'email']);

        return response()->json($clientes);
    }

    /**
     * Busca AJAX para clientes
     */
    public function search(Request $request)
    {
        $search = $request->get('search', '');
        
        $query = Cliente::forUser(Auth::id())->withCount('orcamentos')
            ->with(['orcamentos' => function($query) {
                $query->select('cliente_id', 'valor_total');
            }])
            ->orderBy('nome');

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('pessoa_contato', 'like', "%{$search}%")
                  ->orWhere('telefone', 'like', "%{$search}%");
            });
        }

        $clientes = $query->paginate(15);
        
        return response()->json([
            'html' => view('clientes.partials.lista', compact('clientes'))->render(),
            'pagination' => $clientes->links()->render()
        ]);
    }

    /**
     * Gerar token para extrato público do cliente
     */
    public function gerarTokenExtrato(Cliente $cliente)
    {
        // Verificar se o cliente pertence ao usuário
        if ($cliente->user_id !== Auth::id()) {
            abort(403);
        }

        // Gerar ou obter token existente
        $token = $cliente->getOrGenerateExtratoToken();
        
        // Gerar URL do extrato público
        $url = route('public.extrato.public', [
            'cliente_id' => $cliente->id,
            'token' => $token
        ]);

        return response()->json([
            'success' => true,
            'url' => $url,
            'token' => $token
        ]);
    }

    /**
     * Verificar status do extrato do cliente
     */
    public function checkExtractStatus(Cliente $cliente)
    {
        // Verificar se o cliente pertence ao usuário
        if ($cliente->user_id !== Auth::id()) {
            abort(403);
        }

        $hasActiveLink = !empty($cliente->extrato_token);
        $url = null;

        if ($hasActiveLink) {
            $url = route('public.extrato.public', [
                'cliente_id' => $cliente->id,
                'token' => $cliente->extrato_token
            ]);
        }

        return response()->json([
            'success' => true,
            'hasActiveLink' => $hasActiveLink,
            'url' => $url
        ]);
    }

    /**
     * Desativar token do extrato do cliente
     */
    public function desativarTokenExtrato(Cliente $cliente)
    {
        // Verificar se o cliente pertence ao usuário
        if ($cliente->user_id !== Auth::id()) {
            abort(403);
        }

        // Remover o token
        $cliente->update([
            'extrato_token' => null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Token do extrato desativado com sucesso'
        ]);
    }
}
