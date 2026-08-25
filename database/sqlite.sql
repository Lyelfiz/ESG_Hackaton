PRAGMA foreign_keys = ON;

DROP TABLE IF EXISTS trabalhador_cursos;
DROP TABLE IF EXISTS cursos;
DROP TABLE IF EXISTS trabalhadores;
DROP TABLE IF EXISTS usuarios;

CREATE TABLE usuarios (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nome TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    senha TEXT NOT NULL,
    nivel TEXT NOT NULL DEFAULT 'trabalhador' CHECK (nivel IN ('admin', 'trabalhador')),
    ativo INTEGER NOT NULL DEFAULT 1,
    criado_em TEXT DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE trabalhadores (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    usuario_id INTEGER NULL,
    nome TEXT NOT NULL,
    documento TEXT NOT NULL UNIQUE,
    cargo TEXT NOT NULL,
    setor TEXT NOT NULL,
    status TEXT NOT NULL DEFAULT 'ativo',
    criado_em TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
);

CREATE TABLE cursos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nome TEXT NOT NULL,
    descricao TEXT NULL,
    carga_horaria INTEGER NULL,
    validade_meses INTEGER NULL,
    ativo INTEGER NOT NULL DEFAULT 1
);

CREATE TABLE trabalhador_cursos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    trabalhador_id INTEGER NOT NULL,
    curso_id INTEGER NOT NULL,
    status TEXT NOT NULL DEFAULT 'pendente',
    data_conclusao TEXT NULL,
    validade TEXT NULL,
    FOREIGN KEY (trabalhador_id) REFERENCES trabalhadores(id) ON DELETE CASCADE,
    FOREIGN KEY (curso_id) REFERENCES cursos(id) ON DELETE CASCADE
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
