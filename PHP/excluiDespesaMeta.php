<?php
session_start();
include 'conexao.php';

	$id = $_SESSION['ID_USER'];
    
    $idMeta = $_POST['idMeta'];
    $idDespesa = $_POST['idDespesa'];
    $valor = $_POST['valor'];

    $update = "update poupanca set valor_atual = valor_atual - $valor where id_poupanca = $idMeta";
    echo $update;
    //mysqli_query($conn, $update);

    $delete = "delete from despesa where fk_usuario = $id and id_despesa = $idDespesa";
    //mysqli_query($conn, $delete);

    //header("refresh: 0; url=meta.php");
?>