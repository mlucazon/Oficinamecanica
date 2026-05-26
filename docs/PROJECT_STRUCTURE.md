# Organizacao do Projeto

Este projeto segue a estrutura padrao de uma aplicacao Laravel. As pastas principais devem permanecer na raiz porque o framework, o Composer, o Vite e o Docker esperam esses caminhos.

## Ficam na raiz

- `artisan`
- `composer.json` e `composer.lock`
- `package.json` e `package-lock.json`
- `phpunit.xml`
- `vite.config.js`
- `Dockerfile`
- arquivos de ambiente e configuracao, como `.env.example`, `.gitignore`, `.editorconfig`

## Pastas da aplicacao

- `app/`: codigo PHP da aplicacao.
- `config/`: configuracoes do Laravel.
- `database/`: migrations, seeders e SQL de apoio.
- `public/`: entrada publica da aplicacao.
- `resources/`: Blade, CSS e JavaScript.
- `routes/`: definicao de rotas.
- `storage/`: arquivos gerados em runtime.
- `tests/`: testes.

## Pastas de apoio

- `docs/`: documentacao e tarefas internas.
- `database/sql/`: scripts SQL manuais.
- `docker/`: arquivos usados pela imagem Docker.

## Artefatos removidos da raiz

- `repomix-output.xml`: saida gerada por ferramenta.
- `test`: arquivo solto sem uso no projeto.
- `.phpunit.result.cache`: cache gerado pelos testes.
