<?php
session_start();
include 'conexao.php';

    $id = $_SESSION['ID_USER'];

    $idDespesa = $_POST['idDespesa'];
    $nome = $_POST['editNome'];
    $categoria = $_POST['editCategoria'];
    $tipo = $_POST['editPagamento'];
    $valor = $_POST['editValor'];
    $data = $_POST['editData'];

  
    if($_POST['editDependente'] == ''){
        $update = "update despesa set 
        nome_despesa = '$nome', valor_despesa = $valor,data_despesa = '$data',
        fk_categoria = $categoria, fk_tipo_pagamento = $tipo
        where id_despesa = $idDespesa and fk_usuario = $id";
    }
    else{
        $dependente = $_POST['editDependente'];

        $update = "update despesa set 
        nome_despesa = '$nome', valor_despesa = $valor, data_despesa = '$data',
        fk_dependente = $dependente, fk_categoria = $categoria, fk_tipo_pagamento = $tipo
        where id_despesa = $idDespesa and fk_usuario = $id";
    }

    mysqli_query($conn, $update);

    header("refresh: 0; url = despesa.php");

?>
