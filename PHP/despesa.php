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
          <a class="nav-link" href="../HTML/login.html">Login</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="dependente.php">Cadastro Dependentes</a>
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
    <div class='container mt-4'>
    <div class='form-section bg-white p-4 rounded shadow-sm'>
    <h3>Cadastrar Despesa</h3>

    <form class='form' action='../PHP/cadastraDespesa.php' method='post'>
      <label for='despesa' class='mt-3'>Nome da Despesa:</label>
      <input type='text' name='txtnomedespesa' id='despesa' class='form-control' rows='3' placeholder='Digite a despesa'></input>

    <label for='Dependente'>Dependente:</label>
    <select name='txtdependente' id='dependente' class='form-select'>
      <option value=''>--Selecione--</option>
  ";
	  $dependentes = "SELECT * from dependente where fk_usuario = $id order by nome_dependente asc";
    $queryDependente = mysqli_query($conn, $dependentes);

    while($pegaDependente = mysqli_fetch_assoc($queryDependente)){

    $nomeDependente = $pegaDependente['nome_dependente'];
    $idDependente = $pegaDependente['id_dependente'];

    echo"
        <option value='$idDependente'>$nomeDependente</option>
    ";
    }
    echo"
        </select>
        <label for='categoria' class='mt-3'>Categoria:</label>
        <select name='txtCategoria' id='categoria' class='form-select' required>
          <option value=''>--Selecione--</option>
    ";

    $categoria = "SELECT * from categoria where fk_usuario = $id or fk_usuario is null order by nome_categoria asc";

    $queryCategoria = mysqli_query($conn, $categoria);

    while($pegaCategoria = mysqli_fetch_assoc($queryCategoria)){

    $nomeCategoria = $pegaCategoria['nome_categoria'];
    $idCategoria = $pegaCategoria['id_categoria'];

    echo"
        <option value='$idCategoria'>$nomeCategoria</option>
    ";
    }

    echo"
          <option value='Outra'>Outra...</option>
        </select>

        <div id='novaCategoriaWrapper' class='mt-2' style='display: none;'>
          <label for='novaCategoria'>Digite a nova categoria:</label>
          <input type='text' name='txtNovaCategoria' id='novaCategoria' class='form-control' placeholder='Ex: Educação, Saúde...' />
        </div>
    ";

    
    echo"
        <label for='tipoPagamento' class='mt-3'>Tipo de Pagamento:</label>
        <select name='txtTipoPagamento' id='tipoPagamento' class='form-select' required>
          <option value=''>--Selecione--</option>
    ";

    $tipoPagamento = "SELECT * from tipopagamento where fk_usuario = $id or fk_usuario is null order by nome_pagamento asc";

    $queryPagamento = mysqli_query($conn, $tipoPagamento);

    while($pegaPagamento = mysqli_fetch_assoc($queryPagamento)){

    $nomePagamento= $pegaPagamento['nome_pagamento'];
    $idPagamento = $pegaPagamento['id_pagamento'];

    echo"
        <option value='$idPagamento'>$nomePagamento</option>
    ";
    }

    echo"
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

        <label for='data' class='mt-3'>Data inicial do gasto:</label>
        <input type='date' name='txtData' id='data' class='form-control'/>

        <button type='submit' class='btn btn-success mt-4'>Salvar Gasto</button>
      </form>
    </div>
  </div>
";


$selectDespesas = "SELECT d.*, dep.nome_dependente, cat.nome_categoria, tp.nome_pagamento
                   FROM despesa d
                   LEFT JOIN dependente dep ON d.fk_dependente = dep.id_dependente
                   LEFT JOIN categoria cat ON d.fk_categoria = cat.id_categoria
                   LEFT JOIN tipopagamento tp ON d.fk_tipo_pagamento = tp.id_pagamento
                   WHERE d.fk_usuario = $id
                   ORDER BY d.data_despesa DESC";

$queryDespesas = mysqli_query($conn, $selectDespesas);

echo "
<div class='container mt-4'>
  <div class='form-section bg-white p-4 rounded shadow-sm'>
    <h3>Despesas Cadastradas</h3>
    <div class='table-responsive'>
      <table class='table table-bordered align-middle'>
        <thead class='table-light'>
          <tr>
            <th>Data</th>
            <th>Nome da Despesa</th>
            <th>Dependente</th>
            <th>Categoria</th>
            <th>Tipo Pagamento</th>
            <th>Valor (R$)</th>
            <th class='text-center'>Excluir</th>
          </tr>
        </thead>
        <tbody>
";

while ($despesa = mysqli_fetch_assoc($queryDespesas)) {
    $dataDespesa = date("d/m/Y", strtotime($despesa['data_despesa']));
    $nomeDespesa = htmlspecialchars($despesa['nome_despesa']);
    $nomeDependente = $despesa['nome_dependente'] ?? '-';
    $nomeCategoria = $despesa['nome_categoria'] ?? '-';
    $nomePagamento = $despesa['nome_pagamento'] ?? '-';
    $valorDespesa = number_format($despesa['valor_despesa'], 2, ',', '.');
    $idDespesa = $despesa['id_despesa'];

    echo "
      <tr>
        <td>$dataDespesa</td>
        <td>$nomeDespesa</td>
        <td>$nomeDependente</td>
        <td>$nomeCategoria</td>
        <td>$nomePagamento</td>
        <td>R$ $valorDespesa</td>
        <td class='text-center'>
          <form action='excluiDespesa.php' method='POST' style='display:inline;'>
            <input type='hidden' name='idDespesa' value='$idDespesa'>
            <button type='submit' class='btn btn-danger btn-sm'>Excluir</button>
          </form>
        </td>
      </tr>
    ";
}

echo "
        </tbody>
      </table>
    </div>
  </div>
</div>
";
?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectCategoria = document.querySelector('select[name="txtCategoria"]');
    const inputNovaCategoria = document.querySelector('input[name="txtNovaCategoria"]');

    if (selectCategoria && inputNovaCategoria) {
        selectCategoria.addEventListener('change', function () {
            // Sempre que o usuário mudar a categoria selecionada,
            // limpamos o campo de nova categoria.
            inputNovaCategoria.value = '';
        });
    }
});

<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectPagameto = document.querySelector('select[name="txtTipoPagamento"]');
    const inputNovoPagamento = document.querySelector('input[name="txtNovoTipoPagamento"]');

    if (selectPagameto && inputNovoPagamento) {
        selectPagameto.addEventListener('change', function () {
            // Sempre que o usuário mudar a categoria selecionada,
            // limpamos o campo de nova categoria.
            inputNovoPagamento.value = '';
        });
    }
});
</script>

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
