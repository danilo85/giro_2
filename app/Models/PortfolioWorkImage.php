<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PortfolioWorkImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'portfolio_work_id',
        'filename',
        'original_name',
        'path',
        'alt_text',
        'caption',
        'size',
        'mime_type',
        'sort_order',
        'is_featured'
    ];

    protected $casts = [
        'size' => 'integer',
        'sort_order' => 'integer',
        'is_featured' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($image) {
            // Remove o arquivo físico quando a imagem é deletada
            if ($image->path && Storage::disk('public')->exists($image->path)) {
                Storage::disk('public')->delete($image->path);
            }
        });

        static::creating(function ($image) {
            // Se esta imagem está sendo marcada como destacada,
            // remove o destaque das outras imagens do mesmo trabalho
            if ($image->is_featured) {
                static::where('portfolio_work_id', $image->portfolio_work_id)
                    ->where('is_featured', true)
                    ->update(['is_featured' => false]);
            }
        });

        static::updating(function ($image) {
            // Se esta imagem está sendo marcada como destacada,
            // remove o destaque das outras imagens do mesmo trabalho
            if ($image->isDirty('is_featured') && $image->is_featured) {
                static::where('portfolio_work_id', $image->portfolio_work_id)
                    ->where('id', '!=', $image->id)
                    ->where('is_featured', true)
                    ->update(['is_featured' => false]);
            }
        });
    }

    /**
     * Relacionamento com trabalho de portfólio
     */
    public function portfolioWork()
    {
        return $this->belongsTo(PortfolioWork::class);
    }

    /**
     * Scope para imagens ordenadas
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at');
    }

    /**
     * Scope para imagem destacada
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Accessor para URL completa da imagem
     */
    public function getUrlAttribute()
    {
        return $this->path ? asset('storage/' . $this->path) : null;
    }

    /**
     * Accessor para URL da thumbnail
     */
    public function getThumbnailUrlAttribute()
    {
        if (!$this->path) {
            return null;
        }

        $pathInfo = pathinfo($this->path);
        $thumbnailPath = $pathInfo['dirname'] . '/thumbs/' . $pathInfo['basename'];
        
        if (Storage::disk('public')->exists($thumbnailPath)) {
            return asset('storage/' . $thumbnailPath);
        }
        
        return $this->url;
    }

    /**
     * Accessor para tamanho formatado
     */
    public function getFormattedSizeAttribute()
    {
        if (!$this->size) {
            return null;
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->size;
        $unitIndex = 0;

        while ($size >= 1024 && $unitIndex < count($units) - 1) {
            $size /= 1024;
            $unitIndex++;
        }

        return round($size, 2) . ' ' . $units[$unitIndex];
    }

    /**
     * Método para definir como imagem destacada
     */
    public function setAsFeatured()
    {
        // Remove destaque de outras imagens do mesmo trabalho
        static::where('portfolio_work_id', $this->portfolio_work_id)
            ->where('id', '!=', $this->id)
            ->update(['is_featured' => false]);
        
        // Define esta como destacada
        $this->update(['is_featured' => true]);
    }

    /**
     * Método para reordenar imagens
     */
    public static function reorder($portfolioWorkId, array $imageIds)
    {
        foreach ($imageIds as $index => $imageId) {
            static::where('id', $imageId)
                ->where('portfolio_work_id', $portfolioWorkId)
                ->update(['sort_order' => $index + 1]);
        }
    }

    /**
     * Método para obter próxima ordem
     */
    public static function getNextSortOrder($portfolioWorkId)
    {
        $maxOrder = static::where('portfolio_work_id', $portfolioWorkId)
            ->max('sort_order');
        
        return ($maxOrder ?? 0) + 1;
    }
}