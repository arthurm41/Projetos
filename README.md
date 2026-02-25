# Projetos

Sistema SenaiStock
1. Objetivo do Versionamento

O versionamento do projeto SenaiStock tem como finalidade controlar as alterações no código-fonte ao longo do desenvolvimento, permitindo:

Histórico de mudanças

Trabalho colaborativo em equipe

Controle de versões estáveis

Facilidade de manutenção

Possibilidade de rollback em caso de erros

Será utilizado o Git como sistema de controle de versão e o GitHub como repositório remoto.

2. Ferramentas Utilizadas

Git (controle de versão local)

GitHub (armazenamento remoto)

GitHub Desktop ou Terminal (opcional)

Laravel (framework do projeto)

3. Estratégia de Versionamento

Será adotado o padrão Semantic Versioning (SemVer):

Formato:

MAJOR.MINOR.PATCH

Exemplo:

1.0.0

Significados:

MAJOR → Mudanças grandes ou incompatíveis

MINOR → Novas funcionalidades compatíveis

PATCH → Correções de bugs

4. Estrutura de Branches

O projeto seguirá uma estratégia baseada no Git Flow simplificado.

Branches principais
main

Contém a versão estável do sistema.

Apenas código pronto para produção.

develop

Contém a versão em desenvolvimento.

Integra funcionalidades antes de ir para main.

Branches auxiliares
feature/*

Desenvolvimento de novas funcionalidades.

Exemplos:

feature/autenticacao
feature/cadastro-livros
feature/controle-estoque
feature/monitoramento-estoque
bugfix/*

Correções de erros identificados.

Exemplo:

bugfix/erro-validacao-estoque
hotfix/*

Correções urgentes em produção.

Exemplo:

hotfix/correcao-login
5. Fluxo de Trabalho
Passo a Passo

Clonar repositório:

git clone https://github.com/seu-usuario/senaistock.git

Criar nova branch de funcionalidade:

git checkout -b feature/nome-da-funcionalidade

Desenvolver código.

Adicionar alterações:

git add .

Criar commit:

git commit -m "feat: adiciona cadastro de livros"

Enviar para o GitHub:

git push origin feature/nome-da-funcionalidade

Abrir Pull Request para branch develop.

6. Padrão de Commits (Conventional Commits)

Será utilizado o padrão Conventional Commits para manter organização.

Formato:

tipo: descrição

Tipos:

feat → Nova funcionalidade

fix → Correção de bug

docs → Documentação

style → Formatação

refactor → Refatoração

test → Testes

chore → Tarefas internas

Exemplos de Commits
feat: cria sistema de autenticação
feat: adiciona cadastro de livros
feat: implementa entrada de estoque
feat: implementa saída de estoque
fix: corrige validação de estoque insuficiente
docs: adiciona documentação da API
refactor: melhora estrutura dos controllers
7. Versionamento Inicial do Projeto
Versão 0.1.0 — Configuração Inicial

Criação do projeto Laravel

Configuração do banco MySQL

Estrutura básica da API

Configuração do Git

Versão 0.2.0 — Autenticação

Sistema de login

Proteção de rotas

Tokens de autenticação

Versão 0.3.0 — Cadastro de Livros

CRUD de livros

Model e Migration

Versão 0.4.0 — Controle de Estoque

Entrada de livros

Saída de livros

Validação de saldo

Versão 0.5.0 — Monitoramento

Listagem de estoque baixo

Consulta de saldo

Versão 1.0.0 — Primeira Versão Estável

Sistema completo funcionando

Testes realizados

Documentação finalizada

8. Tags de Versão

As versões estáveis serão marcadas com tags no Git.

Exemplo:

git tag -a v1.0.0 -m "Versão estável inicial"
git push origin v1.0.0
9. Boas Práticas de Versionamento

Commits pequenos e frequentes

Mensagens claras e descritivas

Não enviar código quebrado para main

Sempre usar Pull Request

Revisão de código antes do merge

Atualizar branch antes de enviar alterações

10. Estrutura do Repositório
senaistock/
│── app/
│── routes/
│── database/
│── config/
│── tests/
│── docs/
│── README.md
│── .env.example
│── composer.json
11. README do Projeto (Resumo)

O repositório deverá conter:

Descrição do sistema

Tecnologias utilizadas

Como instalar

Como executar

Rotas da API

Autores

12. Controle de Entregas (Milestones)

Milestones sugeridas no GitHub:

Milestone 1 — Estrutura inicial

Milestone 2 — Autenticação

Milestone 3 — Cadastro de livros

Milestone 4 — Controle de estoque

Milestone 5 — Monitoramento

Milestone 6 — Versão final

13. Responsabilidades da Equipe

Cada integrante deverá:

Criar branch própria

Realizar commits organizados

Documentar funcionalidades

Participar das revisões de código

14. Conclusão

O versionamento utilizando Git e GitHub garantirá organização, rastreabilidade e qualidade no desenvolvimento do SenaiStock, permitindo evolução segura do sistema e colaboração eficiente entre os membros da equipe.