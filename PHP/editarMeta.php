<?php
session_start();
include 'conexao.php';

    $id = $_SESSION['ID_USER'];

    $idMeta = $_POST['idMeta'];
    $objetivo = $_POST['editObjetivo'];
    $valorFinal = $_POST['editValorFinal'];

  
    $update = "update poupanca set objetivo  = '$objetivo', valor_meta = $valorFinal 
    where id_poupanca = $idMeta and fk_usuario = $id";

    mysqli_query($conn, $update);

    header("refresh: 0; url = meta.php");

?>
