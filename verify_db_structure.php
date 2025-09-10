<?php

// Verificar a estrutura do banco de dados
$host = '127.0.0.1';
$port = '3306';
$database = 'giro_2';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Conectado ao banco: $database\n\n";
    
    // Verificar se a tabela clientes existe
    $stmt = $pdo->query("SHOW TABLES LIKE 'clientes'");
    if ($stmt->rowCount() > 0) {
        echo "Tabela 'clientes' encontrada.\n\n";
        
        // Mostrar estrutura completa da tabela
        echo "Estrutura da tabela 'clientes':\n";
        echo "=================================\n";
        $stmt = $pdo->query("DESCRIBE clientes");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo sprintf("%-30s %-20s %-10s %-10s\n", 
                $row['Field'], 
                $row['Type'], 
                $row['Null'], 
                $row['Key']
            );
        }
        
        // Verificar especificamente as colunas do extrato
        echo "\n\nVerificando colunas específicas do extrato:\n";
        echo "==========================================\n";
        
        $stmt = $pdo->prepare("SHOW COLUMNS FROM clientes WHERE Field IN ('extrato_token', 'extrato_token_generated_at')");
        $stmt->execute();
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($columns) > 0) {
            foreach ($columns as $column) {
                echo "Coluna encontrada: {$column['Field']} - {$column['Type']}\n";
            }
        } else {
            echo "ERRO: Colunas extrato_token e extrato_token_generated_at NÃO encontradas!\n";
        }
        
        // Verificar se há registros na tabela
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM clientes");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "\nTotal de registros na tabela clientes: {$result['total']}\n";
        
        // Mostrar alguns registros se existirem
        if ($result['total'] > 0) {
            echo "\nPrimeiros 3 registros:\n";
            $stmt = $pdo->query("SELECT id, nome, extrato_token FROM clientes LIMIT 3");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "ID: {$row['id']}, Nome: {$row['nome']}, Token: " . ($row['extrato_token'] ?? 'NULL') . "\n";
            }
        }
        
    } else {
        echo "ERRO: Tabela 'clientes' não encontrada!\n";
    }
    
} catch (PDOException $e) {
    echo "Erro de conexão: " . $e->getMessage() . "\n";
}