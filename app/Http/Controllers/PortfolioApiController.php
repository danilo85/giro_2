<?php

namespace App\Http\Controllers;

use App\Models\PortfolioWork;
use App\Models\PortfolioCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PortfolioApiController extends Controller
{
    /**
     * Get public portfolio works
     */
    public function works(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'category' => 'nullable|string',
            'featured' => 'nullable|boolean',
            'limit' => 'nullable|integer|min:1|max:50',
            'page' => 'nullable|integer|min:1'
        ]);

        $cacheKey = 'portfolio_works_' . md5(serialize($request->all()));
        
        $result = Cache::remember($cacheKey, 300, function() use ($request) {
            $query = PortfolioWork::with([
                'category:id,name,slug,color',
                'client:id,nome',
                'featuredImage:id,portfolio_work_id,filename,path'
            ])
            ->published()
            ->select([
                'id', 'title', 'slug', 'description', 'portfolio_category_id',
                'client_id', 'project_date', 'project_url', 'technologies',
                'featured_image', 'is_featured', 'views_count', 'created_at'
            ]);

            // Filtrar por usuário
            if ($request->filled('user_id')) {
                $query->whereHas('client', function($q) use ($request) {
                    $q->where('user_id', $request->user_id);
                });
            }

            // Filtrar por categoria
            if ($request->filled('category')) {
                $query->whereHas('category', function($q) use ($request) {
                    $q->where('slug', $request->category);
                });
            }

            // Filtrar trabalhos em destaque
            if ($request->filled('featured') && $request->featured) {
                $query->featured();
            }

            $limit = $request->get('limit', 12);
            $works = $query->latest('created_at')->paginate($limit);

            return [
                'data' => $works->items(),
                'pagination' => [
                    'current_page' => $works->currentPage(),
                    'last_page' => $works->lastPage(),
                    'per_page' => $works->perPage(),
                    'total' => $works->total(),
                    'has_more' => $works->hasMorePages()
                ]
            ];
        });

        return response()->json([
            'success' => true,
            'works' => $result['data'],
            'pagination' => $result['pagination']
        ]);
    }

    /**
     * Get a specific portfolio work
     */
    public function work($slug)
    {
        $cacheKey = "portfolio_work_{$slug}";
        
        $work = Cache::remember($cacheKey, 600, function() use ($slug) {
            return PortfolioWork::with([
                'category:id,name,slug,color,description',
                'client:id,nome',
                'images' => function($query) {
                    $query->ordered()->select([
                        'id', 'portfolio_work_id', 'filename', 'original_name',
                        'path', 'alt_text', 'caption', 'sort_order'
                    ]);
                },
                'authors:id,nome,email'
            ])
            ->published()
            ->where('slug', $slug)
            ->select([
                'id', 'title', 'slug', 'description', 'content',
                'portfolio_category_id', 'client_id', 'project_date',
                'project_url', 'technologies', 'featured_image',
                'is_featured', 'views_count', 'meta_title',
                'meta_description', 'meta_keywords', 'created_at', 'updated_at'
            ])
            ->first();
        });

        if (!$work) {
            return response()->json([
                'success' => false,
                'message' => 'Trabalho não encontrado'
            ], 404);
        }

        // Incrementar visualizações (não cachear)
        $work->incrementViews();

        // Trabalhos relacionados
        // $relatedWorks = $this->getRelatedWorks($work); // Comentado temporariamente
        $relatedWorks = [];

        return response()->json([
            'success' => true,
            'work' => $work,
            'related_works' => $relatedWorks
        ]);
    }

    /**
     * Get portfolio categories
     */
    public function categories(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'with_counts' => 'nullable|boolean'
        ]);

        $cacheKey = 'portfolio_categories_' . md5(serialize($request->all()));
        
        $categories = Cache::remember($cacheKey, 600, function() use ($request) {
            $query = PortfolioCategory::active()->ordered();

            // Filtrar por usuário
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }

            // Incluir contagem de trabalhos
            if ($request->filled('with_counts') && $request->with_counts) {
                $query->withCount(['publishedWorks as works_count']);
            }

            return $query->select([
                'id', 'name', 'slug', 'description', 'color',
                'icon', 'sort_order', 'meta_title', 'meta_description'
            ])->get();
        });

        return response()->json([
            'success' => true,
            'categories' => $categories
        ]);
    }

    /**
     * Get portfolio statistics
     */
    public function stats(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id'
        ]);

        $cacheKey = 'portfolio_stats_' . ($request->user_id ?? 'all');
        
        $stats = Cache::remember($cacheKey, 300, function() use ($request) {
            $worksQuery = PortfolioWork::published();
            $categoriesQuery = PortfolioCategory::active();

            // Filtrar por usuário
            if ($request->filled('user_id')) {
                $worksQuery->whereHas('client', function($q) use ($request) {
                    $q->where('user_id', $request->user_id);
                });
                $categoriesQuery->where('user_id', $request->user_id);
            }

            return [
                'total_works' => $worksQuery->count(),
                'featured_works' => $worksQuery->clone()->featured()->count(),
                'total_categories' => $categoriesQuery->count(),
                'total_views' => $worksQuery->sum('views_count'),
                'latest_work' => $worksQuery->latest()->first([
                    'id', 'title', 'slug', 'created_at'
                ])
            ];
        });

        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }

    /**
     * Search portfolio works
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2|max:100',
            'user_id' => 'nullable|exists:users,id',
            'category' => 'nullable|string',
            'limit' => 'nullable|integer|min:1|max:20'
        ]);

        $query = PortfolioWork::with([
            'category:id,name,slug,color',
            'client:id,nome'
        ])
        ->published()
        ->select([
            'id', 'title', 'slug', 'description', 'portfolio_category_id',
            'client_id', 'project_date', 'featured_image', 'is_featured',
            'views_count', 'created_at'
        ]);

        // Filtrar por usuário
        if ($request->filled('user_id')) {
            $query->whereHas('client', function($q) use ($request) {
                $q->where('user_id', $request->user_id);
            });
        }

        // Filtrar por categoria
        if ($request->filled('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Busca textual
        $searchTerm = $request->q;
        $query->where(function($q) use ($searchTerm) {
            $q->where('title', 'like', "%{$searchTerm}%")
              ->orWhere('description', 'like', "%{$searchTerm}%")
              ->orWhere('content', 'like', "%{$searchTerm}%")
              ->orWhere('technologies', 'like', "%{$searchTerm}%")
              ->orWhereHas('category', function($categoryQuery) use ($searchTerm) {
                  $categoryQuery->where('name', 'like', "%{$searchTerm}%");
              })
              ->orWhereHas('client', function($clientQuery) use ($searchTerm) {
                  $clientQuery->where('nome', 'like', "%{$searchTerm}%");
              });
        });

        $limit = $request->get('limit', 10);
        $works = $query->latest('created_at')->paginate($limit);

        return response()->json([
            'success' => true,
            'query' => $searchTerm,
            'works' => $works->items(),
            'pagination' => [
                'current_page' => $works->currentPage(),
                'last_page' => $works->lastPage(),
                'per_page' => $works->perPage(),
                'total' => $works->total(),
                'has_more' => $works->hasMorePages()
            ]
        ]);
    }

    /**
     * Get user's public portfolio info
     */
    public function userPortfolio($userId)
    {
        $cacheKey = "user_portfolio_{$userId}";
        
        $data = Cache::remember($cacheKey, 600, function() use ($userId) {
            $user = User::findOrFail($userId);
            
            $stats = [
                'total_works' => PortfolioWork::published()
                    ->whereHas('client', function($q) use ($userId) {
                        $q->where('user_id', $userId);
                    })->count(),
                'total_categories' => PortfolioCategory::active()
                    ->where('user_id', $userId)->count(),
                'total_views' => PortfolioWork::published()
                    ->whereHas('client', function($q) use ($userId) {
                        $q->where('user_id', $userId);
                    })->sum('views_count')
            ];

            $featuredWorks = PortfolioWork::with([
                'category:id,name,slug,color',
                'client:id,nome'
            ])
            ->published()
            ->featured()
            ->whereHas('client', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->latest()
            ->limit(6)
            ->get([
                'id', 'title', 'slug', 'description', 'portfolio_category_id',
                'client_id', 'project_date', 'featured_image', 'views_count'
            ]);

            $categories = PortfolioCategory::active()
                ->where('user_id', $userId)
                ->withCount(['publishedWorks as works_count'])
                ->ordered()
                ->get([
                    'id', 'name', 'slug', 'description', 'color', 'icon'
                ]);

            return [
                'user' => $user->only(['id', 'name', 'email']),
                'stats' => $stats,
                'featured_works' => $featuredWorks,
                'categories' => $categories
            ];
        });

        return response()->json([
            'success' => true,
            'portfolio' => $data
        ]);
    }

    /**
     * Display work detail page
     */
    public function workDetail(PortfolioWork $work)
    {
        // Verificar se o trabalho está publicado
        if ($work->status !== 'published') {
            abort(404);
        }

        // Carregar relacionamentos necessários
        $work->load([
            'category',
            'client',
            'images' => function($query) {
                $query->ordered();
            },
            'authors'
        ]);

        // Incrementar visualizações
        // $work->incrementViews(); // Comentado: coluna views_count não existe

        // Trabalhos relacionados
        $relatedWorks = $this->getRelatedWorks($work, 3);

        return view('portfolio.public.work', compact('work', 'relatedWorks'));
    }

    /**
     * Get related works
     */
    private function getRelatedWorks(PortfolioWork $work, int $limit = 3)
    {
        return PortfolioWork::with([
            'category:id,name,slug,color',
            'client:id,nome'
        ])
        ->published()
        ->where('id', '!=', $work->id)
        ->where(function($query) use ($work) {
            $query->where('portfolio_category_id', $work->portfolio_category_id);
            
            if ($work->client && $work->client->user_id) {
                $query->orWhereHas('client', function($q) use ($work) {
                    $q->where('user_id', $work->client->user_id);
                });
            }
        })
        ->latest()
        ->limit($limit)
        ->get([
            'id', 'title', 'slug', 'description', 'portfolio_category_id',
            'client_id', 'completion_date', 'featured_image'
        ]);
    }
}