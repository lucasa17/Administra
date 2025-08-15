<?php
session_start();
include 'conexao.php';

	$id = $_SESSION['ID_USER'];
    
    $idMeta = $_POST['idMeta'];

    $selMeta = "select * from despesa where fk_meta = $idMeta";
    $queryMeta = mysqli_query($conn, $selMeta);
    
    $row = mysqli_num_rows($queryMeta);

    echo $row;
	if ($row > 0) {
        while($delMetaDespesa = mysqli_fetch_assoc($queryMeta)){
            $idDespesa = $delMetaDespesa['id_despesa'];

            $delDespesa = "delete from despesa where fk_usuario = $id and id_despesa = $idDespesa";
            mysqli_query($conn, $delDespesa);
        }
    }

    $delete = "delete from poupanca where fk_usuario = $id and id_poupanca = $idMeta";

    mysqli_query($conn, $delete);

    header("refresh: 0; url=meta.php");
?>