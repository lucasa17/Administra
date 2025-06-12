<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Administra - Login</title>
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

	$entrada = "SELECT email_usuario, senha_usuario FROM usuario WHERE email_usuario = '$email' AND senha_usuario = '$password'";

    $login = mysqli_query($conn, $entrada);

    $row = mysqli_num_rows($login);

 
	if ($row == 1) {
 
		$_SESSION['EMAIL_USER'] = $email;
		$_SESSION['SENHA_USER'] = $password;
		

		echo "	
			<h1>Entrando</h1>
		";

		header("refresh: 2; url=entrou.php");

	} 
    else {
		echo "<h1>Usuário ou senha inválidos</h1>";
        header("refresh: 2; url=../HTML/login.html");
    }

?>

</body>
</html>
