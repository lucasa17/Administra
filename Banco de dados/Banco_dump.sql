-- DROP DATABASE sistemaFinanceiro;
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
	fonte_da_renda VARCHAR(100) NOT NULL,
	fk_usuario INT,
	FOREIGN KEY (fk_usuario) REFERENCES Usuario(id_usuario)  ON DELETE CASCADE ON UPDATE CASCADE
);
-- Categorias das despesas e divídas
CREATE TABLE Categoria(
	id_categoria INT AUTO_INCREMENT PRIMARY KEY,
	nome_categoria VARCHAR(45) NOT NULL,
	fk_usuario INT,
	FOREIGN KEY (fk_usuario) REFERENCES Usuario(id_usuario)  ON DELETE CASCADE ON UPDATE CASCADE
);
-- Tabela de divida e categoria
CREATE TABLE CategoriaDivida(
	id_categoria INT AUTO_INCREMENT PRIMARY KEY,
	nome_categoria VARCHAR(45) NOT NULL,
	fk_usuario INT,
	FOREIGN KEY (fk_usuario) REFERENCES Usuario(id_usuario)  ON DELETE CASCADE ON UPDATE CASCADE
);
-- Dívidas que o usuário tem que pagar
CREATE TABLE Divida (
	id_divida INT AUTO_INCREMENT PRIMARY KEY,
	nome_divida VARCHAR(50),
	valor_divida DECIMAL(10,2),
    credor varchar(100),
	data_vencimento DATE,
    mes_inicio INT,
    mes_final INT,
    valor_parcela DECIMAL(10,2),
	data_primeira_parcela DATE NOT NULL,
	parcelas INT NOT NULL,
	fk_usuario INT NOT NULL,
	fk_categoria INT NOT NULL,
	fk_tipo_pagamento INT NOT NULL,
	FOREIGN KEY (fk_usuario) REFERENCES Usuario(id_usuario)  ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (fk_tipo_pagamento) REFERENCES TipoPagamento(id_pagamento) ON DELETE CASCADE ON UPDATE CASCADE,
	 FOREIGN KEY (fk_categoria) REFERENCES CategoriaDivida(id_categoria) ON DELETE CASCADE ON UPDATE CASCADE
);
-- Como o usuário ganha o seu dinheiro
CREATE TABLE Renda (
	id_renda INT AUTO_INCREMENT PRIMARY KEY,
	valor_renda DECIMAL(10,2),
	data_renda DATE,
    fixa boolean,
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
    meses_ate_meta INT,
	fk_usuario INT,
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
-- Despesas que o usuário está pagando
CREATE TABLE Despesa (
	id_despesa INT AUTO_INCREMENT PRIMARY KEY,
	nome_despesa VARCHAR(50),
	valor_despesa DECIMAL(10,2),
	data_despesa DATE NOT NULL,
	fixo BOOLEAN DEFAULT FALSE,
    fk_meta int,
	fk_dependente int,
	fk_usuario INT NOT NULL,
	fk_categoria INT NOT NULL,
	fk_tipo_pagamento INT NOT NULL,
    FOREIGN KEY (fk_meta) REFERENCES Poupanca(id_poupanca) ON DELETE CASCADE ON UPDATE NO ACTION,
	FOREIGN KEY (fk_dependente) REFERENCES Dependente(id_dependente) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (fk_usuario) REFERENCES Usuario(id_usuario) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (fk_tipo_pagamento) REFERENCES TipoPagamento(id_pagamento)  ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (fk_categoria) REFERENCES Categoria(id_categoria) ON DELETE CASCADE ON UPDATE CASCADE
);
-- Tabela para um resumo mensal
CREATE TABLE ResumoMensal (
  id_resumo INT AUTO_INCREMENT PRIMARY KEY,
  ano INT,
  mes INT,
  total_receita DECIMAL(10,2),
  total_despesa DECIMAL(10,2),
  saldo DECIMAL(10,2),
  saldo_meta DECIMAL(10,2),
  fk_usuario INT
);
DELIMITER #
CREATE PROCEDURE VerDespesasPorMes(
	IN p_mes INT,
    IN p_ano INT
)
BEGIN
	SELECT * FROM Despesa
    WHERE MONTH(data_despesa) = p_mes
    AND YEAR(data_despesa)  = p_ano;
END #
DELIMITER ;

-- Insere data de vencimento de acordo com as parcelas e data inicial
DELIMITER //
CREATE TRIGGER tr_seta_data_vencimento 
BEFORE INSERT ON Divida 
FOR EACH ROW
BEGIN 
	IF (NEW.data_vencimento IS NULL) THEN
		SET NEW.data_vencimento = DATE_ADD(NEW.data_primeira_parcela, INTERVAL (NEW.parcelas - 1) MONTH );
	END IF;
END //
DELIMITER ;
-- Atualiza a data de vencimento de acordo com as parcelas e data inicial
DELIMITER //
CREATE TRIGGER tr_seta_data_vencimento_update
BEFORE UPDATE ON Divida 
FOR EACH ROW
BEGIN 
    IF (NEW.data_vencimento IS NULL) THEN
        SET NEW.data_vencimento = DATE_ADD(NEW.data_primeira_parcela, INTERVAL (NEW.parcelas - 1) MONTH );
    END IF;
END //
DELIMITER ;
-- Não deixa inserir um valor de divida menor que 0
DELIMITER //
CREATE TRIGGER tr_valida_valor_divida_insert
BEFORE INSERT ON Divida
FOR EACH ROW
BEGIN
    IF (NEW.valor_divida <= 0) THEN
	SIGNAL SQLSTATE "45000" 
    SET MESSAGE_TEXT = "O valor da divida deve ser no mínimo 0";
    END IF;
END //
DELIMITER ;
-- Antes de atualizar
DELIMITER //
CREATE TRIGGER tr_valida_valor_divida_update
BEFORE UPDATE ON Divida
FOR EACH ROW
BEGIN
    IF (NEW.valor_divida <= 0) THEN
        SIGNAL SQLSTATE "45000" 
        SET MESSAGE_TEXT = "O valor da divida deve ser no mínimo 0";
    END IF;
END //
DELIMITER ;
-- Verifica se o valor da renda do usuário não é negativa
DELIMITER //
CREATE TRIGGER tr_valida_valor_renda_insert
BEFORE INSERT ON Renda
FOR EACH ROW
BEGIN
    IF (NEW.valor_renda < 0) THEN
		SIGNAL SQLSTATE "45000" 
		SET MESSAGE_TEXT = "O valor da renda não pode ser negativa";
    END IF;
END //
DELIMITER ;
-- Antes de atualizar também
DELIMITER //
CREATE TRIGGER tr_valida_valor_renda_update
BEFORE UPDATE ON Renda
FOR EACH ROW
BEGIN
    IF (NEW.valor_renda < 0) THEN
        SIGNAL SQLSTATE "45000" 
        SET MESSAGE_TEXT = "O valor da renda não pode ser negativa";
    END IF;
END //
DELIMITER ;
-- Verifica se o valor atual da poupança é maior que o da meta
DELIMITER //
CREATE TRIGGER tr_valida_poupanca_insert
BEFORE INSERT ON Poupanca
FOR EACH ROW
BEGIN
    IF (NEW.valor_atual > NEW.valor_meta) THEN
        SIGNAL SQLSTATE "45000" 
		SET MESSAGE_TEXT = "O valor atual não pode ser maior que o da meta";
    END IF;
END //
DELIMITER ;
-- Antes de atualizar também
DELIMITER //
CREATE TRIGGER tr_valida_poupanca_update
BEFORE UPDATE ON Poupanca
FOR EACH ROW
BEGIN
    IF (NEW.valor_atual > NEW.valor_meta) THEN
        SIGNAL SQLSTATE "45000" 
        SET MESSAGE_TEXT = "O valor atual não pode ser maior que o da meta";
    END IF;
END //
DELIMITER ;
-- Verifica se o valor de parcelas é nulo
DELIMITER //
CREATE TRIGGER tr_valida_parcelas_nulas_insert
BEFORE INSERT ON Divida
FOR EACH ROW
BEGIN
    IF NEW.parcelas IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'O campo parcelas não pode ser nulo';
    END IF;
END //
DELIMITER ;
-- Valida durante o update
DELIMITER //
CREATE TRIGGER tr_valida_parcelas_nulas_update
BEFORE UPDATE ON Divida
FOR EACH ROW
BEGIN
    IF NEW.parcelas IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'O campo parcelas não pode ser nulo';
    END IF;
END //
DELIMITER ;
-- Atualiza resumo de renda
DELIMITER //
CREATE TRIGGER tr_atualiza_resumo_renda_insert
AFTER INSERT ON Renda
FOR EACH ROW
BEGIN
  DECLARE v_id INT;
  SELECT id_resumo INTO v_id
  FROM ResumoMensal
  WHERE mes = MONTH(NEW.data_renda)
    AND ano = YEAR(NEW.data_renda)
    AND fk_usuario = NEW.fk_usuario
  LIMIT 1;
  IF (v_id IS NOT NULL) THEN
    UPDATE ResumoMensal
    SET total_receita = total_receita + NEW.valor_renda,
        saldo = saldo + NEW.valor_renda
    WHERE id_resumo = v_id;
  ELSE
    INSERT INTO ResumoMensal (ano, mes, total_receita, total_despesa, saldo, fk_usuario)
    VALUES (YEAR(NEW.data_renda), MONTH(NEW.data_renda), NEW.valor_renda, 0, NEW.valor_renda, NEW.fk_usuario);
  END IF;
END //
DELIMITER ;
-- Atualiza resumo despesa
DELIMITER //
CREATE TRIGGER tr_atualiza_resumo_despesa_insert
AFTER INSERT ON Despesa
FOR EACH ROW
BEGIN
  DECLARE v_id INT;
  SELECT id_resumo INTO v_id
  FROM ResumoMensal
  WHERE mes = MONTH(NEW.data_despesa)
    AND ano = YEAR(NEW.data_despesa)
    AND fk_usuario = NEW.fk_usuario
  LIMIT 1;
  IF (v_id IS NOT NULL) THEN
    UPDATE ResumoMensal
    SET total_despesa = total_despesa + NEW.valor_despesa,
        saldo = saldo - NEW.valor_despesa
    WHERE id_resumo = v_id;
  ELSE
    INSERT INTO ResumoMensal (ano, mes, total_receita, total_despesa, saldo, fk_usuario)
    VALUES (YEAR(NEW.data_despesa), MONTH(NEW.data_despesa), 0, NEW.valor_despesa, -NEW.valor_despesa, NEW.fk_usuario);
  END IF;
END //
DELIMITER ;
-- Atualiza resumo mensal
DELIMITER //
CREATE TRIGGER tr_atualiza_resumo_renda_update
AFTER UPDATE ON Renda
FOR EACH ROW
BEGIN
  DECLARE v_id INT;

  SELECT id_resumo INTO v_id
  FROM ResumoMensal
  WHERE mes = MONTH(NEW.data_renda)
    AND ano = YEAR(NEW.data_renda)
    AND fk_usuario = NEW.fk_usuario
  LIMIT 1;

  IF (v_id IS NOT NULL) THEN
    UPDATE ResumoMensal
    SET total_receita = total_receita - OLD.valor_renda + NEW.valor_renda,
        saldo = saldo - OLD.valor_renda + NEW.valor_renda
    WHERE id_resumo = v_id;
  END IF;
END //
DELIMITER ;
-- Atualiza resumo mensal da despesa
DELIMITER //
CREATE TRIGGER tr_atualiza_resumo_despesa_update
AFTER UPDATE ON Despesa
FOR EACH ROW
BEGIN
  DECLARE v_id INT;

  SELECT id_resumo INTO v_id
  FROM ResumoMensal
  WHERE mes = MONTH(NEW.data_despesa)
    AND ano = YEAR(NEW.data_despesa)
    AND fk_usuario = NEW.fk_usuario
  LIMIT 1;

  IF (v_id IS NOT NULL) THEN
    UPDATE ResumoMensal
    SET total_despesa = total_despesa - OLD.valor_despesa + NEW.valor_despesa,
        saldo = saldo + OLD.valor_despesa - NEW.valor_despesa
    WHERE id_resumo = v_id;
  END IF;
END //
DELIMITER ;
-- atualiza resumo mensal da divida
DELIMITER //
CREATE TRIGGER tr_atualiza_resumo_divida_update
AFTER UPDATE ON Divida
FOR EACH ROW
BEGIN
  DECLARE v_id INT;
  DECLARE v_mes INT;
  DECLARE v_ano INT;

  SET v_mes = MONTH(NEW.data_primeira_parcela);
  SET v_ano = YEAR(NEW.data_primeira_parcela);

  SELECT id_resumo INTO v_id
  FROM ResumoMensal
  WHERE mes = v_mes
    AND ano = v_ano
    AND fk_usuario = NEW.fk_usuario
  LIMIT 1;

  IF (v_id IS NOT NULL) THEN
    UPDATE ResumoMensal
    SET total_despesa = total_despesa - OLD.valor_parcela + NEW.valor_parcela,
        saldo = saldo + OLD.valor_parcela - NEW.valor_parcela
    WHERE id_resumo = v_id;
  END IF;
END //
DELIMITER ;
-- Detalha Divida
DELIMITER //
CREATE TRIGGER tr_calcula_detalhes_divida
BEFORE INSERT ON Divida
FOR EACH ROW
BEGIN
    IF NEW.parcelas > 0 THEN
        SET NEW.valor_parcela = NEW.valor_divida / NEW.parcelas;
        SET NEW.mes_inicio = MONTH(NEW.data_primeira_parcela);
        SET NEW.mes_final = MONTH(DATE_ADD(NEW.data_primeira_parcela, INTERVAL (NEW.parcelas - 1) MONTH));
    END IF;
END //
DELIMITER ;
-- Para quando atualizar
DELIMITER //
CREATE TRIGGER tr_calcula_detalhes_divida_update
BEFORE UPDATE ON Divida
FOR EACH ROW
BEGIN
    IF NEW.parcelas > 0 THEN
        SET NEW.valor_parcela = NEW.valor_divida / NEW.parcelas;
        SET NEW.mes_inicio = MONTH(NEW.data_primeira_parcela);
        SET NEW.mes_final = MONTH(DATE_ADD(NEW.data_primeira_parcela, INTERVAL (NEW.parcelas - 1) MONTH));
    END IF;
END //
DELIMITER ;
-- Atualiza Renda, Despesas e Dividas quando deletadas
DELIMITER //
CREATE TRIGGER tr_atualiza_resumo_despesa_delete
AFTER DELETE ON Despesa
FOR EACH ROW
BEGIN
  DECLARE v_id INT;
  SELECT id_resumo INTO v_id
  FROM ResumoMensal
  WHERE mes = MONTH(OLD.data_despesa)
    AND ano = YEAR(OLD.data_despesa)
    AND fk_usuario = OLD.fk_usuario
  LIMIT 1;
  IF (v_id IS NOT NULL) THEN
    DELETE FROM ResumoMensal
		WHERE id_resumo = v_id;
  END IF;
END //
DELIMITER ;
-- corte de linha para quando deleta a renda
DELIMITER //
CREATE TRIGGER tr_atualiza_resumo_renda_delete
AFTER DELETE ON Renda
FOR EACH ROW
BEGIN
  DECLARE v_id INT;
  SELECT id_resumo INTO v_id
  FROM ResumoMensal
  WHERE mes = MONTH(OLD.data_renda)
    AND ano = YEAR(OLD.data_renda)
    AND fk_usuario = OLD.fk_usuario
  LIMIT 1;
  IF (v_id IS NOT NULL) THEN
    DELETE FROM ResumoMensal
		WHERE id_resumo = v_id;
  END IF;
END //
DELIMITER ;

-- Tipos de pagamento vinculados ao usuário 1
INSERT INTO TipoPagamento (nome_pagamento)
VALUES 
  ('Pix'),
  ('Dinheiro'),
  ('Cartão Débito'),
  ('Cartão Crédito'),
  ('Cheque'),
  ('Alocação interna');
-- Fontes de renda
INSERT INTO FonteRenda (fonte_da_renda)
VALUES 
  ('Salário'),
  ('Freelance'),
  ('Aluguel');
-- Categorias de despesas
INSERT INTO Categoria (nome_categoria)
VALUES 
  ('Alimentação'),
  ('Transporte'),
  ('Lazer'),
  ('Metas');

-- Categorias de dívida
INSERT INTO CategoriaDivida (nome_categoria)
VALUES 
  ('Empréstimo'),
  ('Consórcio'),
  ('Financiamento');
