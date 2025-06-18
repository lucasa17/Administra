<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<?php
session_start();
    $_SESSION['EMAIL_USER'];
    $email = $_SESSION['EMAIL_USER'];

    include 'conexao.php';

        /*$select = "select * from usuario where email_usuario = '$email'";
        $query = mysqli_query($conn, $select);
        $teste = mysqli_fetch_assoc($query);
        $a = $teste['nome_usuario'];
        echo"$a";
        */

        $insert = "insert into divida(nome_divida, valor_divida, data_vencimento, fk_usuario) values ()"
?>

</body>
</html>