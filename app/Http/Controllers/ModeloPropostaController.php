<?php

namespace App\Http\Controllers;

use App\Models\ModeloProposta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModeloPropostaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ModeloProposta::forUser(Auth::id())->orderBy('nome');

        // Filtro de busca
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filtro por status ativo
        if ($request->filled('ativo')) {
            if ($request->ativo === '1') {
                $query->active();
            } elseif ($request->ativo === '0') {
                $query->where('ativo', false);
            }
        }

        $modelos = $query->paginate(15);

        return view('modelos-propostas.index', compact('modelos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('modelos-propostas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'conteudo' => 'required|string',
            'ativo' => 'boolean'
        ]);

        $data = $request->only(['nome', 'conteudo', 'ativo']);
        $data['user_id'] = Auth::id();
        $data['ativo'] = $request->has('ativo');

        $modelo = ModeloProposta::create($data);

        return redirect()->route('modelos-propostas.show', $modelo)
                       ->with('success', 'Modelo de proposta criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ModeloProposta $modeloProposta)
    {
        // Verificar se o modelo pertence ao usuário
        if ($modeloProposta->user_id !== Auth::id()) {
            abort(403);
        }

        return view('modelos-propostas.show', compact('modeloProposta'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ModeloProposta $modeloProposta)
    {
        // Verificar se o modelo pertence ao usuário
        if ($modeloProposta->user_id !== Auth::id()) {
            abort(403);
        }

        return view('modelos-propostas.edit', compact('modeloProposta'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ModeloProposta $modeloProposta)
    {
        // Verificar se o modelo pertence ao usuário
        if ($modeloProposta->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'nome' => 'required|string|max:255',
            'conteudo' => 'required|string',
            'ativo' => 'boolean'
        ]);

        $data = $request->only(['nome', 'conteudo']);
        $data['ativo'] = $request->has('ativo');

        $modeloProposta->update($data);

        return redirect()->route('modelos-propostas.show', $modeloProposta)
                       ->with('success', 'Modelo de proposta atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ModeloProposta $modeloProposta)
    {
        // Verificar se o modelo pertence ao usuário
        if ($modeloProposta->user_id !== Auth::id()) {
            abort(403);
        }

        $modeloProposta->delete();

        return redirect()->route('modelos-propostas.index')
                       ->with('success', 'Modelo de proposta excluído com sucesso!');
    }

    /**
     * API para listar modelos ativos para autocomplete
     */
    public function autocomplete(Request $request)
    {
        $search = $request->get('q', '');
        
        $modelos = ModeloProposta::forUser(Auth::id())
            ->active()
            ->where('nome', 'like', "%{$search}%")
            ->orderBy('nome')
            ->limit(10)
            ->get(['id', 'nome']);

        return response()->json($modelos);
    }

    /**
     * API para obter conteúdo de um modelo
     */
    public function getConteudo(ModeloProposta $modeloProposta)
    {
        // Verificar se o modelo pertence ao usuário
        if ($modeloProposta->user_id !== Auth::id()) {
            abort(403);
        }

        return response()->json([
            'id' => $modeloProposta->id,
            'nome' => $modeloProposta->nome,
            'conteudo' => $modeloProposta->conteudo
        ]);
    }
}
