# Guia de Deploy para Hostinger

## Problema Identificado
As páginas públicas (orçamento público, recibo público e extrato do cliente) estão retornando erro 404 no servidor da Hostinger, mesmo funcionando localmente.

## Análise Realizada

### ✅ Verificações Concluídas:
1. **Rotas Públicas**: Confirmadas no `routes/web.php`:
   - `/public/orcamento/{token}` → `OrcamentoController@showPublic`
   - `/public/recibo/{token}` → `PagamentoController@showReciboPublico`
   - `/public/extrato/{cliente_id}/{token}` → `ExtratoController@show`

2. **Controllers**: Todos os métodos estão implementados corretamente:
   - `OrcamentoController::showPublic()` - linha 557
   - `PagamentoController::showReciboPublico()` - linha 496
   - `ExtratoController::show()` - linha 17

3. **Middleware**: Não há middleware bloqueando as rotas públicas
4. **CSRF**: Rotas públicas GET não requerem token CSRF

### 🔧 Soluções Implementadas:

## 1. Configuração de Produção (.env.production)

Crie um arquivo `.env` na raiz do projeto na Hostinger com as seguintes configurações:

```env
APP_NAME=Giro
APP_ENV=production
APP_KEY=base64:8K4L5ro4e8c5rAyjR+ks/AYXt64Hz0ABd30sqPA/14s=
APP_DEBUG=false
APP_URL=https://daniloamiguel.com

LOG_CHANNEL=stack
LOG_LEVEL=error

# Configurações do banco de dados da Hostinger
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u574849695_giro
DB_USERNAME=u574849695_giro
DB_PASSWORD=SUA_SENHA_DO_BANCO

# Configurações de sessão para HTTPS
SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax

# Configurações de email da Hostinger
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=noreply@daniloamiguel.com
MAIL_PASSWORD=SUA_SENHA_EMAIL
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@daniloamiguel.com"
MAIL_FROM_NAME="Giro"
```

## 2. Configuração do .htaccess para Hostinger

Substitua o `.htaccess` na raiz do domínio pelo conteúdo do arquivo `.htaccess.production`:

### Principais mudanças:
- Redirecionamento forçado para HTTPS
- Redirecionamento correto para a pasta `public/`
- Configurações de cache e compressão
- Bloqueio de arquivos sensíveis

## 3. Estrutura de Arquivos na Hostinger

```
public_html/
├── .htaccess (usar o .htaccess.production)
├── .env (usar as configurações de produção)
├── app/
├── bootstrap/
├── config/
├── database/
├── public/ (conteúdo do Laravel)
│   ├── index.php
│   ├── .htaccess
│   └── ...
├── resources/
├── routes/
├── storage/
└── vendor/
```

## 4. Comandos para Executar na Hostinger

Após fazer upload dos arquivos:

```bash
# 1. Instalar dependências
composer install --optimize-autoloader --no-dev

# 2. Gerar chave da aplicação (se necessário)
php artisan key:generate

# 3. Limpar e otimizar cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# 4. Otimizar para produção
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Executar migrações (se necessário)
php artisan migrate --force

# 6. Criar link simbólico para storage
php artisan storage:link
```

## 5. Permissões de Pastas

Definir permissões corretas:

```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod 644 .env
```

## 6. Teste das URLs Públicas

Após o deploy, teste as seguintes URLs:

- `https://daniloamiguel.com/public/orcamento/{TOKEN_VALIDO}`
- `https://daniloamiguel.com/public/recibo/{TOKEN_VALIDO}`
- `https://daniloamiguel.com/public/extrato/{CLIENTE_ID}/{TOKEN_VALIDO}`

## 7. Troubleshooting

### Se ainda houver erro 404:

1. **Verificar logs do servidor**:
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Verificar se o mod_rewrite está ativo**:
   - Contatar suporte da Hostinger se necessário

3. **Verificar se a pasta public está acessível**:
   - Testar: `https://daniloamiguel.com/public/`

4. **Verificar configuração do Apache**:
   - Confirmar se `.htaccess` está sendo lido

### Logs importantes:
- `storage/logs/laravel.log` - Logs da aplicação
- Logs do Apache (via painel da Hostinger)

## 8. Checklist Final

- [ ] Arquivo `.env` configurado com dados da Hostinger
- [ ] Arquivo `.htaccess` atualizado na raiz
- [ ] Dependências instaladas com `composer install`
- [ ] Cache otimizado para produção
- [ ] Permissões de pastas configuradas
- [ ] Storage link criado
- [ ] URLs públicas testadas

## Contato

Se o problema persistir após seguir este guia, verificar:
1. Configurações específicas do servidor da Hostinger
2. Logs de erro do Apache
3. Configurações de PHP (versão, módulos)