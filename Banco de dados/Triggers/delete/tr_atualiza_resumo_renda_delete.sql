-- corte de linha para quando deleta a renda
DELIMITER //
CREATE TRIGGER tr_atualiza_resumo_renda_delete
AFTER DELETE ON Renda
FOR EACH ROW
BEGIN
  DECLARE v_id INT;
  SELECT id_resumo INTO v_id
  FROM ResumoMensal
  WHERE mes = MONTH(OLD.data_renda)
    AND ano = YEAR(OLD.data_renda)
    AND fk_usuario = OLD.fk_usuario
  LIMIT 1;
  IF (v_id IS NOT NULL) THEN
    DELETE FROM ResumoMensal
		WHERE id_resumo = v_id;
  END IF;
END //
DELIMITER ;