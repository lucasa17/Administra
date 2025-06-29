<?php
session_start();
include 'conexao.php';

	$id = $_SESSION['ID_USER'];
    
    $idDependente = $_POST['idDependente'];

    $delete = "delete from dependente where fk_usuario = $id and id_dependente = $idDependente";

    $dependente = mysqli_query($conn, $delete);

    header("refresh: 0; url=dependente.php");
?>