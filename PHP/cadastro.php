<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Administra - Cadastro</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="../CSS/carregando.css" rel="stylesheet" />
</head>
<body>


<?php
session_start();
include 'conexao.php';


    $user = $_POST['txtuser'];
    $senha = $_POST['txtsenha'];
    $email = $_POST['txtemail'];

	    $entrada = "SELECT email_usuario, senha_usuario FROM usuario WHERE email_usuario = '$email'";

        $cadastro =  mysqli_query($conn,$entrada);

        $row = mysqli_num_rows($cadastro);

        if ($row == 0) {

            $_SESSION['user_cad'] = $user;
            $_SESSION['email_cad'] = $email;
        
            $senha = hash('sha256', $senha);
            $ins_cad = "insert into usuario (nome_usuario, email_usuario, senha_usuario) values('$user', '$email', '$senha');";
            $cad =  mysqli_query($conn,$ins_cad);
            

        echo"	
        <div id='loadingOverlay'>
            <div id='loadingCard'>
            <h1>Administra</h1>
            <img src='https://i.gifer.com/YCZH.gif' alt='Carregando...' />
            <strong><p class='mt-3'>Cadastro feito com sucesso</p></strong>
            </div>
        </div>
        ";
            
            header("refresh: 2; url=../HTML/login.html");
        }
        else{

        echo"	
        <div id='loadingOverlay'>
            <div id='loadingCard'>
            <h1>Administra</h1>
            <img src='https://i.gifer.com/YCZH.gif' alt='Carregando...' />
            <strong><p class='mt-3'>Cadastro já existente</p></strong>
            </div>
        </div>
        ";

            header("refresh: 2; url= ../HTML/cadastro.html");

        }




?>


</body>
</html>