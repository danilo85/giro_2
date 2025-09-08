<?php

namespace App\Http\Controllers;

use App\Models\ModeloProposta;
use App\Models\Autor;
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
        $autores = Autor::forUser(Auth::id())->orderBy('nome')->get();
        return view('modelos-propostas.create', compact('autores'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'conteudo' => 'required|string',
            'categoria' => 'nullable|string|max:100',
            'status' => 'nullable|string|max:50',
            'descricao' => 'nullable|string',
            'observacoes' => 'nullable|string',
            'valor_padrao' => 'nullable|numeric|min:0',
            'prazo_padrao' => 'nullable|integer|min:1',
            'ativo' => 'boolean'
        ]);

        $data = $request->only([
            'nome', 'conteudo', 'categoria', 'status', 'descricao', 
            'observacoes', 'valor_padrao', 'prazo_padrao', 'ativo'
        ]);
        $data['user_id'] = Auth::id();
        $data['ativo'] = $request->has('ativo');
        $data['status'] = $data['status'] ?? 'ativo';
        


        $modelo = ModeloProposta::create($data);

        return redirect()->route('modelos-propostas.show', $modelo)
                       ->with('success', 'Modelo de proposta criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ModeloProposta $modeloProposta)
    {
        $modelo = $modeloProposta;
        return view('modelos-propostas.show', compact('modelo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ModeloProposta $modeloProposta)
    {
        return view('modelos-propostas.edit', compact('modeloProposta'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ModeloProposta $modeloProposta)
    {
        $request->validate([
            'nome' => 'required|string|max:200',
            'conteudo' => 'required|string',
            'categoria' => 'nullable|string|max:100',
            'status' => 'nullable|string|max:50',
            'descricao' => 'nullable|string',
            'observacoes' => 'nullable|string',
            'valor_padrao' => 'nullable|numeric|min:0',
            'prazo_padrao' => 'nullable|integer|min:1',
            'ativo' => 'boolean'
        ]);

        $data = $request->only([
            'nome', 'conteudo', 'categoria', 'status', 'descricao',
            'observacoes', 'valor_padrao', 'prazo_padrao', 'ativo'
        ]);
        $data['ativo'] = $request->has('ativo');
        $data['status'] = $data['status'] ?? 'ativo';
        


        $modeloProposta->update($data);

        return redirect()->route('modelos-propostas.index')
            ->with('success', 'Modelo de proposta atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ModeloProposta $modeloProposta)
    {
        
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
        $modelo = $modeloProposta;
        return response()->json([
            'id' => $modelo->id,
            'nome' => $modelo->nome,
            'conteudo' => $modelo->conteudo
        ]);
    }

    /**
     * Duplicar um modelo de proposta
     */
    public function duplicate(ModeloProposta $modeloProposta)
    {
        $modelo = $modeloProposta;
        // Criar uma cópia do modelo
        $novoModelo = ModeloProposta::create([
            'nome' => 'Cópia de ' . $modelo->nome,
            'conteudo' => $modelo->conteudo,
            'categoria' => $modelo->categoria,
            'status' => $modelo->status,
            'descricao' => $modelo->descricao,
            'observacoes' => $modelo->observacoes,
            'valor_padrao' => $modelo->valor_padrao,
            'prazo_padrao' => $modelo->prazo_padrao,
            'autores_padrao' => $modelo->autores_padrao,
            'ativo' => $modelo->ativo,
            'user_id' => Auth::id()
        ]);

        return redirect()->route('modelos-propostas.show', $novoModelo)
                       ->with('success', 'Modelo duplicado com sucesso!');
    }
}
