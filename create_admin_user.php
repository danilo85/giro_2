<?php

require_once 'vendor/autoload.php';

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=laravel', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Conectado ao banco de dados com sucesso!\n";
    
    // Verificar se o usuário já existe
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute(['admin@test.com']);
    $existingUser = $stmt->fetch();
    
    if ($existingUser) {
        echo "Usuário admin@test.com já existe! Removendo...\n";
        $deleteStmt = $pdo->prepare("DELETE FROM users WHERE email = ?");
        $deleteStmt->execute(['admin@test.com']);
    }
    
    // Criar hash da senha
    $hashedPassword = password_hash('123456', PASSWORD_DEFAULT);
    
    // Inserir novo usuário administrador
    $stmt = $pdo->prepare("
        INSERT INTO users (
            name, 
            email, 
            password, 
            is_admin, 
            is_active, 
            email_verified_at,
            created_at, 
            updated_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $now = date('Y-m-d H:i:s');
    
    $stmt->execute([
        'Administrador',
        'admin@test.com',
        $hashedPassword,
        1, // is_admin = true
        1, // is_active = true
        $now, // email_verified_at
        $now, // created_at
        $now  // updated_at
    ]);
    
    echo "Usuário administrador criado com sucesso!\n";
    echo "Email: admin@test.com\n";
    echo "Senha: 123456\n";
    echo "Role: Administrador\n";
    
    // Verificar se foi criado corretamente
    $stmt = $pdo->prepare("SELECT id, name, email, is_admin, is_active, created_at FROM users WHERE email = ?");
    $stmt->execute(['admin@test.com']);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        echo "\nDados do usuário criado:\n";
        foreach ($user as $key => $value) {
            echo "- {$key}: {$value}\n";
        }
    }
    
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}