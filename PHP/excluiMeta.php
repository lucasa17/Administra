<?php
session_start();
include 'conexao.php';

	$id = $_SESSION['ID_USER'];
    
    $idMeta = $_POST['idMeta'];

    $delete = "delete from poupanca where fk_usuario = $id and id_poupanca = $idMeta";

    $meta = mysqli_query($conn, $delete);

    header("refresh: 0; url=meta.php");
?>