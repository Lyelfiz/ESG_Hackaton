# Sistema de Controle de Treinamento e Seguranca

Base em PHP puro para um sistema simples de controle de treinamento e seguranca do trabalhador.

Nao usa framework, Composer ou Bootstrap. A ideia e servir como ponto de partida para evoluir manualmente.

## Logins de teste

Depois de importar o SQL, use:

- Admin: `admin@teste.com` / `123456`
- Trabalhador: `trabalhador@teste.com` / `123456`

As senhas do banco foram geradas com `password_hash` e sao verificadas com `password_verify`.

## Estrutura

```text
/config
  database.php
  database.example.php
/auth
  login.php
  logout.php
  check_auth.php
  check_role.php
/admin
  dashboard.php
  trabalhadores.php
  perfil_trabalhador.php
/trabalhador
  dashboard.php
  meus_cursos.php
/includes
  header.php
  footer.php
  navbar.php
  functions.php
/assets
  /css/style.css
  /js/main.js
/database
  mysql.sql
  postgresql.sql
  sqlite.sql
index.php
```

## Como o login funciona

O arquivo `auth/login.php` recebe email e senha, consulta a tabela `usuarios` por PDO e valida a senha com `password_verify`.

Quando o login e aprovado, a sessao `$_SESSION['usuario']` recebe os dados principais do usuario:

- `id`
- `nome`
- `email`
- `nivel`

Depois disso, o usuario e redirecionado por nivel:

- `admin` vai para `admin/dashboard.php`
- `trabalhador` vai para `trabalhador/dashboard.php`

## Protecao de paginas

Use este arquivo em paginas que precisam de login:

```php
require_once __DIR__ . '/../auth/check_auth.php';
```

Use este arquivo em paginas que precisam de um nivel especifico:

```php
require_once __DIR__ . '/../auth/check_role.php';
require_role('admin');
```

Ou:

```php
require_role('trabalhador');
```

Para criar novos niveis, altere a coluna `nivel` da tabela `usuarios`, os checks dos arquivos SQL e as regras em `auth/check_role.php` e nos redirecionamentos de `auth/login.php`.

## Trocar o tipo de banco

Abra `config/database.php` e altere:

```php
$databaseType = 'mysql';
```

Opcoes:

- `mysql`
- `pgsql`
- `sqlite`

No mesmo arquivo ficam os dados de host, porta, usuario, senha e nome do banco.

## Rodar no XAMPP

1. Coloque a pasta do projeto dentro de `htdocs`.
2. Inicie Apache e MySQL no painel do XAMPP.
3. Acesse o phpMyAdmin.
4. Importe o arquivo `database/mysql.sql`.
5. Confira se `config/database.php` esta com:

```php
$databaseType = 'mysql';
```

6. Acesse pelo navegador, por exemplo:

```text
http://localhost/pamela
```

## Rodar no Laragon

1. Coloque a pasta do projeto dentro de `www`.
2. Inicie Apache/Nginx e MySQL no Laragon.
3. Crie ou importe o banco pelo HeidiSQL, phpMyAdmin ou terminal.
4. Importe o arquivo `database/mysql.sql`.
5. Confira se `config/database.php` esta usando `mysql`.
6. Acesse pelo dominio local do Laragon ou por:

```text
http://localhost/pamela
```

Dependendo da configuracao do Laragon, tambem pode funcionar como:

```text
http://pamela.test
```

## Rodar com SQLite

1. Altere `config/database.php`:

```php
$databaseType = 'sqlite';
```

2. Crie o arquivo do banco a partir do SQL:

```bash
sqlite3 database/database.sqlite < database/sqlite.sql
```

3. Inicie um servidor PHP local:

```bash
php -S localhost:8000
```

4. Acesse:

```text
http://localhost:8000
```

## Rodar com PostgreSQL

1. Crie o banco `treinamento_seguranca`.
2. Importe `database/postgresql.sql`.
3. Altere `config/database.php`:

```php
$databaseType = 'pgsql';
```

4. Ajuste usuario e senha conforme sua instalacao local.

## Proximos passos sugeridos

- Criar telas de cadastro e edicao.
- Adicionar validacoes mais completas.
- Criar filtros por status, setor e validade.
- Registrar historico de renovacao dos treinamentos.
- Melhorar mensagens de erro e logs internos.
