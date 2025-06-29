<?php
session_start();
include 'conexao.php';

	$id = $_SESSION['ID_USER'];

    $nome_dependente = $_POST['txtnome'];
	$relacao_dependente = $_POST['txtrelacao'];

    $insert = "insert into dependente (nome_dependente, relacao, fk_usuario) values ('$nome_dependente', '$relacao_dependente', $id)";

    $dependente = mysqli_query($conn, $insert);

    header("refresh: 0; url=dependente.php");
?>