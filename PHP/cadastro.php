<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recebe Cadastro</title>
</head>
<body>


<?php
session_start();
include 'conexao.php';


    $user = $_POST['txtuser'];
    $senha = $_POST['txtpassword'];
    $email = $_POST['txtemail'];

	    $entrada = "SELECT email_usuario, senha_usuario FROM usuario WHERE email_usuario = '$email' AND senha_usuario = '$senha'";

        $cadastro =  mysqli_query($conn,$entrada);

        $row = mysqli_num_rows($cadastro);



        if ($row == 0) {

            $_SESSION['user_cad'] = $user;
            $_SESSION['email_cad'] = $email;
        
            $senha = hash('sha256', $senha);
            $ins_cad = "insert into usuario (nome_usuario, email_usuario, senha_usuario) values('$user', '$email', '$senha');";
            $cad =  mysqli_query($conn,$ins_cad);
            

            echo"
                <h1>certim</h1>
            ";
            
            header("refresh: 2; url=../HTML/login.html");
        }
        else{

            echo"
                <h1>Cadastro já existente</h1>
            ";

            header("refresh: 4; url= ../cadastro/HTML/cadastro1.html");

        }




?>


</body>
</html>