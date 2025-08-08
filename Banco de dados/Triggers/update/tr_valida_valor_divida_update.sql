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