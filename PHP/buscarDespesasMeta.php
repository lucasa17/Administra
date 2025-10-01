<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
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

$sql = "SELECT * FROM despesa WHERE fk_usuario = $idUsuario AND fk_meta = $idMeta ORDER BY data_despesa DESC";
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
                        <i class='bi bi-trash'></i>
                    </button>
                </form>
            </td>
        </tr>
    ";
    }
echo "</tbody></table></div>";
?>
</body>
</html>