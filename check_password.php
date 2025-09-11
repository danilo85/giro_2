<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=laravel', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->prepare('SELECT id, name, email, password FROM users WHERE email = ?');
    $stmt->execute(['admin@test.com']);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($user) {
        echo "Usuário encontrado:\n";
        echo "ID: {$user['id']}\n";
        echo "Nome: {$user['name']}\n";
        echo "Email: {$user['email']}\n";
        echo "Hash da senha: {$user['password']}\n\n";
        
        // Testar se a senha '123456' funciona
        if(password_verify('123456', $user['password'])) {
            echo "✅ A senha '123456' está CORRETA para este usuário!\n";
        } else {
            echo "❌ A senha '123456' está INCORRETA para este usuário.\n";
            echo "Vou criar uma nova senha...\n";
            
            // Criar nova senha
            $newPassword = password_hash('123456', PASSWORD_DEFAULT);
            $updateStmt = $pdo->prepare('UPDATE users SET password = ? WHERE email = ?');
            $updateStmt->execute([$newPassword, 'admin@test.com']);
            
            echo "✅ Senha atualizada com sucesso! Agora você pode usar:\n";
            echo "Email: admin@test.com\n";
            echo "Senha: 123456\n";
        }
    } else {
        echo "Usuário admin@test.com não encontrado.\n";
    }
} catch(PDOException $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}
?>