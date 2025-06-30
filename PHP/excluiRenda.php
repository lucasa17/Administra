<?php
session_start();
include 'conexao.php';

	$id = $_SESSION['ID_USER'];
    
    $idRenda = $_POST['idRenda'];

    $delete = "delete from renda where fk_usuario = $id and id_Renda = $idRenda";

    $renda = mysqli_query($conn, $delete);

    header("refresh: 0; url=renda.php");
?>