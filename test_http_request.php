<?php

// Teste HTTP real para verificar autorização
echo "=== TESTE HTTP REAL ===\n\n";

// URLs para testar
$testUrls = [
    'http://localhost:8000/modelos-propostas',
    'http://localhost:8000/modelos-propostas/3',
    'http://localhost:8000/modelos-propostas/8'
];

foreach ($testUrls as $url) {
    echo "\nTestando: {$url}\n";
    
    // Criar contexto HTTP
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => [
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                'Accept-Language: pt-BR,pt;q=0.9,en;q=0.8'
            ],
            'timeout' => 10,
            'follow_location' => false // Para capturar redirecionamentos
        ]
    ]);
    
    // Fazer a requisição
    $response = @file_get_contents($url, false, $context);
    
    if ($response === false) {
        // Verificar se houve erro ou redirecionamento
        $error = error_get_last();
        if (isset($http_response_header)) {
            $statusLine = $http_response_header[0];
            echo "Status: {$statusLine}\n";
            
            if (strpos($statusLine, '302') !== false || strpos($statusLine, '301') !== false) {
                echo "🔄 Redirecionamento detectado\n";
                // Procurar header Location
                foreach ($http_response_header as $header) {
                    if (stripos($header, 'location:') === 0) {
                        echo "Redirecionando para: " . trim(substr($header, 9)) . "\n";
                        break;
                    }
                }
            } elseif (strpos($statusLine, '403') !== false) {
                echo "❌ Erro 403 - Forbidden\n";
            } elseif (strpos($statusLine, '401') !== false) {
                echo "❌ Erro 401 - Unauthorized\n";
            } else {
                echo "❌ Erro: {$statusLine}\n";
            }
        } else {
            echo "❌ Erro na requisição: " . ($error['message'] ?? 'Erro desconhecido') . "\n";
        }
    } else {
        // Sucesso
        if (isset($http_response_header)) {
            $statusLine = $http_response_header[0];
            echo "Status: {$statusLine}\n";
        }
        
        echo "✅ Sucesso\n";
        
        // Verificar se há conteúdo esperado
        if (strpos($response, 'Modelos de Proposta') !== false || 
            strpos($response, 'modelo') !== false ||
            strpos($response, 'proposta') !== false) {
            echo "✅ Conteúdo parece correto\n";
        } else {
            echo "⚠️ Conteúdo inesperado\n";
            // Mostrar início da resposta
            $preview = substr(strip_tags($response), 0, 200);
            echo "Preview: {$preview}...\n";
        }
    }
}

echo "\n=== CONCLUSÃO DO TESTE HTTP ===\n";
echo "Se todas as URLs retornaram 302 (redirecionamento), o problema é autenticação\n";
echo "Se retornaram 403, o problema é autorização\n";
echo "Se retornaram 200, as permissões estão funcionando via HTTP\n";
echo "\n💡 Próximo passo: Verificar logs do Laravel após este teste\n";