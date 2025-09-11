<?php

require_once 'vendor/autoload.php';

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=laravel', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Conectado ao banco de dados com sucesso!\n";
    
    // Verificar estrutura da tabela users
    $stmt = $pdo->query("DESCRIBE users");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "\nEstrutura da tabela users:\n";
    foreach ($columns as $column) {
        echo "- {$column['Field']}: {$column['Type']} (Default: {$column['Default']})\n";
    }
    
    // Verificar dados do usuário admin@test.com
    $stmt = $pdo->prepare("SELECT id, name, email, is_active, is_online, created_at FROM users WHERE email = ?");
    $stmt->execute(['admin@test.com']);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        echo "\nDados do usuário admin@test.com:\n";
        foreach ($user as $key => $value) {
            echo "- {$key}: {$value}\n";
        }
    } else {
        echo "\nUsuário admin@test.com não encontrado!\n";
    }
    
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}