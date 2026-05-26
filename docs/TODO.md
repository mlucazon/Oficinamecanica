# TODO - Correcoes sem quebrar o projeto

- [ ] Ajustar `VeiculoController@store` para orientar melhor quando o usuario nao tiver perfil em `clientes`.
  - Ideia: se `user->cliente` for null, redirecionar para uma tela de cadastro/complemento de perfil ou retornar erro claro.
- [ ] Criar, se necessario, uma rota simples para o cliente logado completar CPF/telefone.
- [ ] Testar manualmente: login como usuario `role=cliente` e envio de `POST /veiculos`.
