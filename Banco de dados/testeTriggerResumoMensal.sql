-- Usuário base
INSERT INTO Usuario (nome_usuario, email_usuario, senha_usuario)
VALUES ('Carlos Souza', 'carlos@example.com', 'senhaSegura123');

-- Tipo de pagamento
INSERT INTO TipoPagamento (nome_pagamento, fk_usuario)
VALUES ('Débito', 1);

-- Fonte de renda
INSERT INTO FonteRenda (fonte_da_renda, fk_usuario)
VALUES ('Salário', 1);

-- Categoria de despesa
INSERT INTO Categoria (nome_categoria, fk_usuario)
VALUES ('Transporte', 1);

INSERT INTO Renda (valor_renda, data_renda, fk_usuario, fk_fonte)
VALUES (3500.00, '2025-07-01', 1, 1);

INSERT INTO Despesa (nome_despesa, valor_despesa, data_despesa, fk_dependente, fk_usuario, fk_categoria, fk_tipo_pagamento)
VALUES ('Combustível', 800.00, '2025-07-03', NULL, 1, 1, 1);

SELECT * FROM ResumoMensal;