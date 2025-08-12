<?php
session_start();
include 'conexao.php';
  mysqli_set_charset($conn, 'utf8');

if(empty($_SESSION['ID_USER'])){

  echo"	
      <div id='loadingOverlay'>
          <div id='loadingCard'>
          <h1>Administra</h1>
          <img src='../IMAGENS/alerta.gif' alt='Carregando...' />
          <strong><p class='mt-3'>Usuário não esta logado</p></strong>
          </div>
      </div>
      ";        
  header("refresh: 3.5; url=../index.html");
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Administra - Despesa</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="../CSS/carregando.css" rel="stylesheet" />

  <!-- Ícones Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />

  <!-- CSS personalizado -->
  <link href="../CSS/principal.css" rel="stylesheet" />
</head>
<body style="padding-top: 80px;">

  <!-- HEADER -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow">
  <div class="container">
  <a class="navbar-brand fw-bold" href="">Administra</a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
            <a class="nav-link" href="visaoGeral.php">Visão Geral</a>
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
          <a class="nav-link" href="dependente.php">Dependentes</a>
        </li>
        <li class="nav-item">
            <form action='sair.php' method='POST' style='display:inline;'>
            <button type='submit' class='btn btn-danger btn-sm' Style='Background-color:Red; Border-radius: 20%'>Sair</button>
          </form>        
        </li>
      </ul>
    </div>
</nav>

<?php

	$id = $_SESSION['ID_USER'];
  echo"
    <div class='container mt-4'>
    <div class='form-section bg-white p-4 rounded shadow-sm'>
    <h3>Cadastrar Despesa</h3>

    <form id='formGasto' class='form' action='../PHP/cadastraDespesa.php' method='post'>
      <label for='despesa' class='mt-3'>Nome da Despesa:</label>
      <input type='text' name='txtnomedespesa' id='despesa' class='form-control' rows='3' placeholder='Digite a despesa'></input>

    <label for='Dependente'>Dependente:</label>
    <select name='txtdependente' id='dependente' class='form-select'>
      <option value=''>Nenhum</option>
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

     $categoria = "SELECT nome_categoria, MIN(id_categoria) AS id_categoria_unica
              FROM categoria
              WHERE fk_usuario = $id OR fk_usuario IS NULL AND nome_categoria != 'Metas'
              GROUP BY nome_categoria
              ORDER BY nome_categoria ASC";

    $queryCategoria = mysqli_query($conn, $categoria);

    while($pegaCategoria = mysqli_fetch_assoc($queryCategoria)) {
      $nomeCategoria = $pegaCategoria['nome_categoria'];
      $idCategoria = $pegaCategoria['id_categoria_unica'];
      echo "<option value='$idCategoria'>$nomeCategoria</option>";
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

   $tipoPagamento = "SELECT nome_pagamento, MIN(id_pagamento) AS id_pagamento_unico
                  FROM tipopagamento
                  WHERE fk_usuario = $id OR fk_usuario IS NULL
                  GROUP BY nome_pagamento
                  ORDER BY nome_pagamento ASC";

    $queryPagamento = mysqli_query($conn, $tipoPagamento);

    // Assumindo que você está dentro de um <select>
    while($pegaPagamento = mysqli_fetch_assoc($queryPagamento)){
        $nomePagamento = $pegaPagamento['nome_pagamento'];
        $idPagamento = $pegaPagamento['id_pagamento_unico']; // Usamos o ID único agrupado
        echo "<option value='$idPagamento'>$nomePagamento</option>";
    }

    echo"
          <option value='Outro'>Outro...</option>
        </select>

        <div id='novoTipoWrapper' class='mt-2' style='display: none;'>
          <label for='novoTipo'>Digite o novo tipo de pagamento:</label>
          <input type='text' name='txtNovoTipoPagamento' id='novoTipo' class='form-control' placeholder='Ex: Transferência Internacional' />
        </div>
    ";

echo "
  <label for='valor' class='mt-3'>Valor total (R$):</label>
  <input type='number' name='numValor' id='valor' class='form-control' min='0' step='0.01' required />

  <label for='data' class='mt-3'>Data do gasto:</label>
  <input type='date' name='txtData' id='data' class='form-control' required />

  <button type='submit' class='btn btn-success mt-4'>Salvar Gasto</button>
</form>
</div>
";

?>
    </div>


<!-- Filtro de dívidas -->
<div class="container mt-4">
  <div class="form-section bg-white p-4 rounded shadow-sm">
    <h3>Filtrar Despesa</h3>
    <form id="filtro" class="row g-3">
      <div class="col-md-6">
        <label for="filtroDependente" class="form-label">Dependente:</label>
        <select id="filtroDependente" class="form-select">
          <option value="">--Todos--</option>
          <?php
          // Buscar dependentes do usuário para popular o select
          $dependentes = mysqli_query($conn, "SELECT id_dependente, nome_dependente FROM dependente WHERE fk_usuario = $id ORDER BY nome_dependente ASC");
          while ($dep = mysqli_fetch_assoc($dependentes)) {
            echo "<option value='{$dep['id_dependente']}'>{$dep['nome_dependente']}</option>";
          }
          ?>
        </select>
      </div>

      <div class="col-md-6">
        <label for="filtroCategoria" class="form-label">Categoria:</label>
        <select id="filtroCategoria" class="form-select">
          <option value="">--Todas--</option>

          <?php
          $categoriaSel = "SELECT id_categoria, nome_categoria FROM categoria 
          WHERE fk_usuario = $id OR fk_usuario IS NULL
          ORDER BY nome_categoria ASC";

          $categorias = mysqli_query($conn,$categoriaSel);
          while ($cat = mysqli_fetch_assoc($categorias)) {
            echo "<option value='{$cat['id_categoria']}'>{$cat['nome_categoria']}</option>";
          }
          ?>

        </select>
      </div>
      <div class="col-md-6">
        <label for="filtroValor" class="form-label">Valor Mínimo (R$):</label>
        <input type="number" id="filtroValor" class="form-control" min="0" step="0.01" />
      </div>
      <div class="col-md-6">
       <label for="filtroMes" class="form-label">Mês:</label>
      <select id="filtroMes" class="form-select">
        <option value="">--Todos--</option>
        <option value="01">Janeiro</option>
        <option value="02">Fevereiro</option>
        <option value="03">Março</option>
        <option value="04">Abril</option>
        <option value="05">Maio</option>
        <option value="06">Junho</option>
        <option value="07">Julho</option>
        <option value="08">Agosto</option>
        <option value="09">Setembro</option>
        <option value="10">Outubro</option>
        <option value="11">Novembro</option>
        <option value="12">Dezembro</option>
      </select>
      </div>

      <div class="col-12 text-end">
        <button type="submit" class="btn btn-primary mt-3">Buscar</button>
        <button type="button" id="limparFiltros" class="btn btn-secondary mt-3 ms-2">Limpar</button>
      </div>
    </form>
  </div>
</div>


<?php
$selectDespesas = "SELECT d.*, cat.nome_categoria, tp.nome_pagamento
                   FROM despesa d
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
      <table id='tabelaDespesas' class='table table-bordered align-middle'>
        <thead class='table-light'>
          <tr>
            <th>Data</th>
            <th>Nome da Despesa</th>
            <th>Dependente</th>
            <th>Categoria</th>
            <th>Tipo Pagamento</th>
            <th>Valor (R$)</th>
            <th class='text-center'>Editar</th>
            <th class='text-center'>Excluir</th>
          </tr>
        </thead>
        <tbody>
";

error_reporting(0);
$totalDespesas = 0;
while ($despesa = mysqli_fetch_assoc($queryDespesas)) {
    $idDependente = $despesa['fk_dependente'];
    
    $selectDependente = "select * from dependente where id_dependente = $idDependente";
    $queryNomeDependente = mysqli_query($conn, $selectDependente);
    $buscaDependente = mysqli_fetch_assoc($queryNomeDependente);
    $nomeDependente = $buscaDependente['nome_dependente'] ?? '-';

    $dataDespesa = date("d/m/Y", strtotime($despesa['data_despesa']));
    $nomeDespesa = htmlspecialchars($despesa['nome_despesa']);
    $nomeCategoria = $despesa['nome_categoria'] ?? '-';
    $nomePagamento = $despesa['nome_pagamento'] ?? '-';
    $valorDespesaFloat = floatval($despesa['valor_despesa']);
    $valorDespesa = number_format($valorDespesaFloat, 2, ',', '.');
    $idDespesa = $despesa['id_despesa'];

    $totalDespesas += $valorDespesaFloat;

    echo "
      <tr>
        <td>$dataDespesa</td>
        <td>$nomeDespesa</td>
        <td>$nomeDependente</td>
        <td>$nomeCategoria</td>
        <td>$nomePagamento</td>
        <td>R$ $valorDespesa</td>
        <td class='text-center'>
          <button type='button' class='btn btn-warning btn-sm' onclick='abrirModalEdicao(
              $idDespesa, 
              \"".addslashes($nomeDespesa)."\", 
              \"$despesa[fk_dependente]\", 
              \"$despesa[fk_categoria]\", 
              \"$despesa[fk_tipo_pagamento]\", 
              \"$despesa[valor_despesa]\", 
              \"$despesa[data_despesa]\")'>
            Editar
          </button>
        </td>
        <td class='text-center'>
          <form action='excluiDespesa.php' method='POST' style='display:inline;' onsubmit='return confirm(\"Deseja realmente excluir esta despesa?\");'>
            <input type='hidden' name='idDespesa' value='$idDespesa'>
            <button type='submit' class='btn btn-danger btn-sm'>Excluir</button>
          </form>
        </td>
      </tr>
    ";
}

$valorTotalFormatado = number_format($totalDespesas, 2, ',', '.');

echo "
        </tbody>
      </table>
    </div>

    <!-- Total de despesas -->
  <div class='container mt-3'>
    <div class='alert alert-info text-end fw-bold'>
      Total de Despesas: R$ $valorTotalFormatado 
    </div>
  </div>
</div>
</div>
</div>
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
</script>

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

<!-- MODAL EDIÇÃO DESPESA -->
<div class="modal fade" id="modalEditarDespesa" tabindex="-1" aria-labelledby="editarDespesaLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="editarDespesa.php" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="editarDespesaLabel">Editar Despesa</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="idDespesa" id="editIdDespesa" value='$idDespesa'>

          <label for="editNome" class="form-label">Nome da Despesa:</label>
          <input type="text" class="form-control" name="editNome" id="editNome" required>

          <label for="editDependente" class="form-label mt-3">Dependente:</label>
          <select class="form-select" name="editDependente" id="editDependente">
            <option value=''>Nenhum</option>
            <?php
              $deps = mysqli_query($conn, "SELECT * FROM dependente WHERE fk_usuario = $id ORDER BY nome_dependente ASC");
              while ($d = mysqli_fetch_assoc($deps)) {
                echo "<option value='{$d['id_dependente']}'>{$d['nome_dependente']}</option>";
              }
            ?>
          </select>

          <label for="editCategoria" class="form-label mt-3">Categoria:</label>
          <select class="form-select" name="editCategoria" id="editCategoria">
            <?php
              $cats = mysqli_query($conn, "SELECT * FROM categoria 
              WHERE (fk_usuario = $id OR fk_usuario IS NULL) AND id_categoria != 4
              ORDER BY nome_categoria ASC");
              while ($c = mysqli_fetch_assoc($cats)) {
                echo "<option value='{$c['id_categoria']}'>{$c['nome_categoria']}</option>";
              }
            ?>
          </select>

          <label for="editPagamento" class="form-label mt-3">Tipo de Pagamento:</label>
          <select class="form-select" name="editPagamento" id="editPagamento">
            <?php
              $pags = mysqli_query($conn, "SELECT * FROM tipopagamento WHERE fk_usuario = $id OR fk_usuario IS NULL ORDER BY nome_pagamento ASC");
              while ($p = mysqli_fetch_assoc($pags)) {
                echo "<option value='{$p['id_pagamento']}'>{$p['nome_pagamento']}</option>";
              }
            ?>
          </select>

          <label for="editValor" class="form-label mt-3">Valor Total (R$):</label>
          <input type="number" class="form-control" name="editValor" id="editValor" step="0.01" required>

          <label for="editData" class="form-label mt-3">Data:</label>
          <input type="date" class="form-control" name="editData" id="editData" required>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-success">Salvar Alterações</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  function abrirModalEdicao(id, nome, dependente, categoria, pagamento, valor, data) {
    document.getElementById("editIdDespesa").value = id;
    document.getElementById("editNome").value = nome;
    document.getElementById("editDependente").value = dependente;
    document.getElementById("editCategoria").value = categoria;
    document.getElementById("editPagamento").value = pagamento;
    document.getElementById("editValor").value = valor;
    document.getElementById("editData").value = data;

    const modal = new bootstrap.Modal(document.getElementById('modalEditarDespesa'));
    modal.show();
  }
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



}
</script>

  <script>
document.getElementById("filtro").addEventListener("submit", function(e) {
  e.preventDefault();

  // Valores dos filtros
  const filtroValor = parseFloat(document.getElementById("filtroValor").value);
  const filtroMes = document.getElementById("filtroMes").value;
  const filtroDependente = document.getElementById("filtroDependente").value;
  const filtroCategoria = document.getElementById("filtroCategoria").value;

  // Todas as linhas da tabela
  const linhas = document.querySelectorAll("#tabelaDespesas tbody tr");

  linhas.forEach(linha => {
    // Valor na coluna 5 (índice 5)
    const valorTexto = linha.children[5].textContent.replace("R$", "").trim().replace(/\./g, "").replace(",", ".");
    const valor = parseFloat(valorTexto);

    // Data na coluna 0 para pegar mês
    const data = linha.children[0].textContent.split("/");
    const mesLinha = data[1];

    // Dependente na coluna 2 e categoria na coluna 3
    const dependenteLinha = linha.children[2].textContent.trim();
    const categoriaLinha = linha.children[3].textContent.trim();

    let mostrar = true;

    if (!isNaN(filtroValor) && valor < filtroValor) mostrar = false;
    if (filtroMes && filtroMes !== mesLinha) mostrar = false;

    // Se filtroDependente não vazio, verifica se bate com o texto da célula
    if (filtroDependente && filtroDependente !== "") {
      // Aqui vamos comparar pelo texto da célula, para isso precisamos do nome do dependente selecionado.
      const selectDep = document.getElementById("filtroDependente");
      const nomeFiltroDep = selectDep.options[selectDep.selectedIndex].text;
      if (dependenteLinha !== nomeFiltroDep) mostrar = false;
    }

    // Mesmo para categoria
    if (filtroCategoria && filtroCategoria !== "") {
      const selectCat = document.getElementById("filtroCategoria");
      const nomeFiltroCat = selectCat.options[selectCat.selectedIndex].text;
      if (categoriaLinha !== nomeFiltroCat) mostrar = false;
    }

    linha.style.display = mostrar ? "" : "none";
  });
});

document.getElementById("limparFiltros").addEventListener("click", function() {
  document.getElementById("filtroValor").value = "";
  document.getElementById("filtroMes").value = "";
  document.getElementById("filtroDependente").value = "";
  document.getElementById("filtroCategoria").value = "";

  const linhas = document.querySelectorAll("#tabelaDespesas tbody tr");
  linhas.forEach(linha => {
    linha.style.display = "";
  });
});
</script>


<!-- FOOTER -->
<footer class="mt-4">
<div class="container text-center">
  <p class="mb-1">© 2025 Administra - Todos os direitos reservados</p>
</div>
</footer>

</body>
</html>
