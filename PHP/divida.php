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
  <title>Administra - Dívida</title>

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

";
?>

<!-- Filtro de dívidas -->
<div class="container mt-4">
  <div class="form-section bg-white p-4 rounded shadow-sm">
    <h3>Filtrar Dívidas</h3>
    <form id="filtro" class="row g-3">
      <div class="col-md-6">
        <label for="filtroPessoa" class="form-label">Credor:</label>
        <select id="filtroPessoa" class="form-select">
          <option value="">--Todos--</option>
          <?php
            // Carregar credores dinamicamente do banco
            $queryCredores = "SELECT DISTINCT credor FROM divida WHERE fk_usuario = $id";
            $resultCredores = mysqli_query($conn, $queryCredores);
            while ($credor = mysqli_fetch_assoc($resultCredores)) {
                echo "<option value='" . htmlspecialchars($credor['credor']) . "'>" . htmlspecialchars($credor['credor']) . "</option>";
            }
          ?>
        </select>
      </div>

      <div class="col-md-6">
        <label for="filtroCategoria" class="form-label">Categoria:</label>
        <select id="filtroCategoria" class="form-select">
          <option value="">--Todas--</option>
          <?php
            // Carregar categorias dinamicamente do banco
            $queryCategorias = "SELECT DISTINCT nome_categoria FROM categoriaDividaWHERE fk_usuario = $id ORDER BY nome_categoria ASC";
            $resultCategorias = mysqli_query($conn, $queryCategorias);
            while ($categoria = mysqli_fetch_assoc($resultCategorias)) {
                $nomeCategoria = $categoria['nome_categoria'] ?? '-';
                if ($nomeCategoria != '-') {
                  echo "<option value='" . htmlspecialchars($nomeCategoria) . "'>" . htmlspecialchars($nomeCategoria) . "</option>";
                }
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
$selectDividas = "SELECT d.*, c.nome_categoria, tp.nome_pagamento
                  FROM divida d
                  LEFT JOIN categoriaDivida c ON d.fk_categoria = c.id_categoria
                  LEFT JOIN tipopagamento tp ON d.fk_tipo_pagamento = tp.id_pagamento
                  WHERE d.fk_usuario = $id
                  ORDER BY d.data_vencimento ASC";

$queryDividas = mysqli_query($conn, $selectDividas);

echo "
<div class='container mt-4'>
  <div class='form-section bg-white p-4 rounded shadow-sm'>
    <h3>Dívidas Cadastradas</h3>
    <div class='table-responsive'>
    <table id='tabelaDividas' class='table table-bordered align-middle'>
        <thead class='table-light'>
          <tr>
            <th>Data Vencimento</th>
            <th>Nome da Dívida</th>
            <th>Credor</th>
            <th>Categoria</th>
            <th>Tipo Pagamento</th>
            <th>Parcelas</th>
            <th>Valor Total (R$)</th>
            <th class='text-center'>Editar</th>
            <th class='text-center'>Excluir</th>
            <th class='text-center'>Exibir</th>
          </tr>
        </thead>
        <tbody>
";

$totalDividas = 0;

while ($divida = mysqli_fetch_assoc($queryDividas)) {
    $dataVencimento = date("d/m/Y", strtotime($divida['data_vencimento']));
    // Using htmlspecialchars for displaying in table cells
    $nomeDivida = htmlspecialchars($divida['nome_divida']);
    $credor = htmlspecialchars($divida['credor']);
    $categoria = $divida['nome_categoria'] ?? '-';
    $tipoPagamento = $divida['nome_pagamento'] ?? '-';
    $parcelas = $divida['parcelas'];
    $idDivida = $divida['id_divida'];

    $valor = number_format($divida['valor_divida'], 2, ',', '.');
    $totalDividas += $divida['valor_divida'];

    // Prepare variables for JavaScript function call
    // Use the raw values for numbers and addslashes for strings
    $jsIdDivida = $idDivida;
    $jsValorDivida = $divida['valor_divida']; // Raw numeric value
    $jsParcelas = $divida['parcelas']; // Raw numeric value
    $jsDataVencimento = $divida['data_vencimento']; // Raw date string
    $jsNomeDivida = addslashes($divida['nome_divida']); // Escaped string for JS

    echo "
    <tr>
      <td>{$dataVencimento}</td>
      <td>{$nomeDivida}</td>
      <td>{$credor}</td>
      <td>{$categoria}</td>
      <td>{$tipoPagamento}</td>
      <td>{$parcelas}</td>
      <td>R$ {$valor}</td>

      <td class='text-center'>
        <button
          type='button'
          class='btn btn-warning btn-sm'
          onclick='abrirModalEdicao({$idDivida},
            \"".addslashes($nomeDivida)."\",
            \"".addslashes($credor)."\",
            \"{$divida['fk_categoria']}\",
            \"{$divida['fk_tipo_pagamento']}\",
            \"{$parcelas}\",
            \"{$divida['valor_divida']}\",
            \"{$divida['data_vencimento']}\")'>
          Editar
        </button>
      </td>

      <td class='text-center'>
        <form action='excluiDivida.php' method='POST' onsubmit='return confirm(\"Deseja realmente excluir esta dívida?\");'>
          <input type='hidden' name='idDivida' value='{$idDivida}'>
          <button type='submit' class='btn btn-danger btn-sm'>Excluir</button>
        </form>
      </td>
      <td class='text-center'>
        <button
          type='button'
          class='btn btn-info btn-sm'
          onclick=\"exibirParcelas({$jsIdDivida}, {$jsValorDivida}, {$jsParcelas}, '{$jsDataVencimento}', '{$jsNomeDivida}')\">
          Exibir
        </button>
      </td>
    </tr>
    ";
}
      $valorTotalFormatado = number_format($totalDividas, 2, ',', '.');


      echo " </tbody>
            </table>
          </div>

      <div class='container mt-3'>
        <div class='alert alert-info text-end fw-bold'>
            Total de Dívidas: R$ $valorTotalFormatado 
        </div>
      </div>
      </div>
      ";
      echo"
            </div>
        </div>
      ";
?>


  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>



<!-- MODAL EDIÇÃO -->
<div class="modal fade" id="modalEditarDivida" tabindex="-1" aria-labelledby="editarDividaLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="editarDivida.php" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="editarDividaLabel">Editar Dívida</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="idDivida" id="editIdDivida">
          <label for="editNome" class="form-label">Nome da Dívida:</label>
          <input type="text" class="form-control" name="editNome" id="editNome" required>

          <label for="editCredor" class="form-label mt-3">Credor:</label>
          <input type="text" class="form-control" name="editCredor" id="editCredor">

          <label for="editCategoria" class="form-label mt-3">Categoria:</label>
          <select class="form-select" name="editCategoria" id="editCategoria">
            <?php
              $cat = mysqli_query($conn, "SELECT * FROM categoriaDivida WHERE fk_usuario = $id OR fk_usuario IS NULL ORDER BY nome_categoria ASC");
              while ($c = mysqli_fetch_assoc($cat)) {
                echo "<option value='{$c['id_categoria']}'>{$c['nome_categoria']}</option>";
              }
            ?>
          </select>

          <label for="editPagamento" class="form-label mt-3">Tipo de Pagamento:</label>
          <select class="form-select" name="editPagamento" id="editPagamento">
            <?php
              $pag = mysqli_query($conn, "SELECT * FROM tipopagamento WHERE fk_usuario = $id OR fk_usuario IS NULL ORDER BY nome_pagamento ASC");
              while ($p = mysqli_fetch_assoc($pag)) {
                echo "<option value='{$p['id_pagamento']}'>{$p['nome_pagamento']}</option>";
              }
            ?>
          </select>

          <label for="editParcelas" class="form-label mt-3">Parcelas:</label>
          <input type="number" class="form-control" name="editParcelas" id="editParcelas" min="1" required>

          <label for="editValor" class="form-label mt-3">Valor Total (R$):</label>
          <input type="number" class="form-control" name="editValor" id="editValor" step="0.01" required>

          <label for="editData" class="form-label mt-3">Data de Vencimento:</label>
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

 <!-- Modal Parcelas -->
<div class="modal fade" id="modalParcelas" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Parcelas da Dívida</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>#</th>
              <th>Valor (R$)</th>
              <th>Mês de Vencimento</th>
            </tr>
          </thead>
          <tbody id="tabelaParcelas"></tbody>
        </table>
      </div>
    </div>
  </div>
</div>


<script>
  function abrirModalEdicao(id, nome, credor, categoria, tipoPagamento, parcelas, valor, data) {
    document.getElementById("editIdDivida").value = id;
    document.getElementById("editNome").value = nome;
    document.getElementById("editCredor").value = credor;
    document.getElementById("editCategoria").value = categoria;
    document.getElementById("editPagamento").value = tipoPagamento;
    document.getElementById("editParcelas").value = parcelas;
    document.getElementById("editValor").value = valor;
    document.getElementById("editData").value = data;

    const modal = new bootstrap.Modal(document.getElementById('modalEditarDivida'));
    modal.show();
  }
</script>

<script>
  function exibirParcelas(idDivida, valorTotal, parcelas, dataVencimento, nomeDivida) { // Adicione 'nomeDivida' aqui
    // Calculando o valor de cada parcela
    const valorParcela = valorTotal / parcelas;

    // Formatando o valor da parcela
    const valorParcelaFormatado = valorParcela.toFixed(2).replace('.', ',');

    // Calculando as datas de vencimento das parcelas
    const dataInicial = new Date(dataVencimento);
    const tabelaParcelas = document.getElementById("tabelaParcelas");
    tabelaParcelas.innerHTML = '';  // Limpa a tabela de parcelas

    // Atualizar o título do modal com o nome da dívida
    document.querySelector('#modalParcelas .modal-title').textContent = `Parcelas de: ${nomeDivida}`;


    // Gerando as parcelas
    for (let i = 0; i < parcelas; i++) {
        // Criando uma nova linha para cada parcela
        const tr = document.createElement('tr');

        // Calculando a data da parcela
        const vencimento = new Date(dataInicial);
        vencimento.setMonth(vencimento.getMonth() + i);
        const mesVencimento = vencimento.toLocaleString('pt-BR', { year: 'numeric', month: 'long' });

        // Adicionando as células
        tr.innerHTML = `
            <td>${i + 1}</td>
            <td>R$ ${valorParcelaFormatado}</td>
            <td>${mesVencimento}</td>
        `;

        // Adicionando a linha na tabela
        tabelaParcelas.appendChild(tr);
    }

    // Exibindo o modal de parcelas
    const modal = new bootstrap.Modal(document.getElementById('modalParcelas'));
    modal.show();
}
</script>


<script>
function abrirModalParcelas(idDivida) {
    // Limpar o conteúdo da tabela antes de preencher
    const tabelaParcelas = document.getElementById("tabelaParcelas");
    tabelaParcelas.innerHTML = "";

    // Fazer uma requisição AJAX para buscar as parcelas dessa dívida
    fetch(`buscarParcelas.php?idDivida=${idDivida}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                data.parcelas.forEach((parcela, index) => {
                    const tr = document.createElement("tr");

                    tr.innerHTML = `
                        <td>${index + 1}</td>
                        <td>R$ ${parcela.valor.toFixed(2).replace('.', ',')}</td>
                        <td>${parcela.data_vencimento}</td>
                    `;

                    tabelaParcelas.appendChild(tr);
                });
            } else {
                alert("Erro ao carregar as parcelas.");
            }
        })
        .catch(error => {
            console.error("Erro na requisição:", error);
            alert("Erro na requisição.");
        });

    // Mostrar o modal
    const modal = new bootstrap.Modal(document.getElementById('modalParcelas'));
    modal.show();
}

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
  
  <script>
 document.getElementById("filtro").addEventListener("submit", function(e) {
  e.preventDefault();

  const filtroPessoa = document.getElementById("filtroPessoa").value.toLowerCase();
  const filtroCategoria = document.getElementById("filtroCategoria").value.toLowerCase();
  const filtroValor = parseFloat(document.getElementById("filtroValor").value);
  const filtroMes = document.getElementById("filtroMes").value;

  const linhas = document.querySelectorAll("#tabelaDividas tbody tr");

  linhas.forEach(linha => {
    const credor = linha.children[2].textContent.toLowerCase();
    const categoria = linha.children[3].textContent.toLowerCase();
    const valorTexto = linha.children[6].textContent.replace("R$", "").trim().replace(".", "").replace(",", ".");
    const valor = parseFloat(valorTexto);
    const mesLinha = linha.children[0].textContent.split("/")[1];

    let mostrar = true;

    if (filtroPessoa && !credor.includes(filtroPessoa)) mostrar = false;
    if (filtroCategoria && !categoria.includes(filtroCategoria)) mostrar = false;
    if (!isNaN(filtroValor) && valor < filtroValor) mostrar = false;
    if (filtroMes && filtroMes !== mesLinha) mostrar = false;

    linha.style.display = mostrar ? "" : "none";
  });
});


document.getElementById("limparFiltros").addEventListener("click", function() {
  document.getElementById("filtroPessoa").value = "";
  document.getElementById("filtroCategoria").value = "";
  document.getElementById("filtroValor").value = "";
  document.getElementById("filtroMes").value = "";

  const linhas = document.querySelectorAll("#tabelaDividas tbody tr");
  linhas.forEach(linha => linha.style.display = "");
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
