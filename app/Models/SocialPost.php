<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class SocialPost extends Model
{
    use HasFactory;

    protected $table = 'social_posts';

    protected $fillable = [
        'user_id',
        'titulo',
        'legenda',
        'texto_final',
        'call_to_action_image',
        'status',
        'scheduled_date',
        'scheduled_time'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'scheduled_date' => 'date',
        'scheduled_time' => 'datetime:H:i',
    ];

    // Status constants
    const STATUS_RASCUNHO = 'rascunho';
    const STATUS_PUBLICADO = 'publicado';
    const STATUS_ARQUIVADO = 'arquivado';

    public static function getStatusOptions()
    {
        return [
            self::STATUS_RASCUNHO => 'Rascunho',
            self::STATUS_PUBLICADO => 'Publicado',
            self::STATUS_ARQUIVADO => 'Arquivado'
        ];
    }

    /**
     * Boot method para definir user_id automaticamente
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($socialPost) {
            if (empty($socialPost->user_id)) {
                $socialPost->user_id = Auth::id();
            }
        });
    }

    /**
     * Relacionamento com User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com Hashtags (many-to-many)
     */
    public function hashtags(): BelongsToMany
    {
        return $this->belongsToMany(Hashtag::class, 'social_post_hashtags')
                    ->withTimestamps();
    }

    /**
     * Relacionamento com Textos do Carrossel
     */
    public function carouselTexts(): HasMany
    {
        return $this->hasMany(CarouselText::class)->orderBy('position');
    }

    /**
     * Relacionamento com Cores de Fundo
     */
    public function backgroundColors(): HasMany
    {
        return $this->hasMany(ImageBackgroundColor::class, 'post_id');
    }

    /**
     * Scope para filtrar por status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope para filtrar por usuário
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Accessor para status formatado
     */
    public function getStatusLabelAttribute()
    {
        return self::getStatusOptions()[$this->status] ?? $this->status;
    }

    /**
     * Accessor para contagem de hashtags
     */
    public function getHashtagCountAttribute()
    {
        return $this->hashtags()->count();
    }

    /**
     * Accessor para contagem de textos do carrossel
     */
    public function getCarouselTextCountAttribute()
    {
        return $this->carouselTexts()->count();
    }
}