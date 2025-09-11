<?php

require_once 'vendor/autoload.php';

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=laravel', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Conectado ao banco de dados com sucesso!\n";
    
    // Simular o processo de login
    $email = 'admin@test.com';
    $password = '123456';
    
    echo "\nTestando login com:\n";
    echo "Email: {$email}\n";
    echo "Senha: {$password}\n\n";
    
    // Buscar usuário
    $stmt = $pdo->prepare("SELECT id, name, email, password, is_active FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        echo "❌ Usuário não encontrado!\n";
        exit;
    }
    
    echo "✅ Usuário encontrado: {$user['name']}\n";
    
    // Verificar senha
    if (password_verify($password, $user['password'])) {
        echo "✅ Senha está correta!\n";
    } else {
        echo "❌ Senha está incorreta!\n";
        echo "Hash no banco: {$user['password']}\n";
        echo "Hash da senha '123456': " . password_hash('123456', PASSWORD_DEFAULT) . "\n";
    }
    
    // Verificar se está ativo
    if ($user['is_active']) {
        echo "✅ Usuário está ativo!\n";
    } else {
        echo "❌ Usuário está inativo!\n";
    }
    
    echo "\n=== RESUMO ===\n";
    if ($user && password_verify($password, $user['password']) && $user['is_active']) {
        echo "✅ Login deveria funcionar perfeitamente!\n";
        echo "\nCredenciais válidas:\n";
        echo "Email: admin@test.com\n";
        echo "Senha: 123456\n";
    } else {
        echo "❌ Há algum problema com o login!\n";
    }
    
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}