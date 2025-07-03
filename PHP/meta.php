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
          <a class="nav-link" href="dependente.php">Dependentes</a>
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

  $selectMeta = "SELECT * from poupanca where fk_usuario = $id order by objetivo asc";
  $queryMeta = mysqli_query($conn, $selectMeta);

  echo"
  <div class='container mt-5'>
  <div class='consulta-section bg-white p-4 rounded shadow-sm'>
    <!-- Tabela com resultados -->
    <div class='mt-4'>
      <table class='table table-bordered align-middle'>
        <thead class='table-light'>
          <tr>
            <th>Objetivo</th>
            <th>Valor Inicial (R$)</th>
            <th>Valor Final (R$)</th>
            <th class='text-center'>Editar</th>
            <th class='text-center'>Excluir</th>
          </tr>
        </thead>
  ";

  while($pegaMeta = mysqli_fetch_assoc($queryMeta)) {
    $objetivo = $pegaMeta['objetivo'];
    $valorAtual = $pegaMeta['valor_atual'];
    $valorMeta = $pegaMeta['valor_meta'];
    $idMeta = $pegaMeta['id_poupanca'];

  echo "
<tr data-id-meta='$idMeta'>
  <td>$objetivo</td>
  <td>$valorAtual</td>
  <td>$valorMeta</td>
  <td class='text-center'>
    <button type='submit' class='btn btn-sm btn-danger'>
      <i class='bi bi-pencil-square' style='cursor:pointer; font-size: 1.2rem;' 
        onclick='abrirModalEdicao(this)'></i>
    </button>
  </td>
  <td class='text-center'>
    <form action='excluiMeta.php' method='POST' style='display:inline;'>
      <input type='hidden' name='idMeta' value='$idMeta'>
      <button type='submit' class='btn btn-sm btn-danger'>
        <i class='bi bi-trash'></i>
      </button>
    </form>
  </td>
</tr>";
    }
echo "
      </table>
    </div>
  </div>
</div>
";
?>
  </div>
</div>
<!-- Modal para edição -->
<div class="modal fade" id="modalEditarMeta" tabindex="-1" aria-labelledby="modalEditarMetaLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    <form action="editarMeta.php" method="POST" id="formEditarMeta">
      <input type="hidden" name="idMeta" id="editIdMeta">
        <div class="modal-header">
          <h5 class="modal-title" id="modalEditarMetaLabel">Editar Meta</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          <label for="editObjetivo" class="form-label">Objetivo:</label>
          <input type="text" id="editObjetivo" name="editObjetivo" class="form-control" required />

          <label for="editValorInicial" class="form-label mt-3">Valor Inicial (R$):</label>
          <input type="number" id="editValorInicial" name="editValorInicial" class="form-control" min="0" step="0.01" required />

          <label for="editValorFinal" class="form-label mt-3">Valor Final (R$):</label>
          <input type="number" id="editValorFinal" name="editValorFinal" class="form-control" min="0" step="0.01" required />

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-success">Salvar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
  // Função para abrir modal e preencher campos com dados da linha
function abrirModalEdicao(elemento) {
  const linha = elemento.closest('tr');
  const objetivo = linha.cells[0].textContent;
  const valorInicial = linha.cells[1].textContent;
  const valorFinal = linha.cells[2].textContent;
  const idMeta = linha.dataset.idMeta;

  document.getElementById('editObjetivo').value = objetivo.trim();
  document.getElementById('editValorInicial').value = valorInicial.trim();
  document.getElementById('editValorFinal').value = valorFinal.trim();
  document.getElementById('editIdMeta').value = idMeta;

  const modal = new bootstrap.Modal(document.getElementById('modalEditarMeta'));
  modal.show();
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
