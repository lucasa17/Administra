<?php
session_start();
include 'conexao.php';

	$id = $_SESSION['ID_USER'];
    
    $nome = $_POST['txtnomedespesa'];
    $valor = $_POST['numValor'];
    $parcelas = $_POST['numParcelas'];
    $data = $_POST['txtData'];    

    if (isset($_POST['txtNovaCategoria'])) {
        $categoria = $_POST['txtNovaCategoria'];
            
            $insertCategoria = "insert into categoria (nome_categoria, fk_usuario) values ('$categoria', $id)";
            $categoria = mysqli_query($conn, $insertCategoria);
            $idCategoria = mysqli_insert_id($conn);
    }    
    else{
        $idCategoria = $_POST['txtCategoria'];
    }

    if (isset($_POST['txtNovoTipoPagamento'])) {
        $tipoPagamento = $_POST['txtNovoTipoPagamento'];

            $insertPagamento = "insert into TipoPagamento (nome_pagamento, fk_usuario) values ('$tipoPagamento', $id)";
            $pagamento = mysqli_query($conn, $insertPagamento);
            $idPagamento = mysqli_insert_id($conn);
    }    
    else{
        $idPagamento = $_POST['txtTipoPagamento'];
    }

    if ($_POST['txtdependente'] != '') {
    $dependente = $_POST['txtdependente'];

        $insert = "insert into despesa 
        (nome_despesa, valor_despesa, data_despesa, fk_dependente, fk_usuario, fk_categoria, fk_tipo_pagamento) 
        values ('$nome', $valor, '$data', $dependente, $id, $idCategoria, $idPagamento)";
        $despesa = mysqli_query($conn, $insert);
    }
    else{
        $insert = "insert into despesa 
        (nome_despesa, valor_despesa, data_despesa, fk_usuario, fk_categoria, fk_tipo_pagamento) 
        values ('$nome', $valor, '$data', $id, $idCategoria, $idPagamento)";
        $despesa = mysqli_query($conn, $insert);
    }
    header("refresh: 0; url=dependente.php");
?>