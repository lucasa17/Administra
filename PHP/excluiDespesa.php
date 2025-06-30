<?php
session_start();
include 'conexao.php';

	$id = $_SESSION['ID_USER'];
    
    $idDespesa = $_POST['idDespesa'];

    $delete = "delete from despesa where fk_usuario = $id and id_despesa = $idDespesa";

    mysqli_query($conn, $delete);

    header("refresh: 0; url=despesa.php");
?>