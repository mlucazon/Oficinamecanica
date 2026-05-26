# Deploy no Railway

Este projeto usa `Dockerfile` na raiz e `railway.toml` para configurar o deploy.

## Servicos recomendados

- App: este repositorio.
- MySQL: banco gerenciado do Railway.

## Variaveis do App

Configure no servico da aplicacao:

```env
APP_NAME="AutoTech Pro"
APP_ENV=production
APP_KEY=base64:gere_uma_chave_com_php_artisan_key_generate
APP_DEBUG=false
APP_LOCALE=pt_BR
APP_TIMEZONE=America/Fortaleza

DB_CONNECTION=mysql
DB_URL=${{MySQL.MYSQL_URL}}

LOG_CHANNEL=stderr
QUEUE_CONNECTION=sync
CACHE_DRIVER=file
SESSION_DRIVER=file
FILESYSTEM_DISK=local
RUN_MIGRATIONS=false
```

`APP_URL` pode ficar vazio se o servico tiver um dominio publico no Railway, pois o Laravel usa `RAILWAY_PUBLIC_DOMAIN` como fallback.

## Healthcheck

O healthcheck esta configurado em `/health`, com rota publica explicita em `backend/routes/web.php`.

## Observacoes

- O container escuta diretamente a porta definida por `PORT`, variavel injetada pelo Railway.
- O Vite compila o frontend para `backend/public/build` durante o build Docker.
- As views Blade ficam em `frontend/resources/views` e sao copiadas para `/var/frontend/resources` na imagem.
- Uploads em disco local sao efemeros no Railway. Para persistir anexos/fotos, use volume ou storage externo.
