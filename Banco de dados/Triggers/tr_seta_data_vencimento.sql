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