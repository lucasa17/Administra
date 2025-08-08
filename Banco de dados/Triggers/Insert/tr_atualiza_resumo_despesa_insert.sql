-- Atualiza resumo despesa
DELIMITER //
CREATE TRIGGER tr_atualiza_resumo_despesa_insert
AFTER INSERT ON Despesa
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
    SET total_despesa = total_despesa + NEW.valor_despesa,
        saldo = saldo - NEW.valor_despesa
    WHERE id_resumo = v_id;
  ELSE
    INSERT INTO ResumoMensal (ano, mes, total_receita, total_despesa, saldo, fk_usuario)
    VALUES (YEAR(NEW.data_despesa), MONTH(NEW.data_despesa), 0, NEW.valor_despesa, -NEW.valor_despesa, NEW.fk_usuario);
  END IF;
END //
DELIMITER ;