<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Hashtag extends Model
{
    use HasFactory;

    protected $table = 'hashtags';

    protected $fillable = [
        'name',
        'usage_count'
    ];

    protected $casts = [
        'usage_count' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relacionamento com Social Posts (many-to-many)
     */
    public function socialPosts(): BelongsToMany
    {
        return $this->belongsToMany(SocialPost::class, 'social_post_hashtags')
                    ->withTimestamps();
    }

    /**
     * Scope para buscar hashtags por nome
     */
    public function scopeByName($query, $name)
    {
        return $query->where('name', 'like', '%' . $name . '%');
    }

    /**
     * Scope para ordenar por uso
     */
    public function scopeByUsage($query, $direction = 'desc')
    {
        return $query->orderBy('usage_count', $direction);
    }

    /**
     * Incrementar contador de uso
     */
    public function incrementUsage()
    {
        $this->increment('usage_count');
    }

    /**
     * Decrementar contador de uso
     */
    public function decrementUsage()
    {
        $this->decrement('usage_count');
    }

    /**
     * Buscar ou criar hashtag
     */
    public static function findOrCreateByName($name)
    {
        $name = strtolower(trim($name));
        
        return static::firstOrCreate(
            ['name' => $name],
            ['usage_count' => 0]
        );
    }
}