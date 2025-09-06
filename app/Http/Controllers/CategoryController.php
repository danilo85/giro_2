<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Category::forUser(Auth::id())->active();
        
        if ($request->has('tipo') && in_array($request->tipo, ['receita', 'despesa'])) {
            $query->byType($request->tipo);
        }
        
        $categories = $query->orderBy('nome')->get();
        return view('financial.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('financial.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nome' => 'required|string|max:100',
                'tipo' => 'required|in:receita,despesa',
                'icone_url' => 'nullable|string|max:255',
                'cor' => 'nullable|string|max:7',
                'descricao' => 'nullable|string|max:255',
                'ativo' => 'boolean'
            ]);

            $validated['user_id'] = auth()->id();
            $validated['ativo'] = $request->has('ativo');
            
            // Se cor não foi fornecida, remove do array para usar o valor padrão da migration
            if (empty($validated['cor'])) {
                unset($validated['cor']);
            }

            $category = Category::create($validated);

            // Se for uma requisição AJAX, retorna JSON
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Categoria criada com sucesso!',
                    'category' => $category,
                    'redirect' => route('financial.categories.index')
                ]);
            }

            return redirect()->route('financial.categories.index')
                ->with('success', 'Categoria criada com sucesso!');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Se for uma requisição AJAX, retorna erros de validação em JSON
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro de validação',
                    'errors' => $e->errors()
                ], 422);
            }
            
            throw $e;
        } catch (\Exception $e) {
            // Se for uma requisição AJAX, retorna erro genérico em JSON
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro interno do servidor: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Erro ao criar categoria: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $this->authorize('view', $category);
        
        $transactions = $category->transactions()
            ->with(['bank', 'creditCard'])
            ->orderBy('data', 'desc')
            ->paginate(20);
            
        return view('financial.categories.show', compact('category', 'transactions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $this->authorize('update', $category);
        return view('financial.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $this->authorize('update', $category);
        
        try {
            $validated = $request->validate([
                'nome' => 'required|string|max:255',
                'tipo' => 'required|in:receita,despesa',
                'icone' => 'required|string|max:10',
                'descricao' => 'nullable|string|max:255',
                'ativo' => 'nullable|boolean'
            ]);

            // Garantir que o usuário seja definido
            $validated['user_id'] = Auth::id();
            
            // Converter ativo para boolean
            $validated['ativo'] = $request->has('ativo') ? (bool) $request->ativo : false;

            $category->update($validated);

            // Se for uma requisição AJAX, retorna JSON
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Categoria atualizada com sucesso!',
                    'category' => $category,
                    'redirect' => route('financial.categories.index')
                ]);
            }

            return redirect()->route('financial.categories.index')
                ->with('success', 'Categoria atualizada com sucesso!');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Se for uma requisição AJAX, retorna erros de validação em JSON
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro de validação',
                    'errors' => $e->errors()
                ], 422);
            }
            
            throw $e;
        } catch (\Exception $e) {
            // Se for uma requisição AJAX, retorna erro genérico em JSON
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro interno do servidor: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Erro ao atualizar categoria: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Category $category)
    {
        $this->authorize('delete', $category);
        
        try {
            // Verificar se há transações vinculadas
            if ($category->transactions()->count() > 0) {
                $message = 'Não é possível excluir uma categoria que possui transações vinculadas.';
                
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 422);
                }
                
                return redirect()->route('financial.categories.index')
                    ->with('error', $message);
            }
            
            // Deletar permanentemente do banco de dados
            $category->delete();
            
            $message = 'Categoria removida com sucesso!';
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            }

            return redirect()->route('financial.categories.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            $message = 'Erro ao excluir categoria: ' . $e->getMessage();
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 500);
            }
            
            return redirect()->route('financial.categories.index')
                ->with('error', $message);
        }
    }

    /**
     * API endpoint para obter categorias por tipo
     */
    public function getByType($tipo)
    {
        if (!in_array($tipo, ['receita', 'despesa'])) {
            return response()->json(['error' => 'Tipo inválido'], 400);
        }
        
        $categories = Category::forUser(Auth::id())
            ->active()
            ->byType($tipo)
            ->orderBy('nome')
            ->get(['id', 'nome', 'icone_url', 'cor', 'descricao']);
            
        return response()->json($categories);
    }

    /**
     * API endpoint para obter todas as categorias ativas
     */
    public function getAll()
    {
        $categories = Category::forUser(Auth::id())
            ->active()
            ->orderBy('nome')
            ->get(['id', 'nome', 'tipo', 'icone_url', 'cor', 'ativo', 'descricao']);
            
        return response()->json($categories);
    }

    /**
     * API endpoint para obter categorias agrupadas por tipo (receitas e despesas)
     */
    public function getCategoriesGrouped()
    {
        $receitas = Category::forUser(Auth::id())
            ->active()
            ->byType('receita')
            ->orderBy('nome')
            ->get(['id', 'nome', 'tipo', 'icone_url', 'cor', 'descricao']);
            
        $despesas = Category::forUser(Auth::id())
            ->active()
            ->byType('despesa')
            ->orderBy('nome')
            ->get(['id', 'nome', 'tipo', 'icone_url', 'cor', 'descricao']);
            
        return response()->json([
            'receitas' => $receitas,
            'despesas' => $despesas
        ]);
    }

    /**
     * API endpoint para listar categorias
     */
    public function apiIndex()
    {
        $categories = Category::forUser(Auth::id())
            ->active()
            ->orderBy('nome')
            ->get(['id', 'nome', 'tipo', 'icone_url', 'cor', 'descricao']);
            
        return response()->json($categories);
    }
}
