<?php

namespace App\Http\Controllers;

use App\Models\PortfolioCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PortfolioCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = PortfolioCategory::query();
        
        // Todos os usuários (incluindo admin) devem ver apenas suas próprias categorias
        $query->where('user_id', Auth::id());
        
        $categories = $query->orderBy('sort_order')->get();

        return view('portfolio.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('portfolio.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Debug logs
        Log::info('PortfolioCategory store - Debug START', [
            'user_id' => auth()->id(),
            'is_admin' => auth()->user()->is_admin ?? false,
            'expects_json' => $request->expectsJson(),
            'content_type' => $request->header('Content-Type'),
            'accept' => $request->header('Accept'),
            'x_requested_with' => $request->header('X-Requested-With'),
            'all_headers' => $request->headers->all(),
            'request_data' => $request->all(),
            'request_method' => $request->method(),
            'request_url' => $request->url()
        ]);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:portfolio_categories,slug',
            'description' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'icon' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255'
        ]);

        $categoryData = $request->all();
        $categoryData['user_id'] = Auth::id();
        
        // Gerar slug se não fornecido
        if (empty($categoryData['slug'])) {
            $categoryData['slug'] = Str::slug($request->name);
        }
        
        // Garantir slug único
        $originalSlug = $categoryData['slug'];
        $counter = 1;
        while (PortfolioCategory::where('slug', $categoryData['slug'])->exists()) {
            $categoryData['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Definir ordem se não fornecida
        if (empty($categoryData['sort_order'])) {
            $maxOrder = PortfolioCategory::where('user_id', Auth::id())->max('sort_order') ?? 0;
            $categoryData['sort_order'] = $maxOrder + 1;
        }

        $category = PortfolioCategory::create($categoryData);

        // Debug logs após criação
        Log::info('PortfolioCategory store - Debug após criação', [
            'user_id' => auth()->id(),
            'is_admin' => auth()->user()->is_admin ?? false,
            'expects_json' => $request->expectsJson(),
            'category_created' => $category->id
        ]);

        if ($request->expectsJson()) {
            $jsonResponse = response()->json([
                'success' => true,
                'message' => 'Categoria criada com sucesso!',
                'category' => $category
            ]);
            
            Log::info('PortfolioCategory store - Returning JSON', [
                'response_content' => $jsonResponse->getContent(),
                'response_headers' => $jsonResponse->headers->all()
            ]);
            
            return $jsonResponse;
        }

        Log::info('PortfolioCategory store - Returning REDIRECT', [
            'redirect_to' => route('portfolio.categories.index')
        ]);

        return redirect()->route('portfolio.categories.index')
            ->with('success', 'Categoria criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(PortfolioCategory $category)
    {
        if ($category->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('portfolio.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PortfolioCategory $category)
    {
        if ($category->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('portfolio.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PortfolioCategory $category)
    {
        if ($category->user_id !== Auth::id()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized action.'], 403);
            }
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('portfolio_categories')->ignore($category->id)],
            'description' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'icon' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255'
        ]);

        $categoryData = $request->all();
        
        // Gerar slug se não fornecido
        if (empty($categoryData['slug'])) {
            $categoryData['slug'] = Str::slug($request->name);
        }
        
        // Garantir slug único (exceto para a própria categoria)
        $originalSlug = $categoryData['slug'];
        $counter = 1;
        while (PortfolioCategory::where('slug', $categoryData['slug'])->where('id', '!=', $category->id)->exists()) {
            $categoryData['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        $category->update($categoryData);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Categoria atualizada com sucesso!',
                'category' => $category
            ]);
        }

        return redirect()->route('portfolio.categories.index')
            ->with('success', 'Categoria atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PortfolioCategory $category)
    {
        if ($category->user_id !== Auth::id()) {
            if (request()->expectsJson()) {
                return response()->json(['error' => 'Unauthorized action.'], 403);
            }
            abort(403, 'Unauthorized action.');
        }

        // Verificar se há trabalhos associados
        if ($category->portfolioWorks()->count() > 0) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Não é possível excluir uma categoria que possui trabalhos associados.'
                ], 422);
            }

            return back()->with('error', 'Não é possível excluir uma categoria que possui trabalhos associados.');
        }

        $category->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Categoria removida com sucesso!'
            ]);
        }

        return redirect()->route('portfolio.categories.index')
            ->with('success', 'Categoria removida com sucesso!');
    }

    /**
     * Update the sort order of categories
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'categories' => 'required|array',
            'categories.*.id' => 'required|exists:portfolio_categories,id',
            'categories.*.sort_order' => 'required|integer|min:0'
        ]);

        foreach ($request->categories as $index => $categoryId) {
            $query = PortfolioCategory::where('id', $categoryId);
            
            // Garantir que apenas categorias do usuário logado sejam atualizadas
            $query->where('user_id', Auth::id());
            
            $query->update(['sort_order' => $index + 1]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Ordem das categorias atualizada com sucesso!'
        ]);
    }

    /**
     * Toggle category status
     */
    public function toggleStatus(PortfolioCategory $category)
    {
        if ($category->user_id !== Auth::id()) {
            if (request()->expectsJson()) {
                return response()->json(['error' => 'Unauthorized action.'], 403);
            }
            abort(403, 'Unauthorized action.');
        }

        $category->update(['is_active' => !$category->is_active]);

        $status = $category->is_active ? 'ativada' : 'desativada';
        $message = "Categoria {$status} com sucesso!";

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'is_active' => $category->is_active
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Get categories for API/AJAX requests
     */
    public function api(Request $request)
    {
        $query = PortfolioCategory::query();
        
        // Todos os usuários (incluindo admin) devem ver apenas suas próprias categorias
        $query->where('user_id', Auth::id());
        
        $categories = $query->orderBy('sort_order')->get();

        return response()->json([
            'success' => true,
            'categories' => $categories
        ]);
    }
}