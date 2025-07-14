-- Usuário principal
INSERT INTO Usuario (nome_usuario, email_usuario, senha_usuario)
VALUES ('José', 'jose@email.com', 'senhaSegura123');
-- Tipos de pagamento vinculados ao usuário 1
INSERT INTO TipoPagamento (nome_pagamento, fk_usuario)
VALUES 
  ('Pix', 1),
  ('Dinheiro', 1),
  ('Cartão Débito', 1),
  ('Cartão Crédito', 1),
  ('Cheque', 1);
-- Fontes de renda
INSERT INTO FonteRenda (fonte_da_renda, fk_usuario)
VALUES 
  ('Salário', 1),
  ('Freelance', 1),
  ('Aluguel', 1);
-- Categorias de despesas
INSERT INTO Categoria (nome_categoria, fk_usuario)
VALUES 
  ('Alimentação', 1),
  ('Transporte', 1),
  ('Lazer', 1);

-- Categorias de dívida
INSERT INTO CategoriaDivida (nome_categoria, fk_usuario)
VALUES 
  ('Empréstimo', 1),
  ('Consórcio', 1),
  ('Financiamento', 1);
-- Rendas com fontes específicas
INSERT INTO Renda (valor_renda, data_renda, fk_usuario, fk_fonte)
VALUES 
  (4000.00, '2025-01-05', 1, 1),
  (900.00, '2025-01-20', 1, 2),
  (600.00, '2025-01-25', 1, 3);
-- Dependente
INSERT INTO Dependente (nome_dependente, relacao, fk_usuario)
VALUES ('Lucas', 'Filho', 1);
-- Despesas variadas
INSERT INTO Despesa (nome_despesa, valor_despesa, data_despesa, fk_dependente, fk_usuario, fk_categoria, fk_tipo_pagamento)
VALUES 
  ('Supermercado', 450.00, '2025-01-10', NULL, 1, 1, 2),
  ('Transporte Escolar', 300.00, '2025-01-15', 1, 1, 2, 1),
  ('Cinema com família', 120.00, '2025-01-21', 1, 1, 3, 4);
-- Poupanças com metas
INSERT INTO Poupanca (objetivo, valor_atual, valor_meta, meses_ate_meta, fk_usuario)
VALUES 
  ('Trocar carro', 6000.00, 20000.00, 10, 1),
  ('Viagem internacional', 3000.00, 10000.00, 8, 1);
-- Dívidas (triggers calculam parcelas, vencimento e atualizam resumo mensal)
INSERT INTO Divida (
  nome_divida, valor_divida, credor,
  data_vencimento, mes_inicio, mes_final, valor_parcela,
  data_primeira_parcela, parcelas,
  fk_usuario, fk_categoria, fk_tipo_pagamento
)
VALUES (
  'Financiamento Moto', 9000.00, 'Banco Brasil',
  NULL, NULL, NULL, NULL,
  '2025-03-15', 10,
  1, 3, 2
);
SELECT * FROM Divida;