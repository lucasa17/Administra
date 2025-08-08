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