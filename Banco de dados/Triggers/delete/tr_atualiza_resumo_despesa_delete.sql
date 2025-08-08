-- Atualiza Renda, Despesas e Dividas quando deletadas
DELIMITER //
CREATE TRIGGER tr_atualiza_resumo_despesa_delete
AFTER DELETE ON Despesa
FOR EACH ROW
BEGIN
  DECLARE v_id INT;
  SELECT id_resumo INTO v_id
  FROM ResumoMensal
  WHERE mes = MONTH(OLD.data_despesa)
    AND ano = YEAR(OLD.data_despesa)
    AND fk_usuario = OLD.fk_usuario
  LIMIT 1;
  IF (v_id IS NOT NULL) THEN
    DELETE FROM ResumoMensal
		WHERE id_resumo = v_id;
  END IF;
END //
DELIMITER ;