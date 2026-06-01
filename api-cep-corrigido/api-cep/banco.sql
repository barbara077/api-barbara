-- ============================================
-- Script de criação do banco de dados
-- API REST - CEP
-- ============================================

CREATE DATABASE IF NOT EXISTS db_cep
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE db_cep;

CREATE TABLE IF NOT EXISTS cep (
    id      INT          AUTO_INCREMENT PRIMARY KEY,
    cep     VARCHAR(9)   NOT NULL UNIQUE,
    rua     VARCHAR(150) NOT NULL,
    bairro  VARCHAR(100) DEFAULT '',
    cidade  VARCHAR(100) NOT NULL,
    estado  CHAR(2)      NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dados de exemplo
INSERT INTO cep (cep, rua, bairro, cidade, estado) VALUES
    ('01310-100', 'Av. Paulista',    'Bela Vista', 'São Paulo',      'SP'),
    ('20040-020', 'Av. Rio Branco',  'Centro',     'Rio de Janeiro', 'RJ'),
    ('30112-010', 'Av. Afonso Pena', 'Centro',     'Belo Horizonte', 'MG');
