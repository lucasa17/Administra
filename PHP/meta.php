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
    <title>Administra - Meta</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../CSS/carregando.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />

    <link href="../CSS/principal.css" rel="stylesheet" />
</head>
<body style="padding-top: 80px;">

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

<?php
$id = $_SESSION['ID_USER'];

echo "
    <div class='container mt-4'>
        <div class='form-section bg-white p-4 rounded shadow-sm'>
            <h3>Cadastrar Meta</h3>
            <form class='formMeta' action='cadastraMeta.php' method='post' onsubmit='return validarFormulario()'>
                <label for='objetivo' class='mt-3'>Objetivo:</label>
                <input type='text' name='txtObjetivo' id='objetivo' class='form-control' placeholder='Descreva o objetivo da meta' required />

                <!--<label for='valorInicial' class='mt-3'>Aporte Inicial (R$):</label>
                <input type='number' name='numAporteInicial' id='valorInicial' class='form-control' min='0' step='0.01' required />-->

                <label for='valorFinal' class='mt-3'>Valor Final (R$):</label>
                <input type='number' name='numValorFinal' id='valorFinal' class='form-control' min='0' step='0.01' required />

                <button type='submit' class='btn btn-success mt-4'>Salvar Meta</button>
            </form>
        </div>
    </div>
";

// Consulta para metas pendentes
$selectMetaPendentes = "SELECT * from poupanca where fk_usuario = $id and valor_atual < valor_meta order by objetivo asc";
$queryMetaPendentes = mysqli_query($conn, $selectMetaPendentes);

echo "
    <div class='container mt-5'>
        <div class='consulta-section bg-white p-4 rounded shadow-sm'>
            <h3>Metas Pendentes</h3>
            <div class='mt-4'>
                <table class='table table-bordered align-middle'>
                    <thead class='table-light'>
                        <tr>
                            <th>Objetivo</th>
                            <th>Valor Atual (R$)</th>
                            <th>Valor Meta (R$)</th>
                            <th class='text-center'>Alocações</th>
                            <th class='text-center'>Editar</th>
                            <th class='text-center'>Excluir</th>
                        </tr>
                    </thead>
                    <tbody>
";

while($pegaMeta = mysqli_fetch_assoc($queryMetaPendentes)) {
    $objetivo = $pegaMeta['objetivo'];
    $valorAtual = number_format($pegaMeta['valor_atual'], 2, ',', '.');
    $valorMeta = number_format($pegaMeta['valor_meta'], 2, ',', '.');
    $idMeta = $pegaMeta['id_poupanca'];

    echo "
        <tr data-id-meta='$idMeta'>
            <td>$objetivo</td>
            <td>R$$valorAtual</td>
            <td>R$$valorMeta</td>
            <td class='text-center'>
                <form action='buscarDespesasMeta.php' method='POST' style='display:inline;'>
                    <input type='hidden' name='idMeta' value='$idMeta'>
                    <button type='submit' class='btn btn-sm btn-danger'>
                        <i>Alocações</i>
                    </button>
                </form>
            </td>
            <td class='text-center'>
                <button type='button' class='btn btn-sm btn-primary' onclick='abrirModalEdicao(this)'>
                    <i class='bi bi-pencil-square'></i>
                </button>
            </td>
            <td class='text-center'>
                <form action='excluiMeta.php' method='POST' style='display:inline;' onsubmit='return confirm(\"Deseja realmente excluir esta meta?\");'>
                    <input type='hidden' name='idMeta' value='$idMeta'>
                    <button type='submit' class='btn btn-sm btn-danger' Style='Background-color:Red;'>
                        <i class='bi bi-trash'></i>
                    </button>
                </form>
            </td>
        </tr>";
}
echo "
                    </tbody>
                </table>
            </div>
        </div>
    </div>
";


$selectMetaAtingidas = "SELECT * from poupanca where fk_usuario = $id and valor_atual >= valor_meta order by objetivo asc";
$queryMetaAtingidas = mysqli_query($conn, $selectMetaAtingidas);

