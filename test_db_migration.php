<?php

// Configurações do banco de dados
$host = '127.0.0.1';
$port = '3306';
$database = 'giro_2';
$username = 'root';
$password = '';

try {
    // Conectar ao banco de dados
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Conexão com o banco de dados estabelecida com sucesso!\n";
    
    // Verificar se as colunas já existem
    $stmt = $pdo->prepare("SHOW COLUMNS FROM clientes LIKE 'extrato_token'");
    $stmt->execute();
    $tokenColumnExists = $stmt->rowCount() > 0;
    
    $stmt = $pdo->prepare("SHOW COLUMNS FROM clientes LIKE 'extrato_token_generated_at'");
    $stmt->execute();
    $tokenDateColumnExists = $stmt->rowCount() > 0;
    
    if ($tokenColumnExists && $tokenDateColumnExists) {
        echo "As colunas extrato_token e extrato_token_generated_at já existem na tabela clientes.\n";
    } else {
        echo "Adicionando colunas extrato_token à tabela clientes...\n";
        
        // Adicionar as colunas
        if (!$tokenColumnExists) {
            $pdo->exec("ALTER TABLE clientes ADD COLUMN extrato_token VARCHAR(64) NULL UNIQUE");
            echo "Coluna extrato_token adicionada com sucesso.\n";
        }
        
        if (!$tokenDateColumnExists) {
            $pdo->exec("ALTER TABLE clientes ADD COLUMN extrato_token_generated_at TIMESTAMP NULL");
            echo "Coluna extrato_token_generated_at adicionada com sucesso.\n";
        }
    }
    
    // Verificar a estrutura da tabela
    echo "\nEstrutura atual da tabela clientes:\n";
    $stmt = $pdo->query("DESCRIBE clientes");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "{$row['Field']} - {$row['Type']} - {$row['Null']} - {$row['Key']}\n";
    }
    
    echo "\nMigration executada com sucesso!\n";
    
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}