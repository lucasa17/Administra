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