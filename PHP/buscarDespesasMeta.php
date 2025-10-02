<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Despesas Vinculadas</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../CSS/carregando.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />

    <!-- Custom CSS (se necessário) -->
    <link href="../CSS/principal.css" rel="stylesheet" />
</head>
<body style="padding-top: 80px;">

<!-- Navbar -->
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

<div class="container mt-5">
    

    <div class="consulta-section bg-white p-4 rounded shadow-sm">
        <div class="d-flex justify-content-between mb-4">
            <a href="meta.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left-circle"></i> Voltar
            </a>
           <!--<a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#adicionarDespesaModal">
                <i class="bi bi-plus-circle"></i> Adicionar Alocação
            </a>-->
        </div>
        <?php
        session_start();
        include 'conexao.php';
        mysqli_set_charset($conn, 'utf8');

        if (!isset($_SESSION['ID_USER']) || !isset($_POST['idMeta'])) {
            echo "<p class='text-danger'>Dados insuficientes.</p>";
            exit;
        }

        $idUsuario = $_SESSION['ID_USER'];
        $idMeta = intval($_POST['idMeta']);

        $sql = "SELECT * FROM despesa WHERE fk_usuario = $idUsuario AND fk_meta = $idMeta ORDER BY data_despesa DESC";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) === 0) {
            echo "<p>Nenhuma despesa vinculada a esta meta.</p>";
            exit;
        }
        $sqlMeta = "select * from poupanca where id_poupanca = $idMeta";
        $pegaMeta = mysqli_query($conn, $sqlMeta);
        $metafetch = mysqli_fetch_assoc($pegaMeta);
        $nomeMeta = $metafetch['objetivo'];
        echo "<h2 class='text-center mt-4'>Alocações realizadas</h2>";
        echo "<h4>Meta: $nomeMeta</h4>";
        echo "
            <table class='table table-bordered table-striped'>
                <thead class='table-light'>
                    <tr>
                        <th>Valor (R$)</th>
                        <th>Data</th>
                        <th>Excluir</th>
                    </tr>
                </thead>
                <tbody>
        ";

        while ($row = mysqli_fetch_assoc($result)) {
            $valor = number_format($row['valor_despesa'], 2, ',', '.');
            $data = date("d/m/Y", strtotime($row['data_despesa']));
            $idDespesa = $row['id_despesa'];
            echo "
                <tr>
                    <td>$valor</td>
                    <td>$data</td>
                    <td>
                        <form action='excluiDespesaMeta.php' method='POST'>
                            <input type='hidden' name='idMeta' value='$idMeta'>
                            <input type='hidden' name='idDespesa' value='$idDespesa'>
                            <input type='hidden' name='valor' value='$valor'>
                            <button type='submit' class='btn btn-sm btn-danger'>
                                <i class='bi bi-trash'></i> Excluir
                            </button>
                        </form>
                    </td>
                </tr>
            ";
        }

        echo "</tbody></table>";
        ?>
    </div>
</div>

<!-- Modal Adicionar Despesa 
<div class="modal fade" id="adicionarDespesaModal" tabindex="-1" aria-labelledby="adicionarDespesaModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="adicionarDespesaModalLabel">Adicionar Despesa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <form action="cadastraDespesaMeta.php" method="POST">
          <div class="mb-3">
            <label for="valorDespesa" class="form-label">Valor (R$)</label>
            <input type="number" class="form-control" id="valorDespesa" name="valorDespesa" required>
          </div>
          <div class="mb-3">
            <label for="dataDespesa" class="form-label">Data</label>
            <input type="date" class="form-control" id="dataDespesa" name="dataDespesa" required>
          </div>
          <input type="hidden" name="idMeta" value="<?php echo $idMeta; ?>">
          <div class="text-center">
            <button type="submit" class="btn btn-success">Adicionar</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
-->

<footer class="mt-4">
    <div class="container text-center">
        <p class="mb-1">© 2025 Administra - Todos os direitos reservados</p>
    </div>
</footer>

<!-- Bootstrap 5 JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
