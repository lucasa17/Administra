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
          <a class="nav-link" href="login.html">Login</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="dependentes.php">Cadastro Dependentes</a>
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
      </ul>
    </div>
  </div>
</nav>

<?php
session_start();
include 'conexao.php';

	$id = $_SESSION['ID_USER'];
    
    echo"
    <!-- CADASTRO DE DÍVIDAS -->
    <div class='container mt-4'>
      <div class='form-section bg-white p-4 rounded shadow-sm'>
        <h3>Cadastrar Dívida</h3>
        <form class='form' action='../PHP/cadastraDivida.php' method='post'>
        <label for='despesa' class='mt-3'>Nome da Dívida:</label>
        <input type='text' name='txtnomedivida' id='despesa' class='form-control' rows='3' placeholder='Digite a despesa'></input>
        
          <label for='pessoa'>Credor:</label>
          <input type='text' name='txtCredor' id='novaCategoria' class='form-control' placeholder='Ex: Banco do Brasil' />
    
          <!-- Categoria -->
          <label for='categoria' class='mt-3'>Categoria:</label>
          <select name='txtCategoria' id='categoria' class='form-select' required>
            <option value=''>--Selecione--</option>
    ";

    
    $categoria = "SELECT * FROM categoriaDivida WHERE fk_usuario = $id OR fk_usuario IS NULL ORDER BY nome_categoria ASC";
    $queryCategoria = mysqli_query($conn, $categoria);

    while($pegaCategoria = mysqli_fetch_assoc($queryCategoria)) {
      $nomeCategoria = $pegaCategoria['nome_categoria'];
      $idCategoria = $pegaCategoria['id_categoria'];
      echo "<option value='$idCategoria'>$nomeCategoria</option>";
    }
        echo "
            <option value='Outra'>Outra...</option>
            </select>

            <div id='novaCategoriaWrapper' class='mt-2' style='display: none;'>
            <label for='novaCategoria'>Digite a nova categoria:</label>
            <input type='text' name='txtNovaCategoria' id='novaCategoria' class='form-control' placeholder='Ex: Educação, Saúde...' />
            </div>
        ";
        echo "
            <label for='tipoPagamento' class='mt-3'>Tipo de Pagamento:</label>
            <select name='txtTipoPagamento' id='tipoPagamento' class='form-select' required>
            <option value=''>--Selecione--</option>
            ";
            
                $tipoPagamento = "SELECT * from tipopagamento where fk_usuario = $id or fk_usuario is null order by nome_pagamento asc";
                $queryPagamento = mysqli_query($conn, $tipoPagamento);
            
            while($pegaPagamento = mysqli_fetch_assoc($queryPagamento)){
                $nomePagamento= $pegaPagamento['nome_pagamento'];
                $idPagamento = $pegaPagamento['id_pagamento'];
                echo "
                    <option value='$idPagamento'>$nomePagamento</option>";
                }
                echo "
                    <option value='Outro'>Outro...</option>
                    </select>
                    
                    <div id='novoTipoWrapper' class='mt-2' style='display: none;'>
                    <label for='novoTipo'>Digite o novo tipo de pagamento:</label>
                    <input type='text' name='txtNovoTipoPagamento' id='novoTipo' class='form-control' placeholder='Ex: Transferência Internacional' />
                    </div>
        ";
        echo"
                <label for='valor' class='mt-3'>Valor total (R$):</label>
                <input type='number' name='numValor' id='valor' class='form-control' min='0' step='0.01' required />
 
                <label for='parcelas' class='mt-3'>Número de parcelas:</label>
                <input type='number' name='numParcelas' id='parcelas' class='form-control' min='1' required />

                <label for='data' class='mt-3'>Data de vencimento da primeira parcela:</label>
                <input type='date' name='txtData' id='data' class='form-control' required />

                <button type='submit' class='btn btn-success mt-4'>Salvar Dívida</button>
            </form>
            </div>
        </div>
    ";
?>


  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Script funcional -->
  <script>
    const tipoPagamento = document.getElementById("tipoPagamento");
    const novoTipoWrapper = document.getElementById("novoTipoWrapper");
    const novoTipoInput = document.getElementById("novoTipo");

    tipoPagamento.addEventListener("change", () => {
      novoTipoWrapper.style.display = tipoPagamento.value === "Outro" ? "block" : "none";
      novoTipoInput.required = tipoPagamento.value === "Outro";
    });

    const categoria = document.getElementById("categoria");
    const novaCategoriaWrapper = document.getElementById("novaCategoriaWrapper");
    const novaCategoriaInput = document.getElementById("novaCategoria");

    categoria.addEventListener("change", () => {
      novaCategoriaWrapper.style.display = categoria.value === "Outra" ? "block" : "none";
      novaCategoriaInput.required = categoria.value === "Outra";
    });

    document.getElementById("formGasto").addEventListener("submit", function (e) {
      if (tipoPagamento.value === "Outro" && novoTipoInput.value.trim() !== "") {
        const novaOpcao = new Option(novoTipoInput.value, novoTipoInput.value, true, true);
        tipoPagamento.add(novaOpcao);
      }

      if (categoria.value === "Outra" && novaCategoriaInput.value.trim() !== "") {
        const novaCategoria = new Option(novaCategoriaInput.value, novaCategoriaInput.value, true, true);
        categoria.add(novaCategoria);
      }
    });

    function confirmarExclusao(imgElement) {


  const confirmar = confirm("Deseja realmente excluir este registro?");
  if (confirmar) {
    const linha = imgElement.closest('tr');
    linha.remove();
  }
}


  </script>
  
  <!-- FOOTER -->
  <footer class="mt-4">
  <div class="container text-center">
    <p class="mb-1">© 2025 Administra - Todos os direitos reservados</p>
  </div>
  </footer>

</body>
</html>
