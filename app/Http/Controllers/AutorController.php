<?php

namespace App\Http\Controllers;

use App\Models\Autor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AutorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Autor::forUser(Auth::id());

        // Aplicar filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telefone', 'like', "%{$search}%");
            });
        }

        // Calcular estatísticas antes da paginação (apenas para o usuário logado)
        $totalAutores = Autor::forUser(Auth::id())->count();
        $totalOrcamentos = Autor::forUser(Auth::id())->withCount('orcamentos')->get()->sum('orcamentos_count');
        $valorTotalOrcamentos = Autor::forUser(Auth::id())->with(['orcamentos' => function($query) {
            $query->select('orcamentos.id', 'valor_total');
        }])->get()->sum(function($autor) {
            return $autor->orcamentos->sum('valor_total');
        });
        $autoresComWhatsapp = Autor::forUser(Auth::id())->whereNotNull('whatsapp')->where('whatsapp', '!=', '')->count();

        // Buscar autores com orçamentos e contagem
        $autores = $query->withCount('orcamentos')
            ->with(['orcamentos' => function($query) {
                $query->select('orcamentos.id', 'valor_total');
            }])
            ->paginate(15)
            ->appends($request->query());

        // Mapear autores com valor total dos orçamentos
        $autores->getCollection()->transform(function ($autor) {
            $autor->valor_total_orcamentos = $autor->orcamentos->sum('valor_total');
            return $autor;
        });

        return view('autores.index', compact(
            'autores', 
            'totalAutores', 
            'totalOrcamentos', 
            'valorTotalOrcamentos', 
            'autoresComWhatsapp'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('autores.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'biografia' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->only(['nome', 'email', 'telefone', 'whatsapp', 'biografia']);
        $data['user_id'] = Auth::id();

        // Upload do avatar
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars/autores', 'public');
            $data['avatar'] = $avatarPath;
        }

        $autor = Autor::create($data);

        return redirect()->route('autores.show', $autor)
                       ->with('success', 'Autor criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Autor $autor)
    {
        $this->authorize('view', $autor);
        
        // Carrega os orçamentos do autor com relacionamentos necessários
        $autor->load(['orcamentos' => function($query) {
            $query->with(['cliente', 'autores', 'pagamentos'])
                  ->orderBy('created_at', 'desc');
        }]);
        
        // Cálculos para cards de resumo
        $orcamentos = $autor->orcamentos;
        
        // Valores totais gerados
        $valorTotalGerado = $orcamentos->sum('valor_total');
        $valorTotalPago = $orcamentos->sum(function($orcamento) {
            return $orcamento->pagamentos->sum('valor');
        });
        
        // Contagem de trabalhos por status
        $trabalhosFeitos = $orcamentos->where('status', 'aprovado')->count();
        $trabalhosEmAndamento = $orcamentos->whereIn('status', ['pendente', 'em_analise'])->count();
        $totalTrabalhos = $orcamentos->count();
        
        // Principais parceiros (outros autores que mais trabalharam junto)
        $parceiros = collect();
        foreach ($orcamentos as $orcamento) {
            foreach ($orcamento->autores as $outroAutor) {
                if ($outroAutor->id !== $autor->id) {
                    $parceiros->push($outroAutor);
                }
            }
        }
        
        $principaisParceiros = $parceiros->groupBy('id')
            ->map(function($grupo) {
                $autor = $grupo->first();
                $autor->colaboracoes = $grupo->count();
                return $autor;
            })
            ->sortByDesc('colaboracoes')
            ->take(5)
            ->values();
        
        // Estatísticas por mês (últimos 6 meses)
        $estatisticasMensais = $orcamentos->filter(function($orcamento) {
            return $orcamento->created_at->gte(now()->subMonths(6));
        })->groupBy(function($orcamento) {
            return $orcamento->created_at->format('Y-m');
        })->map(function($grupo) {
            return [
                'total' => $grupo->count(),
                'valor' => $grupo->sum('valor_total'),
                'aprovados' => $grupo->where('status', 'aprovado')->count()
            ];
        });
        
        $resumo = [
            'valor_total_gerado' => $valorTotalGerado,
            'valor_total_pago' => $valorTotalPago,
            'trabalhos_feitos' => $trabalhosFeitos,
            'trabalhos_em_andamento' => $trabalhosEmAndamento,
            'total_trabalhos' => $totalTrabalhos,
            'principais_parceiros' => $principaisParceiros,
            'estatisticas_mensais' => $estatisticasMensais
        ];
        
        return view('autores.show', compact('autor', 'resumo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Autor $autor)
    {
        $this->authorize('update', $autor);

        return view('autores.edit', compact('autor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Autor $autor)
    {
        $this->authorize('update', $autor);

        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'biografia' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->only(['nome', 'email', 'telefone', 'whatsapp', 'biografia']);

        // Upload do avatar
        if ($request->hasFile('avatar')) {
            // Deletar avatar anterior se existir
            if ($autor->avatar) {
                Storage::disk('public')->delete($autor->avatar);
            }
            
            $avatarPath = $request->file('avatar')->store('avatars/autores', 'public');
            $data['avatar'] = $avatarPath;
        }

        $autor->update($data);

        return redirect()->route('autores.show', $autor)
                       ->with('success', 'Autor atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Autor $autor)
    {
        $this->authorize('delete', $autor);

        // Verificar se há orçamentos associados
        if ($autor->orcamentos()->count() > 0) {
            return back()->withErrors(['error' => 'Não é possível excluir autor com orçamentos associados.']);
        }

        // Deletar avatar se existir
        if ($autor->avatar) {
            Storage::disk('public')->delete($autor->avatar);
        }

        $autor->delete();

        return redirect()->route('autores.index')
                       ->with('success', 'Autor excluído com sucesso!');
    }

    /**
     * API para autocomplete de autores
     */
    public function autocomplete(Request $request)
    {
        $search = $request->get('q', '');
        $create = $request->get('create', false);
        
        // Se for para criar um novo autor
        if ($create && !empty($search)) {
            $request->validate([
                'q' => 'required|string|max:255'
            ]);
            
            // Verificar se já existe um autor com esse nome
            $existingAutor = Autor::forUser(Auth::id())
                ->where('nome', $search)
                ->first();
                
            if ($existingAutor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Autor já existe',
                    'autor' => $existingAutor
                ]);
            }
            
            // Criar novo autor
            $autor = Autor::create([
                'nome' => $search,
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Autor criado com sucesso',
                'autor' => $autor
            ]);
        }
        
        // Busca normal para autocomplete
        $autores = Autor::forUser(Auth::id())
            ->where('nome', 'like', "%{$search}%")
            ->orderBy('nome')
            ->limit(10)
            ->get(['id', 'nome', 'email']);

        return response()->json($autores);
    }

    /**
     * API para busca AJAX de autores
     */
    public function search(Request $request)
    {
        $query = Autor::forUser(Auth::id());

        // Aplicar filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telefone', 'like', "%{$search}%");
            });
        }

        // Buscar autores com orçamentos e contagem
        $autores = $query->withCount('orcamentos')
            ->with(['orcamentos' => function($query) {
                $query->select('orcamentos.id', 'valor_total');
            }])
            ->paginate(15);

        // Mapear autores com valor total dos orçamentos
        $autores->getCollection()->transform(function ($autor) {
            $autor->valor_total_orcamentos = $autor->orcamentos->sum('valor_total');
            return $autor;
        });

        return response()->json([
            'success' => true,
            'data' => $autores->items(),
            'pagination' => [
                'current_page' => $autores->currentPage(),
                'last_page' => $autores->lastPage(),
                'per_page' => $autores->perPage(),
                'total' => $autores->total(),
                'has_more_pages' => $autores->hasMorePages()
            ]
        ]);
    }
}
