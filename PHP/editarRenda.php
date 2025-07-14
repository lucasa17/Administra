<?php
session_start();
include 'conexao.php';

    $id = $_SESSION['ID_USER'];

    $idRenda = $_POST['idRenda'];
    $data = $_POST['data'];
    $valor = $_POST['valor'];
    $fonte = trim($_POST['fonte']);

  
    $update = "update renda set valor_renda = $valor, data_renda = '$data', fk_fonte = $fonte 
    where id_renda = $idRenda and fk_usuario = $id";

    mysqli_query($conn, $update);

    header("refresh: 0; url = renda.php");

?>
