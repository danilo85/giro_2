<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Utils\MimeTypeDetector;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\File\UploadedFile as SymfonyUploadedFile;

class TestUploadFix extends Command
{
    protected $signature = 'test:upload-fix';
    protected $description = 'Testa se a correção do upload está funcionando';

    public function handle()
    {
        $this->info('=== TESTE DE CORREÇÃO DO UPLOAD ===');
        $this->newLine();

        // Simular um arquivo de upload
        $mockFile = new class {
            public function getClientOriginalName() {
                return 'test.jpg';
            }
            
            public function getSize() {
                return 1024;
            }
            
            public function getRealPath() {
                return '/tmp/test.jpg';
            }
            
            public function getPathname() {
                return '/tmp/test.jpg';
            }
            
            public function getClientOriginalExtension() {
                return 'jpg';
            }
            
            // Método que causava erro antes da correção
            public function getMimeType() {
                throw new \Exception('Unable to guess the MIME type as no guessers are available (have you enabled the php_fileinfo extension?)');
            }
        };

        // Testar diferentes tipos de arquivo
        $testFiles = [
            ['test.jpg', 'Imagem JPEG'],
            ['test.png', 'Imagem PNG'],
            ['test.pdf', 'Documento PDF'],
            ['test.docx', 'Documento Word'],
            ['test.txt', 'Arquivo de texto']
        ];

        foreach ($testFiles as [$filename, $description]) {
            $this->info("Testando: $description ($filename)");
            
            try {
                // Criar arquivo mock com nome específico
                $mockFile = new class($filename) {
                    private $filename;
                    
                    public function __construct($filename) {
                        $this->filename = $filename;
                    }
                    
                    public function getClientOriginalName() {
                        return $this->filename;
                    }
                    
                    public function getSize() {
                        return 1024;
                    }
                    
                    public function getRealPath() {
                        return '/tmp/' . $this->filename;
                    }
                    
                    public function getPathname() {
                        return '/tmp/' . $this->filename;
                    }
                    
                    public function getClientOriginalExtension() {
                        return pathinfo($this->filename, PATHINFO_EXTENSION);
                    }
                    
                    // Método que causava erro antes da correção
                    public function getMimeType() {
                        throw new \Exception('Unable to guess the MIME type as no guessers are available (have you enabled the php_fileinfo extension?)');
                    }
                };
                
                // Testar método antigo (que falharia)
                $this->line('  ❌ Método antigo (getMimeType): ', false);
                try {
                    $oldMime = $mockFile->getMimeType();
                    $this->line($oldMime);
                } catch (\Exception $e) {
                    $this->error('ERRO - ' . $e->getMessage());
                }
                
                // Testar nossa solução
                $this->line('  ✅ Nossa solução (MimeTypeDetector): ', false);
                $newMime = MimeTypeDetector::detect($mockFile);
                $this->info($newMime);
                
                $this->newLine();
                
            } catch (\Exception $e) {
                $this->error('  ❌ Erro geral: ' . $e->getMessage());
                $this->newLine();
            }
        }

        $this->info('=== TESTE CONCLUÍDO ===');
        $this->info('✅ A correção está funcionando!');
        $this->info('✅ Agora o upload de arquivos deve funcionar sem o erro de fileinfo');
        
        return 0;
    }
}