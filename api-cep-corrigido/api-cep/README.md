# API REST — CEP com PHP + MySQL

API para consulta, cadastro e remoção de CEPs, desenvolvida com PHP puro e PDO.

## Estrutura do Projeto

```
api-cep/
├── config/
│   └── database.php   # Conexão PDO com MySQL
├── api/
│   └── cep.php        # Endpoint principal (GET, POST, DELETE, OPTIONS)
├── index.html         # Interface de documentação e teste interativo
├── index.php          # Redireciona para index.html
├── banco.sql          # Script de criação do banco
└── README.md
```

## Pré-requisitos

- PHP 7.4+
- MySQL 5.7+ (ou MariaDB)
- XAMPP / Laragon / WAMP (qualquer servidor local)

## Como rodar

### 1. Banco de dados

Abra o phpMyAdmin (ou MySQL CLI) e execute:

```sql
SOURCE /caminho/para/banco.sql;
```

Ou copie e cole o conteúdo de `banco.sql` direto no phpMyAdmin.

### 2. Servidor local

Coloque a pasta `api-cep/` dentro de:
- **XAMPP** → `C:/xampp/htdocs/`
- **Laragon** → `C:/laragon/www/`

Acesse no navegador: `http://localhost/api-cep/`

### 3. Credenciais

Edite `config/database.php` e ajuste `$user` e `$pass` conforme seu ambiente.

---

## Endpoints

| Método | URL                          | Descrição              |
|--------|------------------------------|------------------------|
| GET    | `/api/cep.php`               | Lista todos os CEPs    |
| GET    | `/api/cep.php?cep=01310-100` | Busca um CEP           |
| POST   | `/api/cep.php`               | Cadastra novo CEP      |
| DELETE | `/api/cep.php?cep=01310-100` | Remove um CEP          |

---

## Exemplos de uso (Postman / Thunder Client / curl)

### GET — listar todos
```
GET http://localhost/api-cep/api/cep.php
```

### GET — buscar por CEP
```
GET http://localhost/api-cep/api/cep.php?cep=01310-100
```

### POST — cadastrar
```
POST http://localhost/api-cep/api/cep.php
Content-Type: application/json

{
  "cep":    "13010-111",
  "rua":    "Rua Treze de Maio",
  "bairro": "Centro",
  "cidade": "Campinas",
  "estado": "SP"
}
```

### DELETE — remover
```
DELETE http://localhost/api-cep/api/cep.php?cep=13010-111
```

---

## Respostas esperadas

**200 OK — CEP encontrado**
```json
{
  "id": 1,
  "cep": "01310-100",
  "rua": "Av. Paulista",
  "bairro": "Bela Vista",
  "cidade": "São Paulo",
  "estado": "SP"
}
```

**200 OK — Lista todos**
```json
[
  { "id": 1, "cep": "01310-100", ... },
  { "id": 2, "cep": "20040-020", ... }
]
```

**201 Created — CEP cadastrado**
```json
{ "mensagem": "CEP cadastrado com sucesso", "id": 4 }
```

**404 Not Found**
```json
{ "erro": "CEP não encontrado" }
```

**400 Bad Request — campos obrigatórios ausentes**
```json
{ "erro": "Campos obrigatórios: cep, rua, cidade, estado" }
```

**409 Conflict — CEP duplicado**
```json
{ "erro": "CEP já cadastrado" }
```

**405 Method Not Allowed**
```json
{ "erro": "Método não permitido" }
```

---

## Testando no VS Code

Instale a extensão **Thunder Client** (alternativa ao Postman direto no editor) e use as rotas acima.

Ou acesse `http://localhost/api-cep/` para usar a interface de teste integrada no browser.
