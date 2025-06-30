CREATE DATABASE sistemaFinanceiro;
USE sistemaFinanceiro;

-- Usuários
CREATE TABLE Usuario (
	id_usuario INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	nome_usuario VARCHAR(90) NOT NULL,
	email_usuario VARCHAR(90) NOT NULL UNIQUE,
	senha_usuario VARCHAR(245)
);
-- Tipo de pagamento das despesas e dividas
CREATE TABLE TipoPagamento(
	id_pagamento INT AUTO_INCREMENT PRIMARY KEY,
	nome_pagamento VARCHAR(45) NOT NULL UNIQUE,
	fk_usuario INT,
	FOREIGN KEY (fk_usuario) REFERENCES Usuario(id_usuario)  ON DELETE CASCADE ON UPDATE CASCADE

);
-- Como o usuário ganhou seu dinheiro
CREATE TABLE FonteRenda(
	id_renda INT AUTO_INCREMENT PRIMARY KEY,
	fonte_da_renda VARCHAR(45) NOT NULL,
	fk_usuario INT NOT NULL,
	UNIQUE(fonte_da_renda),
	FOREIGN KEY (fk_usuario) REFERENCES Usuario(id_usuario)  ON DELETE CASCADE ON UPDATE CASCADE
);
-- Categorias das despesas e divídas
CREATE TABLE Categoria (
	id_categoria INT AUTO_INCREMENT PRIMARY KEY,
	nome_categoria VARCHAR(45) NOT NULL,
	fk_usuario INT,
	UNIQUE(nome_categoria, fk_usuario),
	FOREIGN KEY (fk_usuario) REFERENCES Usuario(id_usuario)  ON DELETE CASCADE ON UPDATE CASCADE
);
-- Dívidas que o usuário tem que pagar
CREATE TABLE Divida (
	id_divida INT AUTO_INCREMENT PRIMARY KEY,
	nome_divida VARCHAR(50),
	valor_divida DECIMAL(10,2) CHECK(valor_divida > 0),
	data_vencimento DATE,
	data_primeira_parcela DATE NOT NULL,
	parcelas int,
	fk_usuario INT NOT NULL,
	fk_categoria INT NOT NULL,
	fk_tipo_pagamento INT NOT NULL,
	FOREIGN KEY (fk_usuario) REFERENCES Usuario(id_usuario)  ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (fk_tipo_pagamento) REFERENCES TipoPagamento(id_pagamento) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (fk_categoria) REFERENCES Categoria(id_categoria) ON DELETE CASCADE ON UPDATE CASCADE
);
-- Como o usuário ganha o seu dinheiro
CREATE TABLE Renda (
	id_renda INT AUTO_INCREMENT PRIMARY KEY,
	valor_renda DECIMAL(10,2) CHECK(valor_renda >= 0),
	data_renda DATE NOT NULL,
	fk_usuario INT NOT NULL,
	fk_fonte INT NOT NULL,
	FOREIGN KEY (fk_usuario) REFERENCES Usuario(id_usuario)  ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (fk_fonte) REFERENCES FonteRenda(id_renda) ON DELETE CASCADE ON UPDATE CASCADE
);
-- Forma do usuário guardar o dinheiro
CREATE TABLE Poupanca (
	id_poupanca INT AUTO_INCREMENT PRIMARY KEY,
	objetivo VARCHAR(50),
	valor_atual DECIMAL(10,2) NOT NULL,
	valor_meta DECIMAL(10,2) NOT NULL,
	fk_usuario INT,
	UNIQUE (objetivo),
	FOREIGN KEY (fk_usuario) REFERENCES Usuario(id_usuario)  ON DELETE CASCADE ON UPDATE CASCADE
);
-- Dependentes
CREATE TABLE Dependente (
	id_dependente INT AUTO_INCREMENT PRIMARY KEY,
	nome_dependente VARCHAR(50) NOT NULL,
	relacao VARCHAR(45),
	fk_usuario INT NOT NULL,
	UNIQUE(nome_dependente),
	FOREIGN KEY (fk_usuario) REFERENCES Usuario(id_usuario) ON DELETE CASCADE ON UPDATE CASCADE
);
-- Despesas que o usuário está pagando
CREATE TABLE Despesa (
	id_despesa INT AUTO_INCREMENT PRIMARY KEY,
	nome_despesa VARCHAR(50),
	valor_despesa DECIMAL(10,2),
	data_despesa DATE NOT NULL,
	fk_dependente int,
	fk_usuario INT NOT NULL,
	fk_categoria INT NOT NULL,
	fk_tipo_pagamento INT NOT NULL,
	FOREIGN KEY (fk_dependente) REFERENCES Dependente(id_dependente) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (fk_usuario) REFERENCES Usuario(id_usuario) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (fk_tipo_pagamento) REFERENCES TipoPagamento(id_pagamento)  ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (fk_categoria) REFERENCES Categoria(id_categoria) ON DELETE CASCADE ON UPDATE CASCADE
);
