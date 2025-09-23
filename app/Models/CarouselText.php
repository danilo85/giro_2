<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarouselText extends Model
{
    use HasFactory;

    protected $table = 'carousel_texts';

    protected $fillable = [
        'social_post_id',
        'position',
        'texto'
    ];

    protected $casts = [
        'position' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relacionamento com Social Post
     */
    public function socialPost(): BelongsTo
    {
        return $this->belongsTo(SocialPost::class);
    }

    /**
     * Scope para filtrar por posição
     */
    public function scopeByPosition($query, $position)
    {
        return $query->where('position', $position);
    }

    /**
     * Scope para ordenar por posição
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('position');
    }

    /**
     * Validar posição (1-10)
     */
    public function isValidPosition($position)
    {
        return $position >= 1 && $position <= 10;
    }

    /**
     * Boot method para validações
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($carouselText) {
            if ($carouselText->position < 1 || $carouselText->position > 10) {
                throw new \InvalidArgumentException('A posição deve estar entre 1 e 10.');
            }
        });
    }
}