<?php
session_start();
include 'conexao.php';

	$id = $_SESSION['ID_USER'];
    
    $objetivo = $_POST['txtObjetivo'];
    $aporteInicial = $_POST['numAporteInicial'];    
    $valorFinal = $_POST['numValorFinal'];    

    $insertMeta = "insert into Poupanca (objetivo, valor_atual, valor_meta, fk_usuario) values ('$objetivo', $aporteInicial, $valorFinal, $id)";
    $meta = mysqli_query($conn, $insertMeta);

    header("refresh: 0; url=metas.php");
?>