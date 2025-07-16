<?php
session_start();
include 'conexao.php';

$id = $_SESSION['ID_USER'];
    
    $valor = $_POST['valor'];
    $op = $_POST['op'];
   
    if($op == 'sim'){

        $update = "update resumoMensal where fk_usuario = $id set saldo_meta = $valor";

    }

    //header("refresh: 0; url=visaoGeral.php");

?>