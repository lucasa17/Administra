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