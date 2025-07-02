<?php
session_start();
include 'conexao.php';

    $id = $_SESSION['ID_USER'];

    $idDivida = $_POST['idDivida'];
    $nome = $_POST['editNome'];
    $credor = $_POST['editCredor'];
    $categoria = $_POST['editCategoria'];
    $tipo = $_POST['editPagamento'];
    $parcelas = $_POST['editParcelas'];
    $valor = $_POST['editValor'];
    $data = $_POST['editData'];

  
    $update = "update divida set 
    nome_divida = '$nome', valor_divida = $valor, credor = '$credor', 
    data_vencimento = '$data', parcelas = $parcelas, fk_categoria = $categoria,
    fk_tipo_pagamento = $tipo
    where id_divida = $idDivida and fk_usuario = $id";

    mysqli_query($conn, $update);

    header("refresh: 0; url = divida.php");

?>
