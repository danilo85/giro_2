<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class TempFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'temp_id',
        'nome_arquivo',
        'url_arquivo',
        'tipo_arquivo',
        'tamanho',
        'user_id'
    ];

    protected $casts = [
        'tamanho' => 'integer'
    ];

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

    /**
     * Mover arquivo temporário para transação definitiva
     */
    public function moveToTransaction($transactionId)
    {
        $transactionFile = TransactionFile::create([
            'transaction_id' => $transactionId,
            'nome_arquivo' => $this->nome_arquivo,
            'url_arquivo' => $this->url_arquivo,
            'tipo_arquivo' => $this->tipo_arquivo,
            'tamanho' => $this->tamanho
        ]);

        // Deletar registro temporário
        $this->delete();

        return $transactionFile;
    }

    /**
     * Limpar arquivos temporários antigos (mais de 24 horas)
     */
    public static function cleanupOldFiles()
    {
        $oldFiles = self::where('created_at', '<', Carbon::now()->subHours(24))->get();

        foreach ($oldFiles as $file) {
            // Deletar arquivo do storage
            $disk = config('filesystems.default') === 's3' ? 's3' : 'public';
            if (Storage::disk($disk)->exists($file->url_arquivo)) {
                Storage::disk($disk)->delete($file->url_arquivo);
            }

            // Deletar registro
            $file->delete();
        }

        return $oldFiles->count();
    }

    public function deletarArquivo()
    {
        $disk = config('filesystems.default') === 's3' ? 's3' : 'public';
        if (Storage::disk($disk)->exists($this->url_arquivo)) {
            Storage::disk($disk)->delete($this->url_arquivo);
        }
        $this->delete();
    }
}