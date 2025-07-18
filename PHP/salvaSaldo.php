<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Administra - Visão Geral</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="../CSS/carregando.css" rel="stylesheet" />

  <!-- Ícones Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />

  <!-- CSS personalizado -->
  <link href="../CSS/principal.css" rel="stylesheet" />
</head>
<body style="padding-top: 80px;">
<?php
session_start();
include 'conexao.php';

$id = $_SESSION['ID_USER'];
    
    $idMeta = intval($_POST['id_poupanca']);
    $saldoAtual = floatval($_POST['saldoAtual']);
    $valor = floatval($_POST['valor_aplicado']);
    $valorTotal = floatval($_POST['valor_total_disponivel']);
    $mes = $_POST['mes'];

    //echo"$saldoAtual, $valor, $valorTotal";
    if ($valor+$saldoAtual <= $valorTotal && $valor > 0) {
        $upMeta = "UPDATE poupanca SET valor_atual = valor_atual + $valor, meses_ate_meta = ($valorTotal-$valor)/$valor WHERE id_poupanca = $idMeta AND fk_usuario = $id";
        mysqli_query($conn, $upMeta);
        
        $upMensal = "UPDATE resumoMensal SET saldo_meta = saldo_meta + $valor, saldo = saldo - $valor where fk_usuario = $id and mes = $mes";
        mysqli_query($conn, $upMensal);
        header("refresh: 0; url=visaoGeral.php");
    }
    else{
        echo"	
          <div id='loadingOverlay'>
              <div id='loadingCard'>
              <h1>Administra</h1>
              <img src='../IMAGENS/alerta.gif' alt='Carregando...' />
              <strong><p class='mt-3'>Valor Inválido</p></strong>
              </div>
          </div>
          ";        
        header("refresh: 3.5; url=visaoGeral.php");
    }

?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
