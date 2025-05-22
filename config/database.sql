-- Criação do banco e tabela de despesas
CREATE DATABASE IF NOT EXISTS controle_financeiro;
USE controle_financeiro;

CREATE TABLE IF NOT EXISTS despesas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  valor DECIMAL(10,2) NOT NULL,
  categoria VARCHAR(50) NOT NULL,
  data DATE NOT NULL,
  descricao VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS settings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) UNIQUE NOT NULL,
  value TEXT NOT NULL
);

INSERT IGNORE INTO settings (name, value) VALUES ('cohere_api_key', '');


