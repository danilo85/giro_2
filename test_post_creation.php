<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    // Criar um post de teste
    $post = App\Models\SocialPost::create([
        'user_id' => 1, // Assumindo que existe um usuário com ID 1
        'titulo' => 'Post de Teste',
        'legenda' => 'Esta é uma legenda de teste',
        'texto_final' => 'Texto final de teste',
        'status' => 'rascunho'
    ]);
    
    echo "Post criado com sucesso! ID: " . $post->id . PHP_EOL;
    echo "Título: " . $post->titulo . PHP_EOL;
    echo "Status: " . $post->status . PHP_EOL;
    
} catch (Exception $e) {
    echo "Erro ao criar post: " . $e->getMessage() . PHP_EOL;
    echo "Trace: " . $e->getTraceAsString() . PHP_EOL;
}