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
		SET NEW.data_vencimento = DATE_ADD(NEW.data_primeira_parcela, INTERVAL (NEW.parcelas - 1) MONTH );
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