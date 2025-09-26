<?php

// Teste simples para verificar o comportamento do expectsJson()
require_once 'vendor/autoload.php';

echo "=== TESTE EXPECTS JSON ===\n\n";

// Teste 1: Requisição sem headers AJAX
$request1 = new \Illuminate\Http\Request();
echo "Teste 1 - Requisição normal:\n";
echo "expectsJson(): " . ($request1->expectsJson() ? 'true' : 'false') . "\n";
echo "Accept header: " . ($request1->header('Accept') ?: 'null') . "\n";
echo "X-Requested-With: " . ($request1->header('X-Requested-With') ?: 'null') . "\n\n";

// Teste 2: Requisição com X-Requested-With
$request2 = new \Illuminate\Http\Request();
$request2->headers->set('X-Requested-With', 'XMLHttpRequest');
echo "Teste 2 - Com X-Requested-With:\n";
echo "expectsJson(): " . ($request2->expectsJson() ? 'true' : 'false') . "\n";
echo "Accept header: " . ($request2->header('Accept') ?: 'null') . "\n";
echo "X-Requested-With: " . ($request2->header('X-Requested-With') ?: 'null') . "\n\n";

// Teste 3: Requisição com Accept application/json
$request3 = new \Illuminate\Http\Request();
$request3->headers->set('Accept', 'application/json');
echo "Teste 3 - Com Accept application/json:\n";
echo "expectsJson(): " . ($request3->expectsJson() ? 'true' : 'false') . "\n";
echo "Accept header: " . ($request3->header('Accept') ?: 'null') . "\n";
echo "X-Requested-With: " . ($request3->header('X-Requested-With') ?: 'null') . "\n\n";

// Teste 4: Requisição com ambos os headers (como AJAX real)
$request4 = new \Illuminate\Http\Request();
$request4->headers->set('X-Requested-With', 'XMLHttpRequest');
$request4->headers->set('Accept', 'application/json, text/javascript, */*; q=0.01');
echo "Teste 4 - AJAX completo:\n";
echo "expectsJson(): " . ($request4->expectsJson() ? 'true' : 'false') . "\n";
echo "Accept header: " . ($request4->header('Accept') ?: 'null') . "\n";
echo "X-Requested-With: " . ($request4->header('X-Requested-With') ?: 'null') . "\n\n";

// Teste 5: Verificar se há diferença com wantsJson()
echo "=== COMPARAÇÃO COM wantsJson() ===\n";
echo "Teste 1 - wantsJson(): " . ($request1->wantsJson() ? 'true' : 'false') . "\n";
echo "Teste 2 - wantsJson(): " . ($request2->wantsJson() ? 'true' : 'false') . "\n";
echo "Teste 3 - wantsJson(): " . ($request3->wantsJson() ? 'true' : 'false') . "\n";
echo "Teste 4 - wantsJson(): " . ($request4->wantsJson() ? 'true' : 'false') . "\n";

echo "\n=== TESTE CONCLUÍDO ===\n";