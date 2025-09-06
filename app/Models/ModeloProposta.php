<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModeloProposta extends Model
{
    use HasFactory;

    protected $table = 'modelos_propostas';

    protected $fillable = [
        'user_id',
        'nome',
        'conteudo',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean',
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
     * Scope para filtrar por usuário
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope para filtrar apenas ativos
     */
    public function scopeActive($query)
    {
        return $query->where('ativo', true);
    }

    /**
     * Scope para buscar por nome
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('nome', 'like', '%' . $search . '%');
    }
}
