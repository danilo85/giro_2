<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SocialPost;

// Verificar posts com hashtags
$postsWithHashtags = SocialPost::whereHas('hashtags')->count();
echo "Posts com hashtags: {$postsWithHashtags}\n";

if ($postsWithHashtags > 0) {
    $post = SocialPost::whereHas('hashtags')->with('hashtags')->first();
    echo "Post ID: {$post->id}\n";
    echo "Título: {$post->titulo}\n";
    echo "Hashtags: " . $post->hashtags->pluck('name')->join(', ') . "\n";
    echo "URL de edição: http://localhost:8000/social-posts/{$post->id}/edit\n";
} else {
    echo "Nenhum post com hashtags encontrado.\n";
    
    // Verificar se existem hashtags no banco
    $totalHashtags = \App\Models\Hashtag::count();
    echo "Total de hashtags no banco: {$totalHashtags}\n";
    
    // Verificar se existem posts
    $totalPosts = SocialPost::count();
    echo "Total de posts: {$totalPosts}\n";
}