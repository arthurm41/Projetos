📚 SenaiStock API
Sistema de Controle Quantitativo de Livros Didáticos

O SenaiStock é uma API RESTful desenvolvida para solucionar a falta de controle de estoque de livros nas unidades do Senai. O sistema permite que o almoxarifado registre entradas e saídas de materiais, garantindo que nenhum instrutor seja pego de surpresa com o estoque zerado.

🛠️ Tecnologias Utilizadas
Framework: Laravel 11 (PHP 8.2+)

Banco de Dados: MySQL

ORM: Eloquent (Mapeamento Objeto-Relacional)

Padronização: PSR (PHP Standard Recommendations) e Clean Code

Documentação/Testes: Insomnia / Postman

Funcionalidades Principais
1. Autenticação e Segurança
Apenas usuários autenticados com perfis de Almoxarife ou Coordenador podem realizar alterações no saldo.

Tecnologia: Laravel Sanctum ou JWT.

2. Catálogo de Títulos
Gerenciamento completo dos livros disponíveis na unidade.

Campos: Título, ISBN (único) e Matéria.

3. Gestão de Fluxo (Entrada e Saída)
O coração do sistema é o controle do saldo atualizado:

Abastecimento: Adiciona exemplares ao saldo ao receber remessas da editora.

Baixa Manual: Registra a retirada de livros para turmas específicas.

⚠️ Regra de Negócio: O sistema impede a saída se a quantidade solicitada for superior ao saldo disponível (Erro: Estoque Insuficiente).

4. Alerta de Reposição (Monitoramento)
Uma rota estratégica que lista automaticamente todos os títulos que possuem menos de 10 unidades em estoque, facilitando o planejamento de novos pedidos.

Método,Endpoint,Descrição
POST,/login,Autenticação de usuário e geração de token.
GET,/books,Lista todos os livros cadastrados.
POST,/books,Cadastra um novo título no catálogo.
POST,/stock/in,Registra a entrada de novas caixas (Soma saldo).
POST,/stock/out,Registra a retirada para sala de aula (Subtrai saldo).
GET,/stock/low-inventory,Lista livros com saldo abaixo do nível mínimo.

O Gemini disse
Essa é uma excelente iniciativa! O SenaiStock resolve um problema clássico de logística escolar com uma solução elegante de software. Para o seu repositório no GitHub, o ideal é que o README.md atue como o "cartão de visitas" e o manual técnico ao mesmo tempo.

Aqui está uma proposta de documentação limpa, didática e profissional para o seu projeto:

📚 SenaiStock API
Sistema de Controle Quantitativo de Livros Didáticos

O SenaiStock é uma API RESTful desenvolvida para solucionar a falta de controle de estoque de livros nas unidades do Senai. O sistema permite que o almoxarifado registre entradas e saídas de materiais, garantindo que nenhum instrutor seja pego de surpresa com o estoque zerado.

🛠️ Tecnologias Utilizadas
Framework: Laravel 11 (PHP 8.2+)

Banco de Dados: MySQL

ORM: Eloquent (Mapeamento Objeto-Relacional)

Padronização: PSR (PHP Standard Recommendations) e Clean Code

Documentação/Testes: Insomnia / Postman

🚀 Funcionalidades Principais
1. Autenticação e Segurança
Apenas usuários autenticados com perfis de Almoxarife ou Coordenador podem realizar alterações no saldo.

Tecnologia: Laravel Sanctum ou JWT.

2. Catálogo de Títulos
Gerenciamento completo dos livros disponíveis na unidade.

Campos: Título, ISBN (único) e Matéria.

3. Gestão de Fluxo (Entrada e Saída)
O coração do sistema é o controle do saldo atualizado:

Abastecimento: Adiciona exemplares ao saldo ao receber remessas da editora.

Baixa Manual: Registra a retirada de livros para turmas específicas.

⚠️ Regra de Negócio: O sistema impede a saída se a quantidade solicitada for superior ao saldo disponível (Erro: Estoque Insuficiente).

4. Alerta de Reposição (Monitoramento)
Uma rota estratégica que lista automaticamente todos os títulos que possuem menos de 10 unidades em estoque, facilitando o planejamento de novos pedidos.

🛣️ Endpoints da API (Planejamento)
Método	Endpoint	Descrição
POST	/login	Autenticação de usuário e geração de token.
GET	/books	Lista todos os livros cadastrados.
POST	/books	Cadastra um novo título no catálogo.
POST	/stock/in	Registra a entrada de novas caixas (Soma saldo).
POST	/stock/out	Registra a retirada para sala de aula (Subtrai saldo).
GET	/stock/low-inventory	Lista livros com saldo abaixo do nível mínimo.

🏗️ Estrutura de Banco de Dados (Sugestão Eloquent)
O projeto utilizará as seguintes migrações principais:

Users: id, name, email, password, role (admin/staff).

Books: id, title, isbn, subject, current_balance.

Transactions: id, book_id, user_id, type (in/out), quantity, observation.

⚙️ Como executar o projeto
Clone o repositório:

Bash
git clone https://github.com/seu-usuario/senai-stock.git
Instale as dependências:

Bash
composer install
Configure o .env:
Configure suas credenciais do MySQL.

Rode as migrações e o servidor:

Bash
php artisan migrate
php artisan serve

🎯 Versionamento Semântico
Este projeto segue o padrão de versionamento:

v1.0.0: Lançamento inicial com funcionalidades essenciais de CRUD e controle de saldo.

