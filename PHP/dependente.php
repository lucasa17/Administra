<?php
session_start();
include 'conexao.php';

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
  <title>Administra - Dependente</title>

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
  </div>
</nav>

  <!-- CADASTRO DEPENDENTES -->
  <main class="container mt-4">
    <div class="row justify-content-center">
      <!-- FORMULÁRIO DE CADASTRO -->
      <div class="col-md-6 mb-4">
        <div class="form-section bg-white p-4 rounded shadow-sm h-100">
          <h3 class="text-center">Cadastrar Dependentes</h3>
          
          <form class="formGasto" action="cadastraDependente.php" method="post">
          <label for="nomeDependente" class="mt-3">Nome do dependente:</label>
            <input name = "txtnome" type="text" id="nomeDependente" class="form-control" placeholder="Digite o nome do dependente..." required />
  
            <label for="relacao" class="mt-3">Relação:</label>
            <input name = "txtrelacao" type="text" id="relacao" class="form-control" placeholder="Ex: Filho(a), Pai/Mãe..." required />
  
            <button type="submit" class="btn btn-success mt-4">Cadastrar dependente</button>
          </form>
        </div>
      </div>
      <div class="col-md-6 mb-4">
        <div class="form-section bg-white p-4 rounded shadow-sm h-100">
          <h3 class="text-center">Dependentes Cadastrados</h3>
          <ul id="listaDependentes" class="list-group mt-3">

<?php

	$id = $_SESSION['ID_USER'];

    $select = "select * from dependente where fk_usuario = $id";
    $dependente = mysqli_query($conn, $select);

    while($pegaDependente = mysqli_fetch_assoc($dependente)){

        $nomeDependente = $pegaDependente['nome_dependente'];
        $relacaoDependente = $pegaDependente['relacao'];
        $idDependente = $pegaDependente['id_dependente'];
    
echo "
  <li class='list-group-item d-flex justify-content-between align-items-center'>
    <span>
      <class='nome'>$nomeDependente - <span class='relacao'>$relacaoDependente</span>
    </span>
    <div>
      <button class='btn btn-sm btn-outline-primary me-2' onclick=\"abrirModalEdicao('$idDependente', '$nomeDependente', '$relacaoDependente')\">
        <i class='bi bi-pencil-square'></i>
      </button>
      <form id='form-excluir-$idDependente' action='excluiDependente.php' method='POST' style='display:inline;'>
        <input type='hidden' name='idDependente' value='$idDependente'>
        <button type='submit' class='btn btn-sm btn-outline-danger' Style='Background-color:Red;'>
          <i class='bi bi-trash'></i>
        </button>
      </form>
    </div>
  </li>
    ";
    }
?>
    </ul>
        </div>
      </div>
    </div>
  </main>

<!-- MODAL DE EDIÇÃO -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formEditar" action="editarDependente.php" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="modalEditarLabel">Editar Dependente</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="editId" name="idDependente">
          <div class="mb-3">
            <label for="editNome" class="form-label">Nome</label>
            <input type="text" id="editNome" name="editNome" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="editRelacao" class="form-label">Relação</label>
            <input type="text" id="editRelacao" name="editRelacao" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Salvar Alterações</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>


<script>
  function abrirModalEdicao(id, nome, relacao) {
    document.getElementById("editId").value = id;
    document.getElementById("editNome").value = nome;
    document.getElementById("editRelacao").value = relacao;

    const modal = new bootstrap.Modal(document.getElementById('modalEditar'));
    modal.show();
  }
</script>

<!-- Bootstrap JS -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- FOOTER -->
<footer class="mt-4">
  <div class="container text-center">
    <p class="mb-1">© 2025 Administra - Todos os direitos reservados</p>
  </div>
</footer>

</body>
</html>