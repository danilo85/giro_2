<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PortfolioWork extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'portfolio_category_id',
        'client_id',
        'orcamento_id',
        'project_date',
        'project_url',
        'technologies',
        'featured_image',
        'is_featured',
        'is_published',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'views_count',
        'sort_order'
    ];

    protected $casts = [
        'project_date' => 'date',
        'technologies' => 'array',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'views_count' => 'integer',
        'sort_order' => 'integer'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($work) {
            if (empty($work->slug)) {
                $work->slug = Str::slug($work->title);
            }
            if (is_null($work->views_count)) {
                $work->views_count = 0;
            }
        });

        static::updating(function ($work) {
            if ($work->isDirty('title') && empty($work->slug)) {
                $work->slug = Str::slug($work->title);
            }
        });
    }

    /**
     * Relacionamento com categoria
     */
    public function category()
    {
        return $this->belongsTo(PortfolioCategory::class, 'portfolio_category_id');
    }

    /**
     * Relacionamento com cliente
     */
    public function client()
    {
        return $this->belongsTo(Cliente::class, 'client_id');
    }

    /**
     * Relacionamento com orçamento
     */
    public function orcamento()
    {
        return $this->belongsTo(Orcamento::class, 'orcamento_id');
    }

    /**
     * Relacionamento com imagens
     */
    public function images()
    {
        return $this->hasMany(PortfolioWorkImage::class)->orderBy('sort_order');
    }

    /**
     * Relacionamento com imagem destacada
     */
    public function featuredImage()
    {
        return $this->hasOne(PortfolioWorkImage::class)->where('is_featured', true);
    }

    /**
     * Relacionamento com autores (many-to-many)
     */
    public function authors()
    {
        return $this->belongsToMany(Autor::class, 'portfolio_work_authors', 'portfolio_work_id', 'author_id')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    /**
     * Scope para trabalhos publicados
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope para trabalhos em destaque
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope para ordenação por data do projeto
     */
    public function scopeOrderByProjectDate($query, $direction = 'desc')
    {
        return $query->orderBy('project_date', $direction);
    }

    /**
     * Scope para filtrar por categoria
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('portfolio_category_id', $categoryId);
    }

    /**
     * Accessor para URL do trabalho
     */
    public function getUrlAttribute()
    {
        return route('portfolio.public.work', $this->slug);
    }

    /**
     * Accessor para imagem destacada URL
     */
    public function getFeaturedImageUrlAttribute()
    {
        if ($this->featured_image) {
            return asset('storage/' . $this->featured_image);
        }
        
        $featuredImg = $this->featuredImage;
        if ($featuredImg) {
            return asset('storage/' . $featuredImg->path);
        }
        
        return asset('images/portfolio-placeholder.jpg');
    }

    /**
     * Accessor para data formatada do projeto
     */
    public function getFormattedProjectDateAttribute()
    {
        return $this->project_date ? $this->project_date->format('M Y') : null;
    }

    /**
     * Método para incrementar visualizações
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    /**
     * Método para obter trabalhos relacionados
     */
    public function getRelatedWorks($limit = 3)
    {
        return static::published()
            ->where('id', '!=', $this->id)
            ->where('portfolio_category_id', $this->portfolio_category_id)
            ->orderByProjectDate()
            ->limit($limit)
            ->get();
    }
}