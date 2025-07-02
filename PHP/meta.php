<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Administra</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

  <!-- Ícones Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />

  <!-- CSS personalizado -->
  <link href="principal.css" rel="stylesheet" />
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
          <a class="nav-link" href="dependentes.html">Cadastro Dependentes</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="despesa.php">Despesas</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="divida.php">Dívidas</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="renda.php">Renda</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="meta.php">Metas</a>
        </li>
        <li class="nav-item">
            <a class="nav-link">Sair</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<?php
session_start();
include 'conexao.php';

	$id = $_SESSION['ID_USER'];

echo"
    <!-- CADASTRO DE METAS -->
    <div class='container mt-4'>
    <div class='form-section bg-white p-4 rounded shadow-sm'>
        <h3>Cadastrar Meta</h3>
          <form class='formMeta' action='cadastraMeta.php' method='post'>
        <!-- Objetivo -->
        <label for='objetivo' class='mt-3'>Objetivo:</label>
        <input type='text' name='txtObjetivo' id='objetivo' class='form-control' placeholder='Descreva o objetivo da meta' required />

        <!-- Valor Inicial -->
        <label for='valorInicial' class='mt-3'>Aporte Inicial (R$):</label>
        <input type='number' name='numAporteInicial' id='valorInicial' class='form-control' min='0' step='0.01' required />

        <!-- Valor Final -->
        <label for='valorFinal' class='mt-3'>Valor Final (R$):</label>
        <input type='number' name='numValorFinal' id='valorFinal' class='form-control' min='0' step='0.01' required />

        <button type='submit' class='btn btn-success mt-4'>Salvar Meta</button>
        </form>
    </div>
    </div>
";

?>