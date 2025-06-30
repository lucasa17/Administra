<?php
session_start();
include 'conexao.php';

	$id = $_SESSION['ID_USER'];
    
    $idDivida = $_POST['idDivida'];

    $delete = "delete from divida where fk_usuario = $id and id_divida = $idDivida";

    mysqli_query($conn, $delete);

    header("refresh: 0; url=divida.php");
?>