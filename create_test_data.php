<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Criar um usuário se não existir
$user = App\Models\User::first();
if (!$user) {
    $user = App\Models\User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);
}

// Criar um post de teste
$post = App\Models\SocialPost::create([
    'user_id' => $user->id,
    'titulo' => 'Teste Modal',
    'conteudo' => 'Post para testar modais',
    'status' => 'rascunho'
]);

// Criar textos do carrossel
$post->carouselTexts()->create([
    'position' => 1,
    'texto' => 'Texto do slide 1'
]);

$post->carouselTexts()->create([
    'position' => 2,
    'texto' => 'Texto do slide 2'
]);

echo "Post criado com ID: " . $post->id . "\n";
echo "URL para testar: http://127.0.0.1:8000/social-posts/{$post->id}/generate-images\n";
