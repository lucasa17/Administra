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