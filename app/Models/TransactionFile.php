<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class TransactionFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'nome_arquivo',
        'url_arquivo',
        'tipo_arquivo',
        'tamanho'
    ];

    protected $casts = [
        'tamanho' => 'integer'
    ];

    // Relacionamentos
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    // Métodos auxiliares
    public function getTamanhoFormatadoAttribute()
    {
        $bytes = $this->tamanho;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getExtensaoAttribute()
    {
        return pathinfo($this->nome_arquivo, PATHINFO_EXTENSION);
    }

    public function isImagem()
    {
        $extensoesImagem = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
        return in_array(strtolower($this->extensao), $extensoesImagem);
    }

    public function isPdf()
    {
        return strtolower($this->extensao) === 'pdf';
    }

    public function getUrlDownloadAttribute()
    {
        return Storage::disk('s3')->url($this->url_arquivo);
    }

    public function deletarArquivo()
    {
        if (Storage::disk('s3')->exists($this->url_arquivo)) {
            Storage::disk('s3')->delete($this->url_arquivo);
        }
        $this->delete();
    }
}
