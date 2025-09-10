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
        'categoria',
        'status',
        'descricao',
        'observacoes',
        'valor_padrao',
        'prazo_padrao',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'valor_padrao' => 'decimal:2',
        'prazo_padrao' => 'integer',
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

    /**
     * Scope para filtrar por categoria
     */
    public function scopeByCategoria($query, $categoria)
    {
        return $query->where('categoria', $categoria);
    }

    /**
     * Scope para filtrar por status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
