# Oficina Mecanica

Sistema Laravel para gestao de oficina mecanica, organizado em duas partes:

- `backend/`: aplicacao Laravel, rotas, controllers, models, banco, Docker, testes e `public/`.
- `frontend/`: views Blade e fontes de CSS/JavaScript usados pela interface.

## Stack

- PHP 8.2
- Laravel 12
- MySQL/MariaDB
- Blade
- Vite
- Tailwind CSS
- Docker/Nginx/Supervisor para deploy

## Desenvolvimento

Instale as dependencias do backend:

```bash
cd backend
composer install
```

Instale as dependencias do frontend:

```bash
cd ../frontend
npm install
```

Prepare a aplicacao:

```bash
cd ../backend
php artisan key:generate
php artisan migrate --seed
```

Rode tudo em modo desenvolvimento:

```bash
composer run dev
```

Compile os assets:

```bash
cd ../frontend
npm run build
```

## Observacoes

- `backend/vendor/` e `frontend/node_modules/` sao dependencias locais e nao devem ser versionadas.
- O Laravel busca os templates Blade em `frontend/resources/views`.
- O Vite publica o build em `backend/public/build`.
- As instrucoes de deploy no Railway estao em `docs/RAILWAY.md`.
- Pendencias tecnicas estao em `docs/TODO.md`.
