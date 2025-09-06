<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class OrcamentoFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'orcamento_id',
        'user_id',
        'nome_arquivo',
        'url_arquivo',
        'tipo_arquivo',
        'tamanho',
        'categoria',
        'descricao'
    ];

    protected $casts = [
        'tamanho' => 'integer',
    ];

    /**
     * Relacionamento com Orcamento
     */
    public function orcamento(): BelongsTo
    {
        return $this->belongsTo(Orcamento::class);
    }

    /**
     * Relacionamento com User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obter URL do arquivo
     */
    public function getUrlAttribute(): string
    {
        return $this->url_arquivo;
    }

    /**
     * Verificar se é uma imagem
     */
    public function getIsImageAttribute(): bool
    {
        return str_starts_with($this->tipo_arquivo, 'image/');
    }

    /**
     * Formatar tamanho do arquivo
     */
    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->tamanho;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Obter ícone baseado no tipo MIME
     */
    public function getIconAttribute(): string
    {
        if (str_starts_with($this->tipo_arquivo, 'image/')) {
            return 'fas fa-image';
        }
        
        if (str_starts_with($this->tipo_arquivo, 'video/')) {
            return 'fas fa-video';
        }
        
        if (str_starts_with($this->tipo_arquivo, 'audio/')) {
            return 'fas fa-music';
        }
        
        if ($this->tipo_arquivo === 'application/pdf') {
            return 'fas fa-file-pdf';
        }
        
        if (in_array($this->tipo_arquivo, [
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ])) {
            return 'fas fa-file-word';
        }
        
        if (in_array($this->tipo_arquivo, [
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ])) {
            return 'fas fa-file-excel';
        }
        
        if (in_array($this->tipo_arquivo, [
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation'
        ])) {
            return 'fas fa-file-powerpoint';
        }
        
        if (str_starts_with($this->tipo_arquivo, 'text/')) {
            return 'fas fa-file-alt';
        }
        
        if (in_array($this->tipo_arquivo, ['application/zip', 'application/x-rar-compressed'])) {
            return 'fas fa-file-archive';
        }
        
        return 'fas fa-file';
    }

    /**
     * Scope para filtrar por categoria
     */
    public function scopeCategoria($query, $categoria)
    {
        return $query->where('categoria', $categoria);
    }

    /**
     * Scope para filtrar por usuário
     */
    public function scopeDoUsuario($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Excluir arquivo do storage ao deletar o registro
     */
    protected static function boot()
    {
        parent::boot();
        
        static::deleting(function ($file) {
            try {
                // Se for URL do S3, extrair o path
                if (str_contains($file->url_arquivo, 's3.amazonaws.com') || str_contains($file->url_arquivo, 'amazonaws.com')) {
                    $path = parse_url($file->url_arquivo, PHP_URL_PATH);
                    if ($path) {
                        Storage::disk('s3')->delete(ltrim($path, '/'));
                    }
                } else {
                    // Para arquivos locais
                    $path = str_replace('/storage/', '', $file->url_arquivo);
                    Storage::disk('public')->delete($path);
                }
            } catch (\Exception $e) {
                \Log::error('Erro ao excluir arquivo do storage: ' . $e->getMessage());
            }
        });
    }
}