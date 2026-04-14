<div align="center">

<img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white"/>
<img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white"/>
<img src="https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white"/>
<img src="https://img.shields.io/badge/REST_API-009688?style=for-the-badge&logo=fastapi&logoColor=white"/>

<br/><br/>

<h1>📚 SenaiStock</h1>

<p><strong>Sistema de Controle de Estoque de Livros Didáticos — SENAI</strong></p>

<p><em>Uma API RESTful desenvolvida em Laravel para gerenciar entradas e saídas de livros no almoxarifado das unidades de ensino do SENAI, garantindo que nenhuma turma fique sem material.</em></p>

---

</div>

<br/>

<h2>🎯 Sobre o Projeto</h2>

O **SenaiStock** surgiu de um problema silencioso: livros chegavam e saiam em grandes remessas, eram guardados de forma desorganizada e sem controle.  
Este sistema resolve isso de forma direta e confiável — registrando cada **entrada** vinda da editora e cada **saída** para as turmas, mantendo o saldo sempre atualizado em tempo real.

> **Missão:** Nunca mais descobrir que o estoque acabou tarde demais.

<br/>

<h2>⚙️ Funcionalidades</h2>

<h3>🔐 1. Autenticação</h3>

- Login seguro via token (Laravel Sanctum)
- Acesso restrito a **Almoxarifes** e **Coordenadores**
- Rotas protegidas por middleware de autenticação

<h3>📖 2. Catálogo de Livros</h3>

- Cadastro completo de títulos com:
  - **Título** do livro
  - **ISBN** (identificador único)
  - **Matéria** / disciplina relacionada
- Listagem, atualização e remoção de títulos

<h3>📦 3. Entrada de Estoque (Abastecimento)</h3>

- Registra a chegada de novas remessas da editora
- O usuário informa o **livro** e a **quantidade recebida**
- O sistema **soma automaticamente** ao saldo atual

<h3>📤 4. Saída de Estoque (Baixa Manual)</h3>

- Registra a retirada de livros para as turmas
- O usuário informa o livro, a quantidade e o destino
- Operação bloqueada automaticamente se não houver saldo suficiente (`422 – Estoque Insuficiente`)

<h3>🔔 5. Monitoramento de Saldo Baixo</h3>

- Lista livros com estoque abaixo do mínimo configurado
- Funciona como alerta para reposição

<br/>

<h2>🛠️ Stack Tecnológica</h2>

| Camada | Tecnologia |
|---|---|
| Back-End | Laravel (PHP) |
| Banco de Dados | MySQL |
| ORM | Eloquent ORM |
| Autenticação | Laravel Sanctum |
| Padrão de API | RESTful |
| Testes | Insomnia / Postman |

<br/>

<h2>🗄️ Modelagem do Banco de Dados</h2>

**`books`**

| Coluna | Tipo | Descrição |
|---|---|---|
| id | INT | Identificador |
| title | VARCHAR | Título |
| isbn | VARCHAR | ISBN |
| subject | VARCHAR | Matéria |
| current_stock | INT | Estoque |
| minimum_stock | INT | Mínimo |

**`stock_movements`**

| Coluna | Tipo | Descrição |
|---|---|---|
| id | INT | Identificador |
| book_id | INT | Livro |
| user_id | INT | Usuário |
| type | ENUM | entry/exit |
| quantity | INT | Quantidade |
| description | TEXT | Descrição |

**`users`**

| Coluna | Tipo | Descrição |
|---|---|---|
| id | INT | Identificador |
| name | VARCHAR | Nome |
| email | VARCHAR | Email |
| password | VARCHAR | Senha |
| role | ENUM | Perfil |

<br/>

<h2>🌐 Rotas da API</h2>

### 🔓 Públicas

| Método | Rota |
|---|---|
| POST | /api/login |

### 🔒 Protegidas

| Método | Rota |
|---|---|
| POST | /api/logout |
| GET | /api/books |
| POST | /api/books |
| GET | /api/books/{id} |
| PUT | /api/books/{id} |
| DELETE | /api/books/{id} |
| POST | /api/stock/entry |
| POST | /api/stock/exit |
| GET | /api/stock/low |

<br/>

<h2>📌 Organização do Projeto</h2>

### 📋 Levantamento de Requisitos
Etapa inicial onde foram definidas as necessidades do sistema, como controle de estoque, autenticação e regras de negócio.

### 🎨 Prototipagem
Desenvolvimento das telas e fluxos no Figma para visualizar o sistema antes da implementação.

### 🔄 Metodologias Ágeis
Uso de Scrum com organização em tarefas, backlog e acompanhamento por sprints.

### 🗂️ Versionamento
Utilização do Git para controle de versões e trabalho em equipe.

### 📝 Documentação
Registro das rotas, estrutura e funcionamento da API para facilitar manutenção e uso.

<br/>

<h2>🔗 Links do Projeto</h2>

- 🎨 Figma: https://www.figma.com/design/KPWxNFxbknn0oY8n5VHYfa/SenaiStock?node-id=4-17&t=KRryyOwFlSXFIZDr-1  
- 📋 Trello: https://trello.com/invite/b/699f396ecebd97e020ea17ec/ATTIc41c0d194b39adedb1125257fabc90907CF3F411/senaistock-scrum  

<br/>

<h2>👥 Equipe</h2>

| Nome | Função |
|---|---|
| Gabriel | Back-End Developer |
| Arthur | Back-End Developer |

---

<div align="center">

<p>Feito com ❤️ para o SENAI</p>

<img src="https://img.shields.io/badge/status-em%20desenvolvimento-yellow?style=flat-square"/>
<img src="https://img.shields.io/badge/vers%C3%A3o-1.0.0-blue?style=flat-square"/>

</div>
