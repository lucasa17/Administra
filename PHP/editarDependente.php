<?php
session_start();
include 'conexao.php';

    $id = $_SESSION['ID_USER'];

    $idDependente = $_POST['idDependente'];
    $nome = $_POST['editNome'];
    $relacao = $_POST['editRelacao'];

  
    $update = "update dependente set nome_dependente = '$nome', relacao = '$relacao' 
    where id_dependente = $idDependente and fk_usuario = $id";

    mysqli_query($conn, $update);

    header("refresh: 0; url = dependente.php");

?>
