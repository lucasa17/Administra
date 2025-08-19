<?php
session_start();
include 'conexao.php';
mysqli_set_charset($conn, 'utf8');

if (!isset($_SESSION['ID_USER']) || !isset($_GET['idMeta'])) {
    echo "<p class='text-danger'>Dados insuficientes.</p>";
    exit;
}

$idUsuario = $_SESSION['ID_USER'];
$idMeta = intval($_GET['idMeta']);

$sql = "SELECT nome_despesa, valor_despesa, data_despesa FROM despesa WHERE fk_usuario = $idUsuario AND fk_meta = $idMeta ORDER BY data_despesa DESC";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) === 0) {
    echo "<p>Nenhuma despesa vinculada a esta meta.</p>";
    exit;
}

echo "
    <div style='padding-left: 10px;'>
    <h5 class='mt-4' style='padding-left: 10px;'>Despesas vinculadas</h5>
    <table class='table table-sm table-bordered mt-2' style='width: 99%;'>
        <thead>
            <tr>
                <th>Descrição</th>
                <th>Valor (R$)</th>
                <th>Data</th>
            </tr>
        </thead>
        <tbody>
";

while ($row = mysqli_fetch_assoc($result)) {
    $desc = htmlspecialchars($row['nome_despesa']);
    $valor = number_format($row['valor_despesa'], 2, ',', '.');
    $data = date("d/m/Y", strtotime($row['data_despesa']));
    echo "
        <tr>
            <td>$desc</td>
            <td>$valor</td>
            <td>$data</td>
        </tr>
    ";
}
echo "</tbody></table></div>";
