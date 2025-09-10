<?php

namespace App\Http\Controllers;

use App\Models\PortfolioWork;
use App\Models\PortfolioCategory;
use App\Models\PortfolioWorkImage;
use App\Models\Cliente;
use App\Models\Orcamento;
use App\Models\Autor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PortfolioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PortfolioWork::with(['category', 'client', 'featuredImage'])
            ->whereHas('client', function($q) {
                $q->where('user_id', Auth::id());
            });

        // Filtros
        if ($request->filled('category')) {
            $query->where('portfolio_category_id', $request->category);
        }

        if ($request->filled('status')) {
            if ($request->status === 'published') {
                $query->where('is_published', true);
            } elseif ($request->status === 'draft') {
                $query->where('is_published', false);
            }
        }

        if ($request->filled('featured')) {
            $query->where('is_featured', $request->featured === 'yes');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('client', function($clientQuery) use ($search) {
                      $clientQuery->where('nome', 'like', "%{$search}%");
                  });
            });
        }

        $works = $query->orderBy('created_at', 'desc')->paginate(12);
        $categories = PortfolioCategory::active()->ordered()->get();

        return view('portfolio.index', compact('works', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $categories = PortfolioCategory::active()->ordered()->get();
        $clients = Cliente::forUser(Auth::id())->orderBy('nome')->get();
        $authors = Autor::forUser(Auth::id())->orderBy('nome')->get();
        
        // Se vier de um orçamento específico
        $orcamento = null;
        if ($request->filled('orcamento_id')) {
            $orcamento = Orcamento::whereHas('cliente', function($q) {
                $q->where('user_id', Auth::id());
            })->with(['cliente', 'autores'])->findOrFail($request->orcamento_id);
        }

        return view('portfolio.create', compact('categories', 'clients', 'authors', 'orcamento'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:portfolio_works,slug',
            'description' => 'required|string|max:500',
            'content' => 'required|string',
            'portfolio_category_id' => 'required|exists:portfolio_categories,id',
            'client_id' => 'required|exists:clientes,id',
            'orcamento_id' => 'nullable|exists:orcamentos,id',
            'project_date' => 'required|date',
            'project_url' => 'nullable|url',
            'technologies' => 'nullable|array',
            'technologies.*' => 'string|max:50',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
            'authors' => 'nullable|array',
            'authors.*' => 'exists:autores,id',
            'author_roles' => 'nullable|array',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        // Verificar se o cliente pertence ao usuário
        $client = Cliente::forUser(Auth::id())->findOrFail($request->client_id);
        
        // Verificar orçamento se fornecido
        if ($request->filled('orcamento_id')) {
            $orcamento = Orcamento::whereHas('cliente', function($q) {
                $q->where('user_id', Auth::id());
            })->findOrFail($request->orcamento_id);
        }

        DB::beginTransaction();
        try {
            // Criar o trabalho
            $workData = $request->except(['featured_image', 'images', 'authors', 'author_roles']);
            
            if (empty($workData['slug'])) {
                $workData['slug'] = Str::slug($request->title);
            }
            
            // Garantir slug único
            $originalSlug = $workData['slug'];
            $counter = 1;
            while (PortfolioWork::where('slug', $workData['slug'])->exists()) {
                $workData['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }

            $work = PortfolioWork::create($workData);

            // Upload da imagem destacada
            if ($request->hasFile('featured_image')) {
                $featuredImage = $request->file('featured_image');
                $path = $featuredImage->store('portfolio/featured', 'public');
                $work->update(['featured_image' => $path]);
            }

            // Upload das imagens adicionais
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('portfolio/images', 'public');
                    
                    PortfolioWorkImage::create([
                        'portfolio_work_id' => $work->id,
                        'filename' => $image->getClientOriginalName(),
                        'original_name' => $image->getClientOriginalName(),
                        'path' => $path,
                        'size' => $image->getSize(),
                        'mime_type' => $image->getMimeType(),
                        'sort_order' => $index + 1,
                        'is_featured' => $index === 0 && !$request->hasFile('featured_image')
                    ]);
                }
            }

            // Associar autores
            if ($request->filled('authors')) {
                $authorData = [];
                foreach ($request->authors as $index => $authorId) {
                    $role = $request->author_roles[$index] ?? null;
                    $authorData[$authorId] = ['role' => $role];
                }
                $work->authors()->attach($authorData);
            }

            DB::commit();

            return redirect()->route('portfolio.show', $work->slug)
                ->with('success', 'Trabalho de portfólio criado com sucesso!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Erro ao criar trabalho: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PortfolioWork $work)
    {
        // Verificar se o trabalho pertence ao usuário
        if ($work->client->user_id !== Auth::id()) {
            abort(403);
        }

        $work->load([
            'category',
            'client',
            'orcamento',
            'images' => function($query) {
                $query->ordered();
            },
            'authors'
        ]);

        // Incrementar visualizações
        $work->incrementViews();

        // Trabalhos relacionados
        $relatedWorks = $work->getRelatedWorks(3);

        return view('portfolio.show', compact('work', 'relatedWorks'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PortfolioWork $work)
    {
        // Verificar se o trabalho pertence ao usuário
        if ($work->client->user_id !== Auth::id()) {
            abort(403);
        }

        $work->load(['images' => function($query) {
            $query->ordered();
        }, 'authors']);

        $categories = PortfolioCategory::active()->ordered()->get();
        $clients = Cliente::forUser(Auth::id())->orderBy('nome')->get();
        $authors = Autor::forUser(Auth::id())->orderBy('nome')->get();
        $orcamentos = Orcamento::whereHas('cliente', function($q) {
            $q->where('user_id', Auth::id());
        })->with('cliente')->orderBy('created_at', 'desc')->get();

        return view('portfolio.edit', compact('work', 'categories', 'clients', 'authors', 'orcamentos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PortfolioWork $work)
    {
        // Verificar se o trabalho pertence ao usuário
        if ($work->client->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('portfolio_works')->ignore($work->id)],
            'description' => 'required|string|max:500',
            'content' => 'required|string',
            'portfolio_category_id' => 'required|exists:portfolio_categories,id',
            'client_id' => 'required|exists:clientes,id',
            'orcamento_id' => 'nullable|exists:orcamentos,id',
            'project_date' => 'required|date',
            'project_url' => 'nullable|url',
            'technologies' => 'nullable|array',
            'technologies.*' => 'string|max:50',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
            'authors' => 'nullable|array',
            'authors.*' => 'exists:autores,id',
            'author_roles' => 'nullable|array'
        ]);

        // Verificar se o cliente pertence ao usuário
        $client = Cliente::forUser(Auth::id())->findOrFail($request->client_id);

        DB::beginTransaction();
        try {
            // Atualizar dados básicos
            $workData = $request->except(['featured_image', 'authors', 'author_roles']);
            
            if (empty($workData['slug'])) {
                $workData['slug'] = Str::slug($request->title);
            }
            
            // Garantir slug único (exceto para o próprio trabalho)
            $originalSlug = $workData['slug'];
            $counter = 1;
            while (PortfolioWork::where('slug', $workData['slug'])->where('id', '!=', $work->id)->exists()) {
                $workData['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }

            $work->update($workData);

            // Upload da nova imagem destacada
            if ($request->hasFile('featured_image')) {
                // Remover imagem anterior
                if ($work->featured_image && Storage::disk('public')->exists($work->featured_image)) {
                    Storage::disk('public')->delete($work->featured_image);
                }
                
                $featuredImage = $request->file('featured_image');
                $path = $featuredImage->store('portfolio/featured', 'public');
                $work->update(['featured_image' => $path]);
            }

            // Atualizar autores
            if ($request->filled('authors')) {
                $authorData = [];
                foreach ($request->authors as $index => $authorId) {
                    $role = $request->author_roles[$index] ?? null;
                    $authorData[$authorId] = ['role' => $role];
                }
                $work->authors()->sync($authorData);
            } else {
                $work->authors()->detach();
            }

            DB::commit();

            return redirect()->route('portfolio.show', $work->slug)
                ->with('success', 'Trabalho atualizado com sucesso!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Erro ao atualizar trabalho: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PortfolioWork $work)
    {
        // Verificar se o trabalho pertence ao usuário
        if ($work->client->user_id !== Auth::id()) {
            abort(403);
        }

        DB::beginTransaction();
        try {
            // Remover imagem destacada
            if ($work->featured_image && Storage::disk('public')->exists($work->featured_image)) {
                Storage::disk('public')->delete($work->featured_image);
            }

            // Remover todas as imagens (o model já cuida da exclusão dos arquivos)
            $work->images()->delete();

            // Remover associações com autores
            $work->authors()->detach();

            // Remover o trabalho
            $work->delete();

            DB::commit();

            return redirect()->route('portfolio.index')
                ->with('success', 'Trabalho removido com sucesso!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erro ao remover trabalho: ' . $e->getMessage());
        }
    }

    /**
     * Display a listing of works.
     */
    public function worksIndex(Request $request)
    {
        $query = PortfolioWork::with(['category', 'client', 'featuredImage'])
            ->whereHas('client', function($q) {
                $q->where('user_id', Auth::id());
            });

        // Filtros
        if ($request->filled('category')) {
            $query->where('portfolio_category_id', $request->category);
        }

        if ($request->filled('status')) {
            if ($request->status === 'published') {
                $query->where('is_published', true);
            } elseif ($request->status === 'draft') {
                $query->where('is_published', false);
            }
        }

        if ($request->filled('featured')) {
            $query->where('is_featured', $request->featured === 'yes');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('client', function($clientQuery) use ($search) {
                      $clientQuery->where('nome', 'like', "%{$search}%");
                  });
            });
        }

        $works = $query->orderBy('created_at', 'desc')->paginate(12);
        $categories = PortfolioCategory::active()->ordered()->get();

        return view('portfolio.works.index', compact('works', 'categories'));
    }

    /**
     * Pipeline de orçamentos finalizados
     */
    public function pipeline(Request $request)
    {
        $query = Orcamento::with(['cliente', 'autores'])
            ->whereHas('cliente', function($q) {
                $q->where('user_id', Auth::id());
            })
            ->where('status', 'finalizado')
            ->whereDoesntHave('portfolioWork'); // Orçamentos sem trabalho de portfólio

        // Filtros
        if ($request->filled('client')) {
            $query->where('cliente_id', $request->client);
        }

        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('descricao', 'like', "%{$search}%")
                  ->orWhereHas('cliente', function($clientQuery) use ($search) {
                      $clientQuery->where('nome', 'like', "%{$search}%");
                  });
            });
        }

        $orcamentos = $query->orderBy('created_at', 'desc')->paginate(10);
        $clients = Cliente::forUser(Auth::id())->orderBy('nome')->get();
        $authors = Autor::forUser(Auth::id())->orderBy('nome')->get();
        $categories = PortfolioCategory::active()->ordered()->get();

        return view('portfolio.pipeline', compact('orcamentos', 'clients', 'authors', 'categories'))->with('budgets', $orcamentos);
    }
}