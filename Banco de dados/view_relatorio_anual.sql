CREATE VIEW vw_relatorio_anual AS SELECT 
  u.id_usuario,
  u.nome_usuario,
  rm.ano,
  SUM(rm.total_receita) AS receita_anual,
  SUM(rm.total_despesa) AS despesa_anual,
  SUM(rm.saldo) AS saldo_anual,
  (
    SELECT IFNULL(SUM(valor_divida), 0)
    FROM Divida d
    WHERE d.fk_usuario = u.id_usuario
      AND YEAR(d.data_primeira_parcela) = rm.ano
  ) AS total_dividas_ano,
  (
    SELECT IFNULL(SUM(p.valor_meta - p.valor_atual), 0)
    FROM Poupanca p
    WHERE p.fk_usuario = u.id_usuario
  ) AS valor_faltante_poupanca
FROM Usuario u
JOIN ResumoMensal rm ON rm.fk_usuario = u.id_usuario
GROUP BY u.id_usuario, rm.ano;
SELECT * FROM vw_relatorio_anual;