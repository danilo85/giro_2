# Instruções para Corrigir Erro 404 no Hostinger

## Problema
A rota `/clientes/{cliente}/check-extract-status` retorna erro 404 no servidor Hostinger, mas funciona localmente.

## Causa Identificada
O problema é causado por cache de rotas e configurações no servidor de produção.

## Solução - Execute os seguintes comandos no servidor Hostinger:

### 1. Acesse o terminal/SSH do seu servidor Hostinger

### 2. Navegue até o diretório do projeto
```bash
cd /path/to/your/laravel/project
```

### 3. Limpe o cache de rotas
```bash
php artisan route:clear
```

### 4. Limpe o cache de configuração
```bash
php artisan config:clear
```

### 5. Limpe todos os caches (opcional, mas recomendado)
```bash
php artisan cache:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
```

### 6. Verifique se a rota está registrada
```bash
php artisan route:list --name=clientes.check-extract-status
```

### 7. Verifique as permissões dos arquivos
```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

## Verificação
Após executar os comandos acima, teste novamente a funcionalidade de gerar link de extrato no painel de clientes.

## Notas Importantes
- A rota está corretamente definida em `routes/web.php` na linha 275
- O método `checkExtractStatus` existe no `ClienteController`
- Os arquivos `.htaccess` estão configurados corretamente
- O problema é específico do cache do servidor de produção

## Se o problema persistir
1. Verifique se o PHP está atualizado no servidor
2. Confirme se todas as dependências do Composer estão instaladas
3. Verifique os logs de erro do servidor em `/storage/logs/laravel.log`