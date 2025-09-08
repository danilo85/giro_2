-- Adicionar coluna status à tabela pagamentos
ALTER TABLE pagamentos 
ADD COLUMN status ENUM('pendente', 'processando', 'confirmado', 'cancelado') 
DEFAULT 'confirmado' 
AFTER forma_pagamento;

-- Verificar se a coluna foi adicionada
DESCRIBE pagamentos;