<?php
session_start();
include 'conexao.php';

	$id = $_SESSION['ID_USER'];
    
    $nome = $_POST['txtnomedivida'];
    $credor = $_POST['txtCredor'];
    $parcelas = $_POST['numParcelas'];
    $valor = $_POST['numValor'];
    $data = $_POST['txtData'];    

   
    if ($_POST['txtNovaCategoria'] != '') {
        $categoria = $_POST['txtNovaCategoria'];
            
            $insertCategoria = "insert into categoriaDivida (nome_categoria, fk_usuario) values ('$categoria', $id)";
            $categoria = mysqli_query($conn, $insertCategoria);
            $idCategoria = mysqli_insert_id($conn);
    }    
    else{
        $idCategoria = $_POST['txtCategoria'];
    }

    if ($_POST['txtNovoTipoPagamento'] != '') {
        $tipoPagamento = $_POST['txtNovoTipoPagamento'];

            $insertPagamento = "insert into TipoPagamento (nome_pagamento, fk_usuario) values ('$tipoPagamento', $id)";
            $pagamento = mysqli_query($conn, $insertPagamento);
            $idPagamento = mysqli_insert_id($conn);
    }    
    else{
        $idPagamento = $_POST['txtTipoPagamento'];
    }

    $insert = "insert into divida 
    (nome_divida, valor_divida, credor, data_primeira_parcela, parcelas, fk_usuario, fk_categoria, fk_tipo_pagamento) 
    values ('$nome', $valor, '$credor', '$data', $parcelas, $id, $idCategoria, $idPagamento)";
    $despesa = mysqli_query($conn, $insert);

    header("refresh: 0; url=divida.php");

?>