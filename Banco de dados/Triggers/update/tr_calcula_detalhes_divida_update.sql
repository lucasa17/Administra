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