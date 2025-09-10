<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class HistoricoFile extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'historico_entry_id',
        'original_name',
        'file_path',
        'mime_type',
        'file_size'
    ];

    protected $casts = [
        'file_size' => 'integer',
        'created_at' => 'datetime'
    ];

    /**
     * Relacionamento com HistoricoEntry
     */
    public function historicoEntry(): BelongsTo
    {
        return $this->belongsTo(HistoricoEntry::class);
    }

    /**
     * Obter URL para download do arquivo
     */
    public function getDownloadUrlAttribute(): string
    {
        return route('orcamentos.historico.files.download', [
            'orcamento' => $this->historicoEntry->orcamento_id,
            'file' => $this->id
        ]);
    }

    /**
     * Verificar se o arquivo existe no storage
     */
    public function exists(): bool
    {
        return Storage::exists($this->file_path);
    }

    /**
     * Obter tamanho formatado do arquivo
     */
    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Verificar se é uma imagem
     */
    public function isImage(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }
}
