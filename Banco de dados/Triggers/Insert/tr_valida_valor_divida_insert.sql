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