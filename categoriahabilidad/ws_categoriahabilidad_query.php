<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php';

$sql = 'SELECT IDCATEGORIAHABILIDAD, NOMBRECATEGORIAHABILIDAD FROM CATEGORIAHABILIDAD';
$stmt = mysqli_prepare($conexion, $sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => mysqli_error($conexion)]);
    exit;
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$categorias = [];
while ($row = mysqli_fetch_assoc($result)) {
    $categorias[] = $row;
}

echo json_encode($categorias);

mysqli_stmt_close($stmt);
mysqli_close($conexion);
?>