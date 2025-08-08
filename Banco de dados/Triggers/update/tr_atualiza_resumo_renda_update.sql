-- Atualiza resumo mensal
DELIMITER //
CREATE TRIGGER tr_atualiza_resumo_renda_update
AFTER UPDATE ON Renda
FOR EACH ROW
BEGIN
  DECLARE v_id INT;

  SELECT id_resumo INTO v_id
  FROM ResumoMensal
  WHERE mes = MONTH(NEW.data_renda)
    AND ano = YEAR(NEW.data_renda)
    AND fk_usuario = NEW.fk_usuario
  LIMIT 1;

  IF (v_id IS NOT NULL) THEN
    UPDATE ResumoMensal
    SET total_receita = total_receita - OLD.valor_renda + NEW.valor_renda,
        saldo = saldo - OLD.valor_renda + NEW.valor_renda
    WHERE id_resumo = v_id;
  END IF;
END //
DELIMITER ;