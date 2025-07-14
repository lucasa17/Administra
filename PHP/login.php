<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Administra - Login</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="../CSS/carregando.css" rel="stylesheet" />
  

</head>
<body>

<?php
session_start();
include 'conexao.php';

	$email_user = $_POST['txtemail'];

    $email = mysqli_real_escape_string($conn, $email_user);
    $password = mysqli_real_escape_string($conn, $_POST['txtpassword']);


//echo "$email <br> <br>";
//echo "$password";

    $password = hash('sha256', $password);

	$entrada = "SELECT email_usuario, senha_usuario FROM usuario WHERE email_usuario = '$email' AND senha_usuario = '$password'";

    $login = mysqli_query($conn, $entrada);

    $row = mysqli_num_rows($login);

 
	if ($row == 1) {
 
		$_SESSION['EMAIL_USER'] = $email;

        $id_user = "select id_usuario from usuario where email_usuario = '$email'";   

        $id = mysqli_query($conn, $id_user);

        $row = mysqli_fetch_assoc($id);

        //echo $row['id_usuario'];
		
        $_SESSION['ID_USER'] = $row['id_usuario'];


		echo"	
        <div id='loadingOverlay'>
            <div id='loadingCard'>
            <h1>Administra</h1>
            <img src='https://i.gifer.com/YCZH.gif' alt='Carregando...' />
            <strong><p class='mt-3'>Entrando...</p></strong>
            </div>
        </div>
        ";
        header("refresh: 2; url=visaoGeral.php");
	} 
    else {

        echo"
        <div class='container d-flex justify-content-center align-items-center' style='min-height: 80vh;'>
            <div id='loadingCard'>
            <h1>Administra</h1>
            <img src='https://i.gifer.com/YCZH.gif' alt='Carregando...' />
            <strong><p class='mt-3'>Usuário ou senha inválidos</p></strong>
            </div>
        </div>
        ";
        header("refresh: 2; url=../HTML/login.html");
    }

?>

</body>
</html>
