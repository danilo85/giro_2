<?php

require_once 'vendor/autoload.php';

// Simular o processamento de textos do carrossel
function testCarouselTextProcessing($input) {
    echo "Input original: [" . $input . "]\n";
    
    // Simular o processamento do JavaScript
    $slides = explode('---', $input);
    $cleanedSlides = [];
    
    foreach ($slides as $slide) {
        $cleaned = trim(preg_replace('/^\\n+|\\n+$/', '', trim($slide)));
        if (!empty($cleaned) && $cleaned !== '\n' && trim($cleaned) !== '') {
            $cleanedSlides[] = $cleaned;
        }
    }
    
    echo "Slides processados:\n";
    foreach ($cleanedSlides as $i => $slide) {
        echo "  Slide " . ($i + 1) . ": [" . $slide . "]\n";
    }
    
    return $cleanedSlides;
}

// Testes
echo "=== TESTE 1: Texto normal ===\n";
testCarouselTextProcessing("Primeiro slide---Segundo slide---Terceiro slide");

echo "\n=== TESTE 2: Com \\n no início e fim ===\n";
testCarouselTextProcessing("\nPrimeiro slide\n---\nSegundo slide\n---\nTerceiro slide\n");

echo "\n=== TESTE 3: Slides vazios com \\n ===\n";
testCarouselTextProcessing("Primeiro slide---\n---Terceiro slide");

echo "\n=== TESTE 4: Apenas \\n ===\n";
testCarouselTextProcessing("\n---\n---\n");

echo "\n=== TESTE 5: Misturado ===\n";
testCarouselTextProcessing("\nPrimeiro slide\n---\n---Terceiro slide---\n");

echo "\n=== Teste concluído ===\n";