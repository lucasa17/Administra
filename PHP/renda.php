<?php
session_start();
include 'conexao.php';

if(empty($_SESSION['ID_USER'])){

  echo"	
      <div id='loadingOverlay'>
          <div id='loadingCard'>
          <h1>Administra</h1>
          <img src='https://cdn.dribbble.com/users/2469324/screenshots/6538803/comp_3.gif' alt='Carregando...' />
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
  <title>Administra</title>

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
    </div>
  </nav>

  <?php
  
  $id = $_SESSION['ID_USER'];

    echo "
    <div class='container mt-4'>
        <div class='form-section bg-white p-4 rounded shadow-sm'>
        <h3>Cadastrar Renda</h3>
        <form class='form' action='../PHP/cadastraRenda.php' method='post'>

            <label for='tipoRenda' class='mt-3'>Fonte de Renda:</label>
            <select name='txtRenda' id='tipoRenda' class='form-select' required>
            <option value=''>--Selecione--</option>
    ";

  $tipoRenda = "SELECT * from FonteRenda where fk_usuario = $id or fk_usuario is null order by fonte_da_renda asc";
  $queryRenda = mysqli_query($conn, $tipoRenda);
  while($pegaRenda = mysqli_fetch_assoc($queryRenda)) {
    $nomeRenda = $pegaRenda['fonte_da_renda'];
    $idRenda = $pegaRenda['id_renda'];

    echo "<option value='$idRenda'>$nomeRenda</option>";
  }

  echo "
    <option value='Outro'>Outro...</option>
    </select>

    <div id='novoTipoWrapper' class='mt-2' style='display: none;'>
      <label for='novoTipo'>Digite a nova fonte de renda:</label>
      <input type='text' name='txtNovaRenda' id='novoTipo' class='form-control' placeholder='Ex: Salário' />
    </div>

    <label for='valor' class='mt-3'>Valor total (R$):</label>
    <input type='number' name='numValor' id='valor' class='form-control' min='0' step='0.01' required />
            
    <label for='data' class='mt-3'>Data de recebimento:</label>
    <input type='date' name='txtData' id='data' class='form-control' required />

    <button type='submit' class='btn btn-success mt-4'>Salvar Renda</button>
  </form>
  </div>
  </div>
  ";

  $queryFonte = mysqli_query($conn, "SELECT * FROM FonteRenda WHERE fk_usuario = $id OR fk_usuario IS NULL ORDER BY fonte_da_renda ASC");

  echo "
  <div class='container mt-4'>
    <div class='form-section bg-white p-4 rounded shadow-sm'>
      <h3>Fontes de Renda Cadastradas</h3>
      <div class='table-responsive'>
        <table class='table table-bordered align-middle'>
          <thead class='table-light'>
            <tr>
              <th class='text-center'>Fonte de Renda</th>
              <th class='text-center'>Excluir</th>
            </tr>
          </thead>
          <tbody>
  ";
  
  while ($fonte = mysqli_fetch_assoc($queryFonte)) {
    $nomeRenda = htmlspecialchars($fonte['fonte_da_renda']);
    $idFonte = $fonte['id_renda'];
  
echo "
  <tr>
    <td class='text-center'>$nomeRenda</td>
    <td class='text-center'>
      <form action='excluiFonteRenda.php' method='POST' style='display:inline;'>
        <input type='hidden' name='idRenda' value='$idFonte'>
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

    $selectRendas = "SELECT * FROM renda WHERE fk_usuario = $id";
    $queryRendas = mysqli_query($conn, $selectRendas);


    echo "
    <div class='container mt-4'>
      <div class='form-section bg-white p-4 rounded shadow-sm'>
        <h3>Rendas Cadastradas</h3>
        <div class='table-responsive'>
          <table class='table table-bordered align-middle'>
            <thead class='table-light'>
              <tr>
                <th class='text-center'>Data</th>
                <th class='text-center'>Tipo de Renda</th>
                <th class='text-center'>Valor (R$)</th>
                <th class='text-center'>Editar</th>
                <th class='text-center'>Excluir</th>
              </tr>
            </thead>
            <tbody id='resultadoTabela'>
    ";
    
    while ($renda = mysqli_fetch_assoc($queryRendas)) {
      $valorRenda = number_format($renda['valor_renda'], 2, ',', '.'); // Formata para 2 casas decimais, vírgula decimal
      $dataRenda = date("d/m/Y", strtotime($renda['data_renda'])); // Formato dd/mm/aaaa
      $idRenda = $renda['id_renda'];
      $fonteRendaId = $renda['fk_fonte'];
      
      $fonteQuery = "SELECT fonte_da_renda FROM FonteRenda WHERE id_renda = $fonteRendaId";
      $fonteResult = mysqli_query($conn, $fonteQuery);
      $fonteRenda = mysqli_fetch_assoc($fonteResult)['fonte_da_renda'];
    echo "
  <tr>
    <td class='text-center'>$dataRenda</td>
    <td class='text-center'>$fonteRenda</td>
    <td class='text-center'>R$ $valorRenda</td>
    <td class='text-center'>
      <button type='button' class='btn btn-sm btn-primary'
        onclick='abrirModalEdicao(this)'
        data-id='$idRenda'
        data-data='{$renda['data_renda']}'
        data-valor='{$renda['valor_renda']}'
        data-fonte-id='$fonteRendaId'>
        <i class='bi bi-pencil-square'></i>
      </button>
    </td>
    <td class='text-center'>
      <form action='excluiRenda.php' method='POST' style='display:inline;'>
        <input type='hidden' name='idRenda' value='$idRenda'>
        <button type='submit' class='btn btn-danger btn-sm'>        
          <i class='bi bi-trash'></i>
        </button>
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

<!-- MODAL DE EDIÇÃO DE RENDA -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="POST" action="editarRenda.php">
      <div class="modal-header">
        <h5 class="modal-title">Editar Renda</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="idRenda" id="editIdRenda">

        <label for="editData" class="form-label">Data</label>
        <input type="date" name="data" id="editData" class="form-control" required>

        <label for="editFonte" class="form-label mt-3">Fonte de Renda</label>
        <select name="fonte" id="editFonte" class="form-select" required>
          <option value="">--Selecione--</option>
          <?php
            $querySelectFontes = mysqli_query($conn, "SELECT * FROM FonteRenda WHERE fk_usuario = $id OR fk_usuario IS NULL ORDER BY fonte_da_renda ASC");
            while ($fonte = mysqli_fetch_assoc($querySelectFontes)) {
              echo "<option value='{$fonte['id_renda']}'>{$fonte['fonte_da_renda']}</option>";
            }
          ?>
        </select>

        <label for="editValor" class="form-label mt-3">Valor</label>
        <input type="number" name="valor" id="editValor" class="form-control" step="0.01" required>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Salvar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </form>
  </div>
</div>


  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
function abrirModalEdicao(botao) {
  const id = botao.getAttribute('data-id');
  const data = botao.getAttribute('data-data');
  const valor = botao.getAttribute('data-valor');
  const fonteId = botao.getAttribute('data-fonte-id');

  document.getElementById('editIdRenda').value = id;
  document.getElementById('editData').value = data;
  document.getElementById('editValor').value = valor;
  document.getElementById('editFonte').value = fonteId;

  new bootstrap.Modal(document.getElementById('modalEditar')).show();
}
</script>


  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const selectPagameto = document.querySelector('select[name="txtRenda"]');
      const inputNovoPagamento = document.querySelector('input[name="txtNovaRenda"]');

      if (selectPagameto && inputNovoPagamento) {
        selectPagameto.addEventListener('change', function () {
          // Sempre que o usuário mudar a categoria selecionada,
          // limpamos o campo de nova categoria.
          inputNovoPagamento.value = '';
        });
      }
    });
    </script>
  <!-- Script funcional -->
  <script>
    const tipoRenda = document.getElementById("tipoRenda");
    const novoTipoWrapper = document.getElementById("novoTipoWrapper");
    const novoTipoInput = document.getElementById("novoTipo");

    // Exibir o campo "Outro" quando selecionado
    tipoRenda.addEventListener("change", () => {
      novoTipoWrapper.style.display = tipoRenda.value === "Outro" ? "block" : "none";
      novoTipoInput.required = tipoRenda.value === "Outro";
    });

    // Quando o formulário for enviado, se o "Outro" for selecionado, adicionar o valor do input como nova opção
    document.getElementById("formRenda").addEventListener("submit", function (e) {
      if (tipoRenda.value === "Outro" && novoTipoInput.value.trim() !== "") {
        const novaOpcao = new Option(novoTipoInput.value, novoTipoInput.value, true, true);
        tipoRenda.add(novaOpcao);  // Adiciona a nova opção ao select
        tipoRenda.value = novoTipoInput.value; // Define a nova opção como selecionada
      }
    });

    // Iniciar o estado do campo "Outro" ao carregar a página
    document.addEventListener("DOMContentLoaded", function() {
      novoTipoWrapper.style.display = tipoRenda.value === "Outro" ? "block" : "none";
      novoTipoInput.required = tipoRenda.value === "Outro";
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