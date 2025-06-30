<?php
session_start();
include 'conexao.php';

	$id = $_SESSION['ID_USER'];
    
    $idFonte = $_POST['idFonte'];

    $delete = "delete from FonteRenda where fk_usuario = $id and id_Renda = $idFonte";

    $renda = mysqli_query($conn, $delete);

    header("refresh: 0; url=renda.php");
?>