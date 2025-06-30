USE sistemaFinanceiro;
-- Criar um usuário para teste
INSERT INTO Usuario (nome_usuario, email_usuario, senha_usuario)
VALUES ('José Carlos', 'jose@email.com', 'senha123');
-- Cria um tipo de pagamento
INSERT INTO TipoPagamento (nome_pagamento, fk_usuario)
VALUES ('PIX', 1);
-- Criar categoria de dívida
INSERT INTO CategoriaDivida (nome_categoria, fk_usuario)
VALUES ('Cartão de Crédito', 1);
-- Criar dívida
INSERT INTO Divida (
    nome_divida,
    valor_divida,
    credor,
    data_vencimento,
    data_primeira_parcela,
    parcelas,
    fk_usuario,
    fk_tipo_pagamento
)
VALUES (
    'Fatura Nubank',
    500.00,
    'Nubank',
    '2025-08-10',
    '2025-07-10',
    2,
    1,
    1
);
-- Relacionar dívida à categoria
INSERT INTO DividaCategoria (fk_divida, fk_categoria)
VALUES (1, 1);
-- Verificando tabela DividaCategoria
SELECT * FROM DividaCategoria;