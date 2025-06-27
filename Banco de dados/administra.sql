CREATE DATABASE sistemaFinanceiro;
USE sistemaFinanceiro;
-- Usuários
CREATE TABLE Usuario (
	id_usuario INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	nome_usuario VARCHAR(90) NOT NULL,
	email_usuario VARCHAR(90) NOT NULL UNIQUE,
	senha_usuario VARCHAR(245)
);
-- Categorias
CREATE TABLE Categoria (
	id_categoria INT AUTO_INCREMENT PRIMARY KEY,
	nome_categoria VARCHAR(45) NOT NULL,
	fk_usuario INT NOT NULL,
	FOREIGN KEY (fk_usuario) REFERENCES Usuario(id_usuario)  ON DELETE CASCADE ON UPDATE CASCADE
);
-- Despesas
CREATE TABLE Despesa (
	id_despesa INT AUTO_INCREMENT PRIMARY KEY,
    	nome_despesa VARCHAR(50) NULL,
	valor_despesa DECIMAL(10,2) CHECK(valor_despesa > 0),
	data_despesa DATE NOT NULL,
	fk_dependente int,
	fk_usuario INT NOT NULL,
	fk_categoria INT NOT NULL,
	FOREIGN KEY (fk_dependente) REFERENCES Dependente(id_dependente) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (fk_usuario) REFERENCES Usuario(id_usuario) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (fk_categoria) REFERENCES Categoria(id_categoria) ON DELETE CASCADE ON UPDATE CASCADE
);
-- Dívidas
CREATE TABLE Divida (
	id_divida INT AUTO_INCREMENT PRIMARY KEY,
	nome_divida VARCHAR(50) NOT NULL,
	valor_divida DECIMAL(10,2) CHECK(valor_divida > 0),
	data_vencimento DATE NOT NULL,
	fk_usuario INT NOT NULL,
        fk_categoria INT NOT NULL,
	FOREIGN KEY (fk_usuario) REFERENCES Usuario(id_usuario)  ON DELETE CASCADE ON UPDATE CASCADE,
    	FOREIGN KEY (fk_categoria) REFERENCES Categoria(id_categoria) ON DELETE CASCADE ON UPDATE CASCADE
);
-- Rendas
CREATE TABLE Renda (
	id_renda INT AUTO_INCREMENT PRIMARY KEY,
	fonte VARCHAR(45),
	valor_renda DECIMAL(10,2) CHECK(valor_renda >= 0),
	data_renda DATE NOT NULL,
	fk_usuario INT NOT NULL,
	FOREIGN KEY (fk_usuario) REFERENCES Usuario(id_usuario)  ON DELETE CASCADE ON UPDATE CASCADE
);
-- Poupança
CREATE TABLE Poupanca (
	id_poupanca INT AUTO_INCREMENT PRIMARY KEY,
	objetivo VARCHAR(50),
	valor_atual DECIMAL(10,2) CHECK(valor_atual >= 0),
	valor_meta DECIMAL(10,2) CHECK(valor_meta > 0),
	fk_usuario INT NOT NULL,
	FOREIGN KEY (fk_usuario) REFERENCES Usuario(id_usuario)  ON DELETE CASCADE ON UPDATE CASCADE
);
-- Dependentes
CREATE TABLE Dependente (
	id_dependente INT AUTO_INCREMENT PRIMARY KEY,
	nome_dependente VARCHAR(50) NOT NULL,
	relacao VARCHAR(45),
	fk_usuario INT NOT NULL,
	FOREIGN KEY (fk_usuario) REFERENCES Usuario(id_usuario) ON DELETE CASCADE ON UPDATE CASCADE
);
