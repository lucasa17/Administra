<?php
session_start();
include 'conexao.php';

$id = $_SESSION['ID_USER'];

$valor = $_POST['numValor'];
$data = $_POST['txtData'];    
$op = $_POST['rendaRepetida'];    

    if ($_POST['txtNovaRenda'] != '') {
        $fonteRenda = $_POST['txtNovaRenda'];
        $insert = "INSERT INTO FonteRenda (fonte_da_renda, fk_usuario) VALUES ('$fonteRenda', $id)";
        mysqli_query($conn, $insert);
        $idFonte = mysqli_insert_id($conn);
    } else {
        $idFonte = $_POST['txtRenda'];
    }

    if ($op == 'true') {
        $start = new DateTime($data);
        $now = new DateTime();
        $start->setTime(0, 0);
        $now->setTime(0, 0);

        while ($start <= $now) {
            $dataFormatada = $start->format('Y-m-d');

            $insertRenda = "INSERT INTO renda (fk_fonte, valor_renda, data_renda, fixa, fk_usuario)
                            VALUES ($idFonte, $valor, '$dataFormatada', $op, $id)";
            mysqli_query($conn, $insertRenda);

            $start->modify('+1 month');
        }
    } else {
        $insertRenda = "INSERT INTO renda (fk_fonte, valor_renda, data_renda, fixa, fk_usuario)
                        VALUES ($idFonte, $valor, '$data', $op, $id)";
        mysqli_query($conn, $insertRenda);
    }
    //echo"$insertRenda";
    header("refresh: 0; url=renda.php");
?>