echo "
    <div class='container mt-5'>
        <div class='consulta-section bg-white p-4 rounded shadow-sm'>
            <h3>Metas Atingidas <i class='bi bi-trophy-fill text-warning'></i></h3>
            <div class='mt-4'>
                <table class='table table-bordered align-middle table-success'>
                    <thead class='table-dark'>
                        <tr>
                            <th>Objetivo</th>
                            <th>Valor Atingido (R$)</th>
                            <th>Valor Meta (R$)</th>
                            <th class='text-center'>Excluir</th>
                        </tr>
                    </thead>
                    <tbody>
";

while($pegaMeta = mysqli_fetch_assoc($queryMetaAtingidas)) {
    $objetivo = $pegaMeta['objetivo'];
    $valorAtual = $pegaMeta['valor_atual'];
    $valorMeta = $pegaMeta['valor_meta'];
    $idMeta = $pegaMeta['id_poupanca'];

    echo "
        <tr class='table-success'>
            <td>$objetivo</td>
            <td>$valorAtual</td>
            <td>$valorMeta</td>
            <td class='text-center'>
                <form action='excluiMeta.php' method='POST' style='display:inline;' onsubmit='return confirm(\"Deseja realmente excluir esta meta?\");'>
                    <input type='hidden' name='idMeta' value='$idMeta'>
                    <button type='submit' class='btn btn-sm btn-danger'>
                        <i class='bi bi-trash'></i>
                    </button>
                </form>
            </td>
        </tr>";
}
echo "
                    </tbody>
                </table>
            </div>
        </div>
    </div>
";
?>

<div class="modal fade" id="modalEditarMeta" tabindex="-1" aria-labelledby="modalEditarMetaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="editarMeta.php" method="POST" id="formEditarMeta" onsubmit="return validarEdicaoMeta()">
                <input type="hidden" name="idMeta" id="editIdMeta">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarMetaLabel">Editar Meta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <label for="editObjetivo" class="form-label">Objetivo:</label>
                    <input type="text" id="editObjetivo" name="editObjetivo" class="form-control" required />
    
                    <label for="editValorFinal" class="form-label mt-3">Valor Meta (R$):</label>
                    <input type="number" id="editValorFinal" name="editValorFinal" class="form-control" min="0" step="0.01" required />
                </div>
                
                <div id="despesasMeta" class="mt-4">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
   function abrirModalEdicao(elemento) {
        const linha = elemento.closest('tr');
        const objetivo = linha.cells[0].textContent;
        const valorAtual = parseFloat(linha.cells[1].textContent);
        const valorMeta = parseFloat(linha.cells[2].textContent);
        const idMeta = linha.dataset.idMeta;

        document.getElementById('editObjetivo').value = objetivo.trim();
        document.getElementById('editValorFinal').value = valorMeta;
        document.getElementById('editIdMeta').value = idMeta;

        document.getElementById('formEditarMeta').dataset.valorAtual = valorAtual;
        const modal = new bootstrap.Modal(document.getElementById('modalEditarMeta'));
        modal.show();
    }


    function validarFormulario() {
        const valorInicial = parseFloat(document.getElementById('valorInicial').value);
        const valorFinal = parseFloat(document.getElementById('valorFinal').value);
        if (valorInicial >= valorFinal) {
            alert('O valor do Aporte Inicial deve ser menor que o Valor Final.');
            return false;  
        }
    return true;                       
    }

    function validarEdicaoMeta() {
        const valorFinal = parseFloat(document.getElementById('editValorFinal').value);
        const valorAtual = parseFloat(document.getElementById('formEditarMeta').dataset.valorAtual);

        if (valorFinal < valorAtual) {
            alert("O valor da meta não pode ser menor que o valor atual.");
            return false;
        }
        return true;
    }
</script>

<footer class="mt-4">
    <div class="container text-center">
        <p class="mb-1">© 2025 Administra - Todos os direitos reservados</p>
    </div>
</footer>

</body>
</html>