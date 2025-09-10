-- Adicionar colunas extrato_token à tabela clientes
ALTER TABLE clientes 
ADD COLUMN extrato_token VARCHAR(64) NULL UNIQUE AFTER observacoes,
ADD COLUMN extrato_token_generated_at TIMESTAMP NULL AFTER extrato_token;

-- Verificar se as colunas foram adicionadas
DESCRIBE clientes;