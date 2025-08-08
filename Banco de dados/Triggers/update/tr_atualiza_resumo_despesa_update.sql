-- Atualiza resumo mensal da despesa
DELIMITER //
CREATE TRIGGER tr_atualiza_resumo_despesa_update
AFTER UPDATE ON Despesa
FOR EACH ROW
BEGIN
  DECLARE v_id INT;

  SELECT id_resumo INTO v_id
  FROM ResumoMensal
  WHERE mes = MONTH(NEW.data_despesa)
    AND ano = YEAR(NEW.data_despesa)
    AND fk_usuario = NEW.fk_usuario
  LIMIT 1;

  IF (v_id IS NOT NULL) THEN
    UPDATE ResumoMensal
    SET total_despesa = total_despesa - OLD.valor_despesa + NEW.valor_despesa,
        saldo = saldo + OLD.valor_despesa - NEW.valor_despesa
    WHERE id_resumo = v_id;
  END IF;
END //
DELIMITER ;