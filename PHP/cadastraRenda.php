<?php
session_start();
include 'conexao.php';

	$id = $_SESSION['ID_USER'];
    
    $valor = $_POST['numValor'];
    $data = $_POST['txtData'];    

    if ($_POST['txtNovaRenda'] != '') {
        $fonteRenda = $_POST['txtNovaRenda'];

        $insert = "insert into FonteRenda (fonte_da_renda, fk_usuario) values ('$fonteRenda', $id)";
        mysqli_query($conn, $insert);
        $idFonte = mysqli_insert_id($conn);

            $insertRenda = "insert into renda (fk_fonte, valor_renda, data_renda, fk_usuario) values ($idFonte, $valor, '$data', $id)";
            mysqli_query($conn, $insertRenda);

    }    
    else{
        $idFonte = $_POST['txtRenda'];
            $insertRenda = "insert into renda (fk_fonte, valor_renda, data_renda, fk_usuario) values ($idFonte, $valor, '$data', $id)";
            mysqli_query($conn, $insertRenda);

    }
    header("refresh: 0; url=renda.php");
?>