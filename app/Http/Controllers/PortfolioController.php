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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PortfolioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PortfolioWork::with(['category', 'client', 'featuredImage', 'images'])
            ->whereHas('client', function($q) {
                $q->where('user_id', Auth::id());
            });

        // Filtros
        if ($request->filled('category')) {
            $query->where('portfolio_category_id', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('featured')) {
            $query->where('is_featured', $request->featured === 'yes');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('client', 'like', "%{$search}%");
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
        $categories = PortfolioCategory::active()->where('user_id', Auth::id())->ordered()->get();
        $clients = Cliente::forUser(Auth::id())->orderBy('nome')->get();
        $authors = Autor::forUser(Auth::id())->orderBy('nome')->get();
        
        // Se vier de um orçamento específico
        $orcamento = null;
        if ($request->filled('orcamento_id')) {
            $orcamento = Orcamento::whereHas('cliente', function($q) {
                $q->where('user_id', Auth::id());
            })->with(['cliente', 'autores'])->findOrFail($request->orcamento_id);
        }

        return view('portfolio.works.create', compact('categories', 'clients', 'authors', 'orcamento'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:portfolio_works,slug',
            'description' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'portfolio_category_id' => 'required|exists:portfolio_categories,id',
            'client_id' => 'nullable|exists:clientes,id',
            'orcamento_id' => 'nullable|exists:orcamentos,id',
            'project_url' => 'nullable|url',
            'completion_date' => 'nullable|date',
            'technologies' => 'nullable|array',
            'technologies.*' => 'string|max:50',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published',
            'is_featured' => 'boolean',
            'authors' => 'nullable|array',
            'authors.*' => 'exists:autores,id',
            'author_roles' => 'nullable|array',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        \Log::info('Iniciando criação de trabalho de portfólio', [
            'user_id' => Auth::id(),
            'request_data' => $request->except(['featured_image', 'images'])
        ]);

        DB::beginTransaction();
        try {
            // Criar o trabalho
            $workData = $request->except(['featured_image', 'images', 'authors', 'author_roles']);
            $workData['user_id'] = Auth::id();
            
            \Log::info('Dados do trabalho preparados', $workData);
            
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
            \Log::info('Trabalho criado com sucesso', ['work_id' => $work->id, 'client_id' => $work->client_id]);

            // Upload da imagem destacada
            if ($request->hasFile('featured_image')) {
                $featuredImage = $request->file('featured_image');
                $path = $featuredImage->store('portfolio/featured', 'public');
                $work->update(['featured_image' => $path]);
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

            // Processar imagens adicionais
            if ($request->hasFile('images')) {
                $this->uploadImages($work, $request->file('images'));
            }

            DB::commit();

            // Verificar se é uma requisição AJAX
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Trabalho de portfólio criado com sucesso!',
                    'work' => $work->load(['category', 'client'])
                ]);
            }

            return redirect()->route('portfolio.works.index')
                ->with('success', 'Trabalho de portfólio criado com sucesso!');

        } catch (\Exception $e) {
            DB::rollback();
            
            // Verificar se é uma requisição AJAX
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao criar trabalho: ' . $e->getMessage()
                ], 422);
            }
            
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
        // Primeiro verificar se existe user_id diretamente no trabalho
        if ($work->user_id && $work->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Se não tem user_id no trabalho, verificar pelo cliente
        if (!$work->user_id && $work->client && $work->client->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Se não tem nem user_id nem cliente, negar acesso
        if (!$work->user_id && !$work->client) {
            abort(403, 'Trabalho não possui proprietário definido.');
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

        // Trabalhos relacionados
        $relatedWorks = $work->getRelatedWorks(3);

        return view('portfolio.public.work', compact('work', 'relatedWorks'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PortfolioWork $work)
    {
        // Verificar se o trabalho pertence ao usuário
        if ($work->user_id !== Auth::id()) {
            abort(403);
        }

        $work->load(['images' => function($query) {
            $query->orderBy('sort_order');
        }, 'authors']);

        $categories = PortfolioCategory::active()->where('user_id', Auth::id())->orderBy('name')->get();
        $clients = Cliente::forUser(Auth::id())->orderBy('nome')->get();
        $authors = Autor::forUser(Auth::id())->orderBy('nome')->get();

        return view('portfolio.works.edit', compact('work', 'categories', 'clients', 'authors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PortfolioWork $work)
    {
        // Verificar se o trabalho pertence ao usuário
        if ($work->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('portfolio_works')->ignore($work->id)],
            'description' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'portfolio_category_id' => 'required|exists:portfolio_categories,id',
            'client_id' => 'nullable|exists:clientes,id',
            'completion_date' => 'nullable|date',
            'project_url' => 'nullable|url',
            'technologies' => 'nullable|array',
            'technologies.*' => 'string|max:50',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_featured' => 'boolean',
            'status' => 'required|in:draft,published',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'authors' => 'nullable|array',
            'authors.*' => 'exists:autores,id',
            'author_roles' => 'nullable|array',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        DB::beginTransaction();
        try {
            // Atualizar dados básicos
            $workData = $request->except(['featured_image', 'images', 'authors', 'author_roles']);
            $workData['user_id'] = Auth::id();
            
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

            // Processar exclusão de imagens
            if ($request->filled('delete_images')) {
                $imagesToDelete = explode(',', $request->delete_images);
                \Log::info('Imagens para deletar', ['images' => $imagesToDelete]);
                
                foreach ($imagesToDelete as $imageId) {
                    if ($imageId) {
                        $image = PortfolioWorkImage::where('id', $imageId)
                            ->where('portfolio_work_id', $work->id)
                            ->first();
                        
                        if ($image) {
                            \Log::info('Deletando imagem', ['id' => $imageId, 'path' => $image->path]);
                            $image->delete();
                        }
                    }
                }
            }
            
            // Processar imagens adicionais
            \Log::info('Verificando imagens no update', [
                'has_images' => $request->hasFile('images'),
                'images_data' => $request->file('images') ? count($request->file('images')) : 0,
                'all_files' => $request->allFiles(),
                'request_data' => $request->all()
            ]);
            
            if ($request->hasFile('images')) {
                \Log::info('Iniciando upload de imagens no update', ['work_id' => $work->id]);
                $this->uploadImages($work, $request->file('images'));
                \Log::info('Upload de imagens no update finalizado');
            } else {
                \Log::warning('Nenhuma imagem encontrada no request do update');
            }

            DB::commit();

            return redirect()->route('portfolio.works.index')
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
        if ($work->user_id !== Auth::id()) {
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

            return redirect()->route('portfolio.works.index')
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
        $query = PortfolioWork::with(['category', 'featuredImage'])
            ->where('user_id', Auth::id());

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

    /**
     * Upload e salvar imagens adicionais do trabalho
     */
    private function uploadImages(PortfolioWork $work, array $images)
    {
        \Log::info('uploadImages iniciado', ['work_id' => $work->id, 'images_count' => count($images)]);
        
        foreach ($images as $index => $image) {
            \Log::info('Processando imagem', ['index' => $index, 'is_valid' => $image->isValid()]);
            
            if ($image->isValid()) {
                $filename = time() . '_' . $index . '_' . $image->getClientOriginalName();
                $path = $image->storeAs('portfolio/works', $filename, 'public');
                
                \Log::info('Imagem salva no storage', ['filename' => $filename, 'path' => $path]);
                
                // Obter dimensões da imagem
                $imagePath = storage_path('app/public/' . $path);
                $imageSize = getimagesize($imagePath);
                
                $imageData = [
                    'portfolio_work_id' => $work->id,
                    'filename' => $filename,
                    'original_name' => $image->getClientOriginalName(),
                    'path' => $path,
                    'mime_type' => $image->getMimeType(),
                    'file_size' => $image->getSize(),
                    'width' => $imageSize ? $imageSize[0] : null,
                    'height' => $imageSize ? $imageSize[1] : null,
                    'sort_order' => PortfolioWorkImage::getNextSortOrder($work->id)
                ];
                
                \Log::info('Dados da imagem para salvar no BD', $imageData);
                
                try {
                    $portfolioImage = PortfolioWorkImage::create($imageData);
                    \Log::info('Imagem salva no BD com sucesso', ['image_id' => $portfolioImage->id]);
                } catch (\Exception $e) {
                    \Log::error('Erro ao salvar imagem no BD', ['error' => $e->getMessage(), 'data' => $imageData]);
                    throw $e;
                }
            } else {
                \Log::warning('Imagem inválida', ['index' => $index]);
            }
        }
        
        \Log::info('uploadImages finalizado');
    }
}