DROP TABLE IF EXISTS trabalhador_cursos;
DROP TABLE IF EXISTS cursos;
DROP TABLE IF EXISTS trabalhadores;
DROP TABLE IF EXISTS usuarios;

CREATE TABLE usuarios (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    nivel VARCHAR(30) NOT NULL DEFAULT 'trabalhador',
    ativo SMALLINT NOT NULL DEFAULT 1,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CHECK (nivel IN ('admin', 'trabalhador'))
);

CREATE TABLE trabalhadores (
    id SERIAL PRIMARY KEY,
    usuario_id INT NULL REFERENCES usuarios(id) ON DELETE SET NULL,
    nome VARCHAR(120) NOT NULL,
    documento VARCHAR(40) NOT NULL UNIQUE,
    cargo VARCHAR(100) NOT NULL,
    setor VARCHAR(100) NOT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'ativo',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE cursos (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(140) NOT NULL,
    descricao TEXT NULL,
    carga_horaria INT NULL,
    validade_meses INT NULL,
    ativo SMALLINT NOT NULL DEFAULT 1
);

CREATE TABLE trabalhador_cursos (
    id SERIAL PRIMARY KEY,
    trabalhador_id INT NOT NULL REFERENCES trabalhadores(id) ON DELETE CASCADE,
    curso_id INT NOT NULL REFERENCES cursos(id) ON DELETE CASCADE,
    status VARCHAR(30) NOT NULL DEFAULT 'pendente',
    data_conclusao DATE NULL,
    validade DATE NULL
);

INSERT INTO usuarios (id, nome, email, senha, nivel, ativo) VALUES
(1, 'Administrador', 'admin@teste.com', '$2y$12$u.0Y0PYiO.4o8u7xZf22kO8gRWdtZK/I0u0z7WCuxNgJo0mP3KmuO', 'admin', 1),
(2, 'Joao Trabalhador', 'trabalhador@teste.com', '$2y$12$u.0Y0PYiO.4o8u7xZf22kO8gRWdtZK/I0u0z7WCuxNgJo0mP3KmuO', 'trabalhador', 1);

INSERT INTO trabalhadores (id, usuario_id, nome, documento, cargo, setor, status) VALUES
(1, 2, 'Joao Trabalhador', 'MAT-001', 'Operador de Producao', 'Fabrica', 'ativo'),
(2, NULL, 'Maria Souza', 'MAT-002', 'Tecnica de Seguranca', 'SST', 'ativo');

INSERT INTO cursos (id, nome, descricao, carga_horaria, validade_meses, ativo) VALUES
(1, 'NR-10 Seguranca em Instalacoes Eletricas', 'Treinamento basico de seguranca eletrica.', 40, 24, 1),
(2, 'NR-35 Trabalho em Altura', 'Treinamento para atividades em altura.', 8, 24, 1),
(3, 'Integracao de Seguranca', 'Treinamento inicial obrigatorio.', 4, 12, 1);

INSERT INTO trabalhador_cursos (trabalhador_id, curso_id, status, data_conclusao, validade) VALUES
(1, 1, 'concluido', '2026-01-10', '2028-01-10'),
(1, 2, 'pendente', NULL, NULL),
(1, 3, 'concluido', '2024-03-01', '2025-03-01'),
(2, 3, 'concluido', '2026-05-20', '2027-05-20');

SELECT setval('usuarios_id_seq', (SELECT MAX(id) FROM usuarios));
SELECT setval('trabalhadores_id_seq', (SELECT MAX(id) FROM trabalhadores));
SELECT setval('cursos_id_seq', (SELECT MAX(id) FROM cursos));
