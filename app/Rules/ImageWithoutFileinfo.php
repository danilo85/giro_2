<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;
use App\Utils\MimeTypeDetector;

class ImageWithoutFileinfo implements ValidationRule
{
    private $maxSize;
    private $allowedMimes;

    public function __construct($maxSize = 2048, $allowedMimes = ['jpeg', 'png', 'jpg', 'gif', 'webp'])
    {
        $this->maxSize = $maxSize * 1024; // Converter KB para bytes
        $this->allowedMimes = $allowedMimes;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!($value instanceof UploadedFile)) {
            $fail('O campo :attribute deve ser um arquivo.');
            return;
        }

        if (!$value->isValid()) {
            $fail('O arquivo :attribute não é válido.');
            return;
        }

        // Verificar tamanho do arquivo
        if ($value->getSize() > $this->maxSize) {
            $sizeInKb = round($this->maxSize / 1024);
            $fail("O arquivo :attribute não pode ser maior que {$sizeInKb}KB.");
            return;
        }

        // Detectar MIME type usando nossa implementação
        $mimeType = MimeTypeDetector::detect($value);
        
        // Verificar se é uma imagem
        if (!MimeTypeDetector::isImage($mimeType)) {
            $fail('O arquivo :attribute deve ser uma imagem.');
            return;
        }

        // Verificar extensão permitida
        $extension = strtolower($value->getClientOriginalExtension());
        if (!in_array($extension, $this->allowedMimes)) {
            $allowedExtensions = implode(', ', $this->allowedMimes);
            $fail("O arquivo :attribute deve ter uma das seguintes extensões: {$allowedExtensions}.");
            return;
        }

        // Verificar se o MIME type corresponde à extensão
        $expectedMimes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp'
        ];

        $expectedMime = $expectedMimes[$extension] ?? null;
        if ($expectedMime && $mimeType !== $expectedMime) {
            $fail('O tipo do arquivo :attribute não corresponde à sua extensão.');
            return;
        }
    }
}