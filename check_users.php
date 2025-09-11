<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=laravel', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query('SELECT id, name, email, created_at FROM users');
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if(empty($users)) {
        echo "Nenhum usuário encontrado no banco de dados.\n";
    } else {
        echo "Usuários encontrados:\n";
        foreach($users as $user) {
            echo "ID: {$user['id']} | Nome: {$user['name']} | Email: {$user['email']} | Criado em: {$user['created_at']}\n";
        }
    }
} catch(PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage() . "\n";
}
?>