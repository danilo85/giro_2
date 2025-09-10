<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class PortfolioCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'color',
        'icon',
        'is_active',
        'sort_order',
        'meta_title',
        'meta_description',
        'meta_keywords'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name') && empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * Relacionamento com usuário
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com trabalhos de portfólio
     */
    public function portfolioWorks()
    {
        return $this->hasMany(PortfolioWork::class);
    }

    /**
     * Relacionamento com trabalhos publicados
     */
    public function publishedWorks()
    {
        return $this->hasMany(PortfolioWork::class)->where('is_published', true);
    }

    /**
     * Scope para categorias ativas
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para ordenação
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Accessor para URL da categoria
     */
    public function getUrlAttribute()
    {
        return route('portfolio.category', $this->slug);
    }

    /**
     * Método para contar trabalhos publicados
     */
    public function getPublishedWorksCountAttribute()
    {
        return $this->publishedWorks()->count();
    }
}