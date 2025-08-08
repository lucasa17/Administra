DELIMITER #
CREATE PROCEDURE VerDespesasPorMes(
	IN p_mes INT,
    IN p_ano INT
)
BEGIN
	SELECT * FROM Despesa
    WHERE MONTH(data_despesa) = p_mes
    AND YEAR(data_despesa)  = p_ano;
END #
DELIMITER ;