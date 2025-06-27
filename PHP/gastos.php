<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Administra - Gastos</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

  <!-- Ícones Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />

  <!-- CSS personalizado -->
  <link href="../CSS/principal.css" rel="stylesheet" />
</head>
<body style="padding-top: 80px;">

  <!-- HEADER -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.html">Administra</a> <!-- Link para página principal -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="gastos.php">Gastos</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../HTML/login.html">Login</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../HTML/cadastro.html">Cadastro</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

  <div class='container mt-4'>
    <div class='form-section bg-white p-4 rounded shadow-sm'>
      <h3>Cadastrar Gasto</h3>
        <form action='cadastraGastos.php' method='post' name='form' id='form'>
        <label for='pessoa'>Dependente:</label>
        <select id='pessoa' class='form-select' required>
          <option value=''>--Selecione--</option>

<?php
session_start();
include 'conexao.php';

	$id = $_SESSION['ID_USER'];

	$dependentes = "SELECT * from dependente where fk_usuario = $id order by nome_dependente asc";
    $queryDependete = mysqli_query($conn, $dependentes);

    while($pega_nome = mysqli_fetch_assoc($queryDependete)){

    $nome = $pega_nome['nome_dependente'];

    echo"
        <option value='$nome'>$nome</option>
    ";
    }
    echo"
        </select>
        <label for='categoria' class='mt-3'>Categoria:</label>
        <select id='categoria' class='form-select' required>
          <option value=''>--Selecione--</option>
          <option value='Alimentação'>Alimentação</option>
          <option value='Transporte'>Transporte</option>
          <option value='Lazer'>Lazer</option>
          <option value='Outra'>Outra...</option>
        </select>

        <div id='novaCategoriaWrapper' class='mt-2' style='display: none;'>
          <label for='novaCategoria'>Digite a nova categoria:</label>
          <input type='text' id='novaCategoria' class='form-control' placeholder='Ex: Educação, Saúde...' />
        </div>

        <label for='observacoes' class='mt-3'>Observações:</label>
        <textarea id='observacoes' class='form-control' rows='3' placeholder='Digite detalhes sobre o gasto...'></textarea>

        <label for='tipoPagamento' class='mt-3'>Tipo de Pagamento:</label>
        <select id='tipoPagamento' class='form-select' required>
          <option value=''>--Selecione--</option>
          <option value='Pix'>Pix</option>
          <option value='Dinheiro'>Dinheiro</option>
          <option value='Cartão Débito'>Cartão Débito</option>
          <option value='Cartão Crédito'>Cartão Crédito</option>
          <option value='Cheque'>Cheque</option>
          <option value='Outro'>Outro...</option>
        </select>

        <div id='novoTipoWrapper' class='mt-2' style='display: none;'>
          <label for='novoTipo'>Digite o novo tipo de pagamento:</label>
          <input type='text' id='novoTipo' class='form-control' placeholder='Ex: Transferência Internacional' />
        </div>

        <label for='valor' class='mt-3'>Valor total (R$):</label>
        <input type='number' id='valor' class='form-control' min='0' step='0.01' required />

        <label for='parcelas' class='mt-3'>Número de parcelas:</label>
        <input type='number' id='parcelas' class='form-control' min='1' required />

        <label for='data' class='mt-3'>Data inicial do gasto:</label>
        <input type='date' id='data' class='form-control' required />

        <button type='submit' class='btn btn-success mt-4'>Salvar Gasto</button>
      </form>
    </div>
  </div>
";

?>