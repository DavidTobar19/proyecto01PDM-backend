<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php';

$idTipoDocumento = (int) ($_REQUEST['idTipoDocumento'] ?? 0);

if ($idTipoDocumento > 0) {
    $sql = 'SELECT * FROM TIPODOCUMENTO WHERE IDTIPODOCUMENTO = ?';
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $idTipoDocumento);
} else {
    $sql = 'SELECT * FROM TIPODOCUMENTO';
    $stmt = mysqli_prepare($conexion, $sql);
}

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