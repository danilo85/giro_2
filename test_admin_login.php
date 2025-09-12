<?php

require_once 'vendor/autoload.php';

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=laravel', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== TESTE DE LOGIN DO ADMINISTRADOR ===\n\n";
    
    $email = 'admin@test.com';
    $password = '123456';
    
    // Buscar usuário pelo email
    $stmt = $pdo->prepare("SELECT id, name, email, password, is_admin, is_active FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        echo "❌ ERRO: Usuário não encontrado!\n";
        exit(1);
    }
    
    echo "✅ Usuário encontrado:\n";
    echo "   - ID: {$user['id']}\n";
    echo "   - Nome: {$user['name']}\n";
    echo "   - Email: {$user['email']}\n";
    echo "   - Admin: " . ($user['is_admin'] ? 'Sim' : 'Não') . "\n";
    echo "   - Ativo: " . ($user['is_active'] ? 'Sim' : 'Não') . "\n\n";
    
    // Verificar senha
    if (password_verify($password, $user['password'])) {
        echo "✅ SENHA CORRETA!\n";
        echo "✅ LOGIN REALIZADO COM SUCESSO!\n\n";
        
        // Atualizar último login
        $updateStmt = $pdo->prepare("
            UPDATE users 
            SET last_login_at = ?, last_login_ip = ?, is_online = 1, last_activity_at = ?
            WHERE id = ?
        ");
        
        $now = date('Y-m-d H:i:s');
        $updateStmt->execute([$now, '127.0.0.1', $now, $user['id']]);
        
        echo "✅ Dados de login atualizados!\n";
        
        // Verificar atualização
        $checkStmt = $pdo->prepare("SELECT last_login_at, last_login_ip, is_online FROM users WHERE id = ?");
        $checkStmt->execute([$user['id']]);
        $updated = $checkStmt->fetch(PDO::FETCH_ASSOC);
        
        echo "   - Último login: {$updated['last_login_at']}\n";
        echo "   - IP do login: {$updated['last_login_ip']}\n";
        echo "   - Online: " . ($updated['is_online'] ? 'Sim' : 'Não') . "\n";
        
    } else {
        echo "❌ ERRO: Senha incorreta!\n";
        exit(1);
    }
    
    echo "\n=== TESTE CONCLUÍDO COM SUCESSO ===\n";
    echo "\nCredenciais para login:\n";
    echo "Email: admin@test.com\n";
    echo "Senha: 123456\n";
    
} catch (PDOException $e) {
    echo "❌ ERRO: " . $e->getMessage() . "\n";
    exit(1);
}