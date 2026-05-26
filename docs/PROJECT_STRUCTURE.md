# Organizacao do Projeto

O projeto agora esta dividido em duas partes principais: `backend/` e `frontend/`.

## Raiz do repositorio

- `backend/`: aplicacao Laravel.
- `frontend/`: camada de interface.
- `Dockerfile`: build de producao usando as duas partes.
- `.dockerignore`, `.gitignore`, `.editorconfig`, `.gitattributes`: configuracoes do repositorio.
- `docs/`: documentacao e tarefas internas.

## Backend

- `backend/app/`: codigo PHP da aplicacao.
- `backend/routes/`: rotas Laravel.
- `backend/config/`: configuracoes Laravel.
- `backend/database/`: migrations, seeders e SQL de apoio.
- `backend/public/`: entrada publica, assets servidos e build do Vite.
- `backend/storage/`: arquivos gerados em runtime.
- `backend/tests/`: testes.
- `backend/docker/`: arquivos usados pela imagem Docker.
- `backend/composer.json`: dependencias e scripts do Laravel.

## Frontend

- `frontend/resources/views/`: templates Blade.
- `frontend/resources/css/`: CSS de entrada do Vite.
- `frontend/resources/js/`: JavaScript de entrada do Vite.
- `frontend/package.json`: dependencias e scripts do Vite.
- `frontend/vite.config.js`: compila assets para `backend/public/build`.

## Conexoes entre frontend e backend

- O Laravel le as views em `frontend/resources/views` por meio de `backend/config/view.php`.
- O Vite roda a partir de `frontend/` e publica o build em `backend/public/build`.
- Os assets estaticos servidos diretamente continuam em `backend/public`.
- O script `composer run dev`, executado em `backend/`, sobe Laravel, fila, logs e Vite.
