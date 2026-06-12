<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php';

$sql = 'SELECT * FROM TIPODOCUMENTO';
$stmt = mysqli_prepare($conexion, $sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => mysqli_error($conexion)]);
    exit;
}

mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

$tiposDocumento = [];
while ($fila = mysqli_fetch_assoc($resultado)) {
    $tiposDocumento[] = $fila;
}

echo json_encode($tiposDocumento);

mysqli_stmt_close($stmt);
mysqli_close($conexion);