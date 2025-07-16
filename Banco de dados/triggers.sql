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
    UPDATE ResumoMensal
    SET total_receita = total_receita - OLD.valor_renda,
        saldo = saldo - OLD.valor_renda
    WHERE id_resumo = v_id;
  END IF;
END //
-- Quebra de linha
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
    UPDATE ResumoMensal
    SET total_despesa = total_despesa - OLD.valor_despesa,
        saldo = saldo + OLD.valor_despesa
    WHERE id_resumo = v_id;
  END IF;
END //
DELIMITER ;
-- Quebra de linha
DELIMITER //
CREATE TRIGGER tr_atualiza_resumo_divida_delete
AFTER DELETE ON Divida
FOR EACH ROW
BEGIN
  DECLARE v_id INT;

  SELECT id_resumo INTO v_id
  FROM ResumoMensal
  WHERE mes = MONTH(OLD.data_primeira_parcela)
    AND ano = YEAR(OLD.data_primeira_parcela)
    AND fk_usuario = OLD.fk_usuario
  LIMIT 1;

  IF (v_id IS NOT NULL) THEN
    UPDATE ResumoMensal
    SET total_despesa = total_despesa - OLD.valor_parcela,
        saldo = saldo + OLD.valor_parcela
    WHERE id_resumo = v_id;
  END IF;
END //
DELIMITER ;
