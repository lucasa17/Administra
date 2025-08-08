-- Atualiza resumo de renda
DELIMITER //
CREATE TRIGGER tr_atualiza_resumo_renda_insert
AFTER INSERT ON Renda
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
    SET total_receita = total_receita + NEW.valor_renda,
        saldo = saldo + NEW.valor_renda
    WHERE id_resumo = v_id;
  ELSE
    INSERT INTO ResumoMensal (ano, mes, total_receita, total_despesa, saldo, fk_usuario)
    VALUES (YEAR(NEW.data_renda), MONTH(NEW.data_renda), NEW.valor_renda, 0, NEW.valor_renda, NEW.fk_usuario);
  END IF;
END //
DELIMITER ;