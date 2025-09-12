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
        'project_url',
        'completion_date',
        'technologies',
        'featured_image',
        'meta_title',
        'meta_description',
        'status',
        'is_featured',
        'user_id',
        'orcamento_id'
    ];

    protected $casts = [
        'completion_date' => 'date',
        'technologies' => 'array',
        'is_featured' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($work) {
            if (empty($work->slug)) {
                $work->slug = Str::slug($work->title);
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
     * Relacionamento com usuário
     */
    public function user()
    {
        return $this->belongsTo(User::class);
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
        return $this->belongsTo(Orcamento::class);
    }

    /**
     * Scope para trabalhos publicados
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope para trabalhos em destaque
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope para ordenação por data de conclusão
     */
    public function scopeOrderByCompletionDate($query, $direction = 'desc')
    {
        return $query->orderBy('completion_date', $direction);
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
     * Accessor para data formatada de conclusão
     */
    public function getFormattedCompletionDateAttribute()
    {
        return $this->completion_date ? $this->completion_date->format('M Y') : null;
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Método para obter trabalhos relacionados
     */
    public function getRelatedWorks($limit = 3)
    {
        return static::published()
            ->where('id', '!=', $this->id)
            ->where('portfolio_category_id', $this->portfolio_category_id)
            ->orderByCompletionDate()
            ->limit($limit)
            ->get();
    }
}