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


		echo "	
			<h1>Entrando</h1>
		";

		header("refresh: 2; url=../HTML/cadastratrecos.html");

	} 
    else {
		echo "<h1>Usuário ou senha inválidos</h1>";
        header("refresh: 2; url=../HTML/login1.html");
    }

?>

</body>
</html>
