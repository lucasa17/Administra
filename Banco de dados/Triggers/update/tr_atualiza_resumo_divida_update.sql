-- atualiza resumo mensal da divida
DELIMITER //
CREATE TRIGGER tr_atualiza_resumo_divida_update
AFTER UPDATE ON Divida
FOR EACH ROW
BEGIN
  DECLARE v_id INT;
  DECLARE v_mes INT;
  DECLARE v_ano INT;

  SET v_mes = MONTH(NEW.data_primeira_parcela);
  SET v_ano = YEAR(NEW.data_primeira_parcela);

  SELECT id_resumo INTO v_id
  FROM ResumoMensal
  WHERE mes = v_mes
    AND ano = v_ano
    AND fk_usuario = NEW.fk_usuario
  LIMIT 1;

  IF (v_id IS NOT NULL) THEN
    UPDATE ResumoMensal
    SET total_despesa = total_despesa - OLD.valor_parcela + NEW.valor_parcela,
        saldo = saldo + OLD.valor_parcela - NEW.valor_parcela
    WHERE id_resumo = v_id;
  END IF;
END //
DELIMITER ;