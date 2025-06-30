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
      <a class="navbar-brand fw-bold" href="index.html">Administra</a>
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
            <a class="nav-link" href="tela_dividas.html">Dívidas</a>
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
              <th>Fonte de Renda</th>
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
        <td>$nomeRenda</td>
        <td class='text-center'>
          <form action='excluiFonteRenda.php' method='POST' style='display:inline;'>
            <input type='hidden' name='idFonte' value='$idFonte'>
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
                <th>Data</th>
                <th>Tipo de Renda</th>
                <th>Valor (R$)</th>
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
          <td>$dataRenda</td>
          <td>$fonteRenda</td>
          <td>R$ $valorRenda</td>
          <td class='text-center'>
            <form action='excluiRenda.php' method='POST' style='display:inline;'>
              <input type='hidden' name='idRenda' value='$idRenda'>
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

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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