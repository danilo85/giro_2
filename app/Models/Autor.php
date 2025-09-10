<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Autor extends Model
{
    use HasFactory;

    protected $table = 'autores';

    protected $fillable = [
        'user_id',
        'nome',
        'email',
        'telefone',
        'whatsapp',
        'biografia',
        'avatar'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relacionamento com User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com orçamentos (many-to-many)
     */
    public function orcamentos()
    {
        return $this->belongsToMany(Orcamento::class, 'orcamento_autores')
                    ->withTimestamps();
    }

    /**
     * Relacionamento com trabalhos de portfólio (many-to-many)
     */
    public function portfolioWorks()
    {
        return $this->belongsToMany(PortfolioWork::class, 'portfolio_work_authors', 'author_id', 'portfolio_work_id')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    /**
     * Scope para filtrar por usuário
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Accessor para avatar URL
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return null;
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'id';
    }
}
