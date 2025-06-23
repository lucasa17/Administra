<?php

    $server = "localhost";
    $user = "root";
    $password = "";
    $db = "sistemaFinanceiro";

      $conn = mysqli_connect($server, $user, $password, $db);

        $testeConexao = "Conexão realizada com sucesso!";

        //echo" $testeConexao";

?>