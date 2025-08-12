<?php
session_start();
include 'conexao.php';
mysqli_set_charset($conn, 'utf8');

if (empty($_SESSION['ID_USER'])) {
    echo "
        <div id='loadingOverlay'>
            <div id='loadingCard'>
            <h1>Administra</h1>
            <img src='../IMAGENS/alerta.gif' alt='Carregando...' />
            <strong><p class='mt-3'>Usuário não esta logado</p></strong>
            </div>
        </div>
    ";
    header("refresh: 3.5; url=../index.html");
    exit();
}

$id = $_SESSION['ID_USER'];

// Meses para exibição
$meses = [
    1 => 'Jan', 2 => 'Fev', 3 => 'Mar', 4 => 'Abr', 5 => 'Mai', 6 => 'Jun',
    7 => 'Jul', 8 => 'Ago', 9 => 'Set', 10 => 'Out', 11 => 'Nov', 12 => 'Dez'
];

// Captura filtros de mês e ano via GET
$mesAtual = isset($_GET['mes']) ? intval($_GET['mes']) : date('n');
$anoAtual = isset($_GET['ano']) ? intval($_GET['ano']) : date('Y');

// Dados para o gráfico mensal filtrado
$labelsMeses = [$meses[$mesAtual]];

$sql = "SELECT total_receita, total_despesa, saldo 
        FROM ResumoMensal 
        WHERE fk_usuario = $id AND mes = $mesAtual AND ano = $anoAtual";

$res = mysqli_query($conn, $sql);
$dados = mysqli_fetch_assoc($res);

$receitas = [isset($dados['total_receita']) ? floatval($dados['total_receita']) : 0];
$despesas = [isset($dados['total_despesa']) ? -1 * floatval($dados['total_despesa']) : 0];
$saldos   = [isset($dados['saldo']) ? floatval($dados['saldo']) : 0];

// Preparar dados para gráfico anual
$dadosPorAno = [];
$gastosPorAno = [];

$selectAnos = "SELECT DISTINCT ano FROM ResumoMensal WHERE fk_usuario = $id ORDER BY ano ASC";
$anos = mysqli_query($conn, $selectAnos);
$anosDisponiveis = [];
while ($pegaAnos = mysqli_fetch_assoc($anos)) {
    $anosDisponiveis[] = $pegaAnos['ano'];
}

foreach ($anosDisponiveis as $ano) {
    $dadosPorAno[$ano] = array_fill(0, 12, 0);
    $gastosPorAno[$ano] = array_fill(0, 12, 0);

    $sql = "SELECT mes, total_receita, total_despesa 
            FROM ResumoMensal 
            WHERE fk_usuario = $id AND ano = $ano";

    $res = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($res)) {
        $indice = intval($row['mes']) - 1;
        $dadosPorAno[$ano][$indice] = floatval($row['total_receita']);
        $gastosPorAno[$ano][$indice] = -1 * floatval($row['total_despesa']);
    }
}
?>
    
    <!DOCTYPE html>
<html lang="pt-BR">
  <head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Administra - Visão Geral</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="../CSS/carregando.css" rel="stylesheet" />

    <!-- Ícones Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />

    <!-- CSS personalizado -->
    <link href="../CSS/principal.css" rel="stylesheet" />

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  </head>
  <body style="padding-top: 80px;">
    <!-- HEADER -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow">
      <div class="container">
        <a class="navbar-brand fw-bold" href="index.html">Administra</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
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
  
  <!-- VISÃO GERAL -->
  <!-- grafico de analise mensal-->
    <div class="container mt-5 d-flex justify-content-around flex-wrap gap-4">

  <!-- Gráfico Mensal com seletor de mês -->
  <div class="form-section bg-white p-4 rounded shadow-sm" style="max-width: 600px; height: 450px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 class="mb-0">Análise Mensal</h3>
      <select id="mesSelect" class="form-select w-auto">
        <?php
          foreach ($meses as $num => $nome) {
            $selected = ($num == $mesAtual) ? 'selected' : '';
            echo "<option value='$num' $selected>$nome</option>";
          }
        ?>
      </select>
    </div>
    <canvas id="graficoMensal" style="width: 100%; max-height: 350px;"></canvas>
  </div>

  <!-- Gráfico Anual com seletor de ano -->
  <div class="form-section bg-white p-4 rounded shadow-sm" style="max-width: 600px; height: 450px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 class="mb-0">Análise Anual</h3>
      <select id="anoSelect" class="form-select w-auto">
        <?php
          foreach ($anosDisponiveis as $ano) {
            $selected = ($ano == $anoAtual) ? 'selected' : '';
            echo "<option value='$ano' $selected>$ano</option>";
          }
        ?>
      </select>
    </div>
    <canvas id="graficoAnual" style="width: 100%; max-height: 350px;"></canvas>
  </div>
</div>

<?php 
$mesAtual = isset($_GET['mes']) ? intval($_GET['mes']) : date('n');
$anoAtual = isset($_GET['ano']) ? intval($_GET['ano']) : date('Y');

$selectValor = "SELECT * FROM ResumoMensal 
                WHERE fk_usuario = $id 
                AND mes = $mesAtual 
                AND ano = $anoAtual
                ORDER BY ano ASC";

$val = mysqli_query($conn, $selectValor);
$pegaValor = mysqli_fetch_assoc($val);

if ($pegaValor !== null) {
    $valor = $pegaValor['saldo'];
} else {
    $valor = 0;
}

if ($valor > 0) {
    $sqlMetas = "SELECT * FROM Poupanca 
                 WHERE fk_usuario = $id 
                 AND valor_atual < valor_meta";
    $resultMetas = mysqli_query($conn, $sqlMetas);

    $row = mysqli_num_rows($resultMetas);
    if ($row > 0) {
        $optionsMetas = '';
        while ($meta = mysqli_fetch_assoc($resultMetas)) {
            $objetivo = $meta['objetivo'];
            $valorFinal = $meta['valor_meta'];
            $saldoAtual = $meta['valor_atual'];
            $optionsMetas .= "<option value='" . $meta['id_poupanca'] . "'> Meta: $objetivo - Valor Guardado: R$ $saldoAtual / Valor meta: R$ $valorFinal</option>";
        }

        echo "
        <div class='container'>
          <div id='saldoPositivoAlert' class='alert alert-success d-flex flex-column gap-2 mt-3'>
            <div class='d-flex justify-content-between align-items-center'>
              <div>
                <i class='bi bi-cash-stack me-2'></i>
                <strong>Parabéns!</strong> Você teve um saldo positivo de 
                <span id='saldoValor'>R$ $valor </span> em $mesAtual/$anoAtual.
                Deseja adicionar parte desse valor a alguma meta?
              </div>
              <button id='btnSim' class='btn btn-success btn-sm'>Sim</button>
            </div>

            <form id='formSelecionaMeta' action='salvaSaldo.php' method='POST' class='d-none mt-3'>
              <div class='mb-2'>
                <label class='form-label'>Escolha a meta:</label>
                <select name='id_poupanca' class='form-select' required>
                  <option value=''>Selecione uma meta</option>
                  $optionsMetas
                </select>
              </div>
              <div class='mb-2'>
                <label class='form-label'>Quanto deseja aplicar?</label>
                <input type='number' name='valor_aplicado' class='form-control' min='1' max='$valor' required placeholder='Digite o valor (máx: R$ $valor)'>
                <input type='hidden' name='valor_total_disponivel' value='$valorFinal'>
                <input type='hidden' name='saldoAtual' value='$saldoAtual'>
                <input type='hidden' name='mes' value='$mesAtual'>
                <input type='hidden' name='ano' value='$anoAtual'>
                <input type='hidden' name='nomeMeta' value='$objetivo'>
              </div>
              <button type='submit' class='btn btn-primary'>Confirmar</button>
            </form>
          </div>
        </div>
        ";

        mysqli_data_seek($resultMetas, 0); 
        while ($meta = mysqli_fetch_assoc($resultMetas)) {
            if ($meta['meses_ate_meta'] != null) {
                $meses = $meta['meses_ate_meta'];
                $nome = $meta['objetivo'];
                echo "
                <div class='container'>
                  <div id='tempoMetaAlert' class='alert alert-warning d-flex justify-content-between align-items-center mt-3' style='display: none;'>
                    <div>
                      <i class='bi bi-hourglass-split me-2'></i>
                      <strong>Boa notícia!</strong> Mantendo essa economia mensal, 
                      você atingirá a meta '<span id='nomeMeta'>$nome</span>' em <span id='mesesRestantes'>$meses</span> meses.
                      Continue assim para alcançar seus objetivos!
                    </div>
                  </div>
                </div>
                ";
            }
        }
    }
}
?>


  <!-- RENDA - TABELA E GRÁFICO -->
    <div class="container mt-5"> 
      <div class="row">
        <!-- TABELA DE RENDA -->
        <div class="col-md-6">
          <div class="form-section bg-white p-4 rounded shadow-sm">
            <h4 class="mb-3 text-center">Rendas Cadastradas</h4>
            <div class="table-responsive">
              <table class="table table-bordered align-middle">
                <thead class="table-light">
                  <tr>
                    <th>Data</th>
                    <th>Tipo</th>
                    <th>Valor (R$)</th>
                  </tr>
                </thead>
    <?php
    
    $id = $_SESSION['ID_USER'];

      $selectRendas = "SELECT * FROM renda WHERE fk_usuario = $id";
      $queryRendas = mysqli_query($conn, $selectRendas);

      $rendaLabels = [];
      $rendaValores = [];

      while ($renda = mysqli_fetch_assoc($queryRendas)) {
          $fonteRendaId = $renda['fk_fonte'];
          $fonteQuery = "SELECT fonte_da_renda FROM FonteRenda WHERE id_renda = $fonteRendaId";
          $fonteResult = mysqli_query($conn, $fonteQuery);
          $fonteRenda = mysqli_fetch_assoc($fonteResult)['fonte_da_renda'];

          // Verifica se o label já existe
          $key = array_search($fonteRenda, $rendaLabels);
          if ($key === false) {
              // Se não existe, adiciona novo label e valor
              $rendaLabels[] = $fonteRenda;
              $rendaValores[] = $renda['valor_renda'];
          } else {
              // Se já existe, soma o valor
              $rendaValores[$key] += $renda['valor_renda'];
          }

          // Renderiza a tabela normalmente
          $valorRenda = number_format($renda['valor_renda'], 2, ',', '.');
          $dataRenda = date("d/m/Y", strtotime($renda['data_renda']));
          echo "
              <tbody>
                  <tr>
                      <td>$dataRenda</td>
                      <td>$fonteRenda</td>
                      <td>R$ $valorRenda</td>
                  </tr>
              </tbody>
          ";
      }
      ?>

                </table>
            </div>
          </div>
        </div>
        <!-- GRÁFICO DE RENDA -->
        <div class="col-md-6">
          <div class="form-section bg-white p-4 rounded shadow-sm">
            <h4 class="mb-3 text-center">Distribuição de Renda</h4>
            <canvas id="graficoRenda" style="max-width: 300px; height: auto; margin: 0 auto; display: block;"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- DESPESAS - TABELA E GRÁFICO -->
    <div class="container mt-5">
      <div class="row">

        <!-- TABELA DE DESPESAS -->
        <div class="col-md-6">
          <div class="form-section bg-white p-4 rounded shadow-sm">
            <h4 class="mb-3 text-center">Despesas Registradas</h4>
            <div class="table-responsive">
              <table class="table table-bordered align-middle">
                <thead class="table-light">
                  <tr>
                    <th>Data</th>
                    <th>Categoria</th>
                    <th>Valor (R$)</th>
                  </tr>
                </thead>

        <?php

          $selectDespesas = "SELECT d.*, cat.nome_categoria, tp.nome_pagamento
                      FROM despesa d
                      LEFT JOIN categoria cat ON d.fk_categoria = cat.id_categoria
                      LEFT JOIN tipopagamento tp ON d.fk_tipo_pagamento = tp.id_pagamento
                      WHERE d.fk_usuario = $id
                      ORDER BY d.data_despesa DESC";

          $queryDespesas = mysqli_query($conn, $selectDespesas);

          $despesaLabels = [];
          $despesaValores = [];

          while ($despesa = mysqli_fetch_assoc($queryDespesas)) {
              $nomeCategoria = $despesa['nome_categoria'] ?? '-';
              $valorDespesaFloat = $despesa['valor_despesa'];

              // Verifica se a categoria já existe
              $key = array_search($nomeCategoria, $despesaLabels);
              if ($key === false) {
                  // Se não existe, adiciona nova categoria e valor
                  $despesaLabels[] = $nomeCategoria;
                  $despesaValores[] = $valorDespesaFloat;
              } else {
                  // Se já existe, soma o valor
                  $despesaValores[$key] += $valorDespesaFloat;
              }

              $dataDespesa = date("d/m/Y", strtotime($despesa['data_despesa']));
              $valorDespesa = number_format($valorDespesaFloat, 2, ',', '.');
              echo "
                  <tbody>
                      <tr>
                          <td>$dataDespesa</td>
                          <td>$nomeCategoria</td>
                          <td>R$ $valorDespesa</td>
                      </tr>
                  </tbody>
              ";
          }
          ?>
              </table>
            </div>
          </div>
        </div>
    
        <!-- GRÁFICO DE DESPESAS -->
        <div class="col-md-6">
          <div class="form-section bg-white p-4 rounded shadow-sm">
            <h4 class="mb-3 text-center">Distribuição de Despesas</h4>
            <canvas id="graficoDespesas" style="max-width: 300px; height: auto; margin: 0 auto; display: block;"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- DÍVIDAS - TABELA E GRÁFICO -->
  <div class="container mt-5">
      <div class="row">
        <div class="col-md-6">
          <div class="form-section bg-white p-4 rounded shadow-sm">
            <h4 class="mb-3 text-center">Dívidas Atuais</h4>
            <div class="table-responsive">
              <table class="table table-bordered align-middle">
                <thead class="table-light">
                  <tr>
                    <th>Categoria</th>
                    <th>Valor (R$)</th>
                  </tr>
                </thead>

        <?php
          $selectDividas = "SELECT d.*, c.nome_categoria
                            FROM divida d
                            LEFT JOIN categoriaDivida c ON d.fk_categoria = c.id_categoria
                            WHERE d.fk_usuario = $id
                            ORDER BY d.data_vencimento ASC";

          $queryDividas = mysqli_query($conn, $selectDividas);

          $dividaLabels = [];
          $dividaValores = [];

          while ($divida = mysqli_fetch_assoc($queryDividas)) {
              $categoria = $divida['nome_categoria'] ?? '-';
              $valor = $divida['valor_divida'];

              $dividaLabels[] = $categoria;
              $dividaValores[] = $valor;

              $valorFormatado = number_format($valor, 2, ',', '.');
              echo "
                  <tbody>
                      <tr>
                          <td>$categoria</td>
                          <td>R$ $valorFormatado</td>
                      </tr>
                  </tbody>
              ";
          }

        ?>
              </table>
            </div>
          </div>
        </div>
    
        <!-- GRÁFICO DE DÍVIDAS -->
        <div class="col-md-6">
          <div class="form-section bg-white p-4 rounded shadow-sm">
            <h4 class="mb-3 text-center">Distribuição de Dívidas</h4>
            <canvas id="graficoDividas" style="max-width: 300px; height: auto; margin: 0 auto; display: block;"></canvas>
          </div>
        </div>
      </div>
    </div>  

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    document.getElementById('btnSim')?.addEventListener('click', () => {
      const form = document.getElementById('formSelecionaMeta');
      if (form) form.classList.remove('d-none');
    });
  </script>

    <script>
      const meses = <?php echo json_encode($labelsMeses); ?>;
      const receitas = <?php echo json_encode($receitas); ?>;
      const despesas = <?php echo json_encode($despesas); ?>;
      const saldos = <?php echo json_encode($saldos); ?>;
      const dadosPorAno = <?php echo json_encode($dadosPorAno); ?>;
      const gastosPorAno = <?php echo json_encode($gastosPorAno); ?>;
    </script>

    <script>
      const dadosPorAno = <?php echo json_encode($dadosPorAno); ?>;
      const gastosPorAno = <?php echo json_encode($gastosPorAno); ?>;
    </script>

    <script>
      const rendaLabels = <?php echo json_encode($rendaLabels); ?>;
      const rendaValores = <?php echo json_encode($rendaValores); ?>;
    </script>

    <script>
      const despesaLabels = <?php echo json_encode($despesaLabels); ?>;
      const despesaValores = <?php echo json_encode($despesaValores); ?>;
    </script>

    <script>
      const dividaLabels = <?php echo json_encode($dividaLabels); ?>;
      const dividaValores = <?php echo json_encode($dividaValores); ?>;
    </script>

    <!-- Gráficos Chart.js -->
    <script>  
      // Gráfico Mensal
      const ctxMensal = document.getElementById('graficoMensal').getContext('2d');
const graficoMensal = new Chart(ctxMensal, {
  type: 'bar',
  data: {
    labels: meses,
    datasets: [
      { label: 'Receita', data: receitas, backgroundColor: 'rgba(75, 192, 192, 0.7)' },
      { label: 'Despesa', data: despesas, backgroundColor: 'rgba(255, 99, 132, 0.7)' },
      { label: 'Saldo', data: saldos, backgroundColor: 'rgba(255, 206, 86, 0.7)' }
    ]
  },
  options: { responsive: true, maintainAspectRatio: false }
});



      // Gráfico Anual
const ctxAnual = document.getElementById('graficoAnual').getContext('2d');
const graficoAnual = new Chart(ctxAnual, {
  type: 'bar',
  data: {
    labels: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
    datasets: [
      { label: 'Renda Mensal (R$)', data: dadosPorAno[<?php echo $anoAtual; ?>], backgroundColor: 'rgba(75, 192, 192, 0.2)' },
      { label: 'Gastos Mensais (R$)', data: gastosPorAno[<?php echo $anoAtual; ?>], backgroundColor: 'rgba(255, 99, 132, 0.2)' }
    ]
  },
  options: { responsive: true, maintainAspectRatio: false }
});
  document.getElementById('mesSelect').addEventListener('change', function () {
  const mes = this.value;
  const ano = document.getElementById('anoSelect').value;
  window.location.href = `visaoGeral.php?ano=${ano}&mes=${mes}`;
});
document.getElementById('anoSelect').addEventListener('change', function () {
  const ano = this.value;
  const mes = document.getElementById('mesSelect').value;
  window.location.href = `visaoGeral.php?ano=${ano}&mes=${mes}`;
});


        const ctxRenda = document.getElementById('graficoRenda').getContext('2d');
        new Chart(ctxRenda, {
          type: 'pie',
          data: {
            labels: rendaLabels,
            datasets: [{
              label: 'Renda',
              data: rendaValores,
              backgroundColor: [
                'rgba(54, 162, 235, 0.7)',
                'rgba(255, 206, 86, 0.7)',
                'rgba(75, 192, 192, 0.7)',
                'rgba(153, 102, 255, 0.7)',
                'rgba(255, 99, 132, 0.7)'
              ],
              borderColor: [
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 99, 132, 1)'
              ],
              borderWidth: 1
            }]
          },
          options: {
            responsive: true,
            plugins: {
              legend: {
                position: 'bottom'
              }
            }
          }
        });


        const ctxDespesas = document.getElementById('graficoDespesas').getContext('2d');
        new Chart(ctxDespesas, {
          type: 'pie',
          data: {
            labels: despesaLabels,
            datasets: [{
              data: despesaValores,
              backgroundColor: [
                'rgba(255, 99, 132, 0.7)',
                'rgba(255, 159, 64, 0.7)',
                'rgba(153, 102, 255, 0.7)',
                'rgba(75, 192, 192, 0.7)',
                'rgba(255, 206, 86, 0.7)'
              ],
              borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(255, 206, 86, 1)'
              ],
              borderWidth: 1
            }]
          },
          options: {
            responsive: true,
            plugins: {
              legend: {
                position: 'bottom'
              }
            }
          }
        });

        const ctxDividas = document.getElementById('graficoDividas').getContext('2d');
        new Chart(ctxDividas, {
          type: 'pie',
          data: {
            labels: dividaLabels,
            datasets: [{
              data: dividaValores,
              backgroundColor: [
                'rgba(201, 203, 207, 0.7)',
                'rgba(255, 205, 86, 0.7)',
                'rgba(54, 162, 235, 0.7)',
                'rgba(255, 99, 132, 0.7)',
                'rgba(153, 102, 255, 0.7)'
              ],
              borderColor: [
                'rgba(201, 203, 207, 1)',
                'rgba(255, 205, 86, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(153, 102, 255, 1)'
              ],
              borderWidth: 1
            }]
          },
          options: {
            responsive: true,
            plugins: {
              legend: {
                position: 'bottom'
              }
            }
          }
        });

    </script>

    <footer class="mt-4">
      <div class="container text-center">
        <p class="mb-1">© 2025 Administra - Todos os direitos reservados</p>
      </div>
    </footer>
  </body>
  </html>
