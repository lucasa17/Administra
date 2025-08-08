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