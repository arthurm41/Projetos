<div align="center">
<img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white"/>
<img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white"/>
<img src="https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white"/>
<img src="https://img.shields.io/badge/REST_API-009688?style=for-the-badge&logo=fastapi&logoColor=white"/>
<br/><br/>
<h1>📚 SenaiStock</h1>
<p><strong>Sistema de Controle de Estoque de Livros Didáticos — SENAI</strong></p>
<p><em>Uma API RESTful desenvolvida em Laravel para gerenciar entradas e saídas de livros no almoxarifado das unidades de ensino do SENAI, garantindo que nenhuma turma fique sem material.</em></p>

</div>
<br/>
<h2>🎯 Sobre o Projeto</h2>
O SenaiStock nasceu de uma dor real: livros chegando às centenas no almoxarifado, sendo distribuídos aos instrutores sem controle, e o estoque zerando justamente no momento em que uma turma mais precisava.
Este sistema resolve isso de forma direta e confiável — registrando cada entrada vinda da editora e cada saída para as turmas, mantendo o saldo sempre atualizado em tempo real.

Missão: Nunca mais descobrir que o estoque acabou tarde demais.

<br/>
<h2>⚙️ Funcionalidades</h2>
<h3>🔐 1. Autenticação</h3>

Login seguro via token (Laravel Sanctum)
Acesso restrito a Almoxarifes e Coordenadores
Rotas protegidas por middleware de autenticação

<h3>📖 2. Catálogo de Livros</h3>

Cadastro completo de títulos com:

Título do livro
ISBN (identificador único)
Matéria / disciplina relacionada


Listagem, atualização e remoção de títulos

<h3>📦 3. Entrada de Estoque (Abastecimento)</h3>

Registra a chegada de novas remessas da editora
O usuário informa o livro e a quantidade recebida
O sistema soma automaticamente ao saldo atual

<h3>📤 4. Saída de Estoque (Baixa Manual)</h3>

Registra a retirada de livros para as turmas
O usuário informa o livro, a quantidade e o destino (ex: "Turma A — Elétrica")
Regra de negócio crítica: A operação é bloqueada automaticamente se a quantidade solicitada for maior do que o saldo disponível, retornando erro 422 – Estoque Insuficiente

<h3>🔔 5. Monitoramento de Saldo Baixo</h3>

Rota dedicada que lista todos os livros com estoque abaixo do nível mínimo configurado (padrão: 10 unidades)
Funciona como um painel de alertas para o almoxarife saber o que precisa ser reposto antes que acabe

<br/>
<h2>🛠️ Stack Tecnológica</h2>
CamadaTecnologiaBack-EndLaravel (PHP)Banco de DadosMySQLORMEloquent ORMAutenticaçãoLaravel SanctumPadrão de APIRESTful — Respostas em JSONEstilo de CódigoPSR-12 + Clean CodeTestes de RotaInsomnia / Postman
<br/>
<h2>🗄️ Modelagem do Banco de Dados</h2>

books
├── id
├── title
├── isbn (unique)
├── subject
├── current_stock
├── minimum_stock
└── timestamps
stock_movements
├── id
├── book_id (FK → books)
├── type (entry | exit)
├── quantity
├── description
├── user_id (FK → users)
└── timestamps
users
├── id
├── name
├── email
├── password
├── role (almoxarife | coordenador)
└── timestamps

<br/>

<h2>🌐 Rotas da API</h2>

<h3>🔓 Públicas</h3>

| Método | Rota | Descrição |
|---|---|---|
| `POST` | `/api/login` | Autenticação do usuário |

<h3>🔒 Protegidas (requer token)</h3>

| Método | Rota | Descrição |
|---|---|---|
| `POST` | `/api/logout` | Encerra a sessão |
| `GET` | `/api/books` | Lista todos os livros |
| `POST` | `/api/books` | Cadastra um novo livro |
| `GET` | `/api/books/{id}` | Exibe detalhes de um livro |
| `PUT` | `/api/books/{id}` | Atualiza dados de um livro |
| `DELETE` | `/api/books/{id}` | Remove um livro do catálogo |
| `POST` | `/api/stock/entry` | Registra entrada de estoque |
| `POST` | `/api/stock/exit` | Registra saída de estoque |
| `GET` | `/api/stock/low` | Lista livros com estoque baixo |

<br/>

<h2>🚀 Como Executar o Projeto</h2>

<h3>Pré-requisitos</h3>

- PHP >= 8.2
- Composer
- MySQL
- Laravel CLI

<h3>Passo a passo</h3>
bash
# 1. Clone o repositório
git clone https://github.com/seu-usuario/senaistock.git
cd senaistock

# 2. Instale as dependências
composer install

# 3. Configure o ambiente
cp .env.example .env
php artisan key:generate

# 4. Configure o banco de dados no .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=senaistock
DB_USERNAME=root
DB_PASSWORD=sua_senha

# 5. Rode as migrations e seeders
php artisan migrate --seed

# 6. Inicie o servidor
php artisan serve

A API estará disponível em http://localhost:8000/api

<br/>
<h2>📁 Estrutura do Projeto</h2>

senaistock/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── BookController.php
│   │   │   └── StockController.php
│   │   └── Requests/
│   │       ├── StoreBookRequest.php
│   │       ├── StockEntryRequest.php
│   │       └── StockExitRequest.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Book.php
│   │   └── StockMovement.php
│   └── Services/
│       └── StockService.php
├── database/
│   ├── migrations/
│   └── seeders/
├── routes/
│   └── api.php
└── tests/

<br/>
<h2>📋 Exemplos de Requisição</h2>
<h3>Login</h3>
json
POST /api/login
{
  "email": "almoxarife@senai.br",
  "password": "senha123"
}

<h3>Registrar Entrada de Estoque</h3>
json
POST /api/stock/entry
Authorization: Bearer {token}
{
"book_id": 3,
"quantity": 50,
"description": "Remessa de março — Editora Senai"
}

<h3>Registrar Saída de Estoque</h3>
json
POST /api/stock/exit
Authorization: Bearer {token}

{
  "book_id": 3,
  "quantity": 30,
  "description": "Turma A — Eletrotécnica"
}
<h3>Resposta — Estoque Insuficiente</h3>
json
HTTP 422 Unprocessable Entity
{
"message": "Estoque insuficiente.",
"available": 15,
"requested": 30
}

<br/>

<h2>👥 Equipe</h2>

> Projeto desenvolvido como atividade prática do curso técnico — SENAI.

| Nome | Função |
|---|---|
| *(Gabriel e Arthur)* | Back-End Developer |

<br/>

---

<div align="center">


<img src="https://img.shields.io/badge/status-em%20desenvolvimento-yellow?style=flat-square"/>
<img src="https://img.shields.io/badge/vers%C3%A3o-1.0.0-blue?style=flat-square"/>

</div>