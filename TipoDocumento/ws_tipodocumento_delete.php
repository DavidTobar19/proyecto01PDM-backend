<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php';

$idTipoDocumento = (int) ($_REQUEST['idTipoDocumento'] ?? 0);

if ($idTipoDocumento <= 0) {
    http_response_code(400);
    echo json_encode(['resultado' => '0', 'mensaje' => 'Falta idTipoDocumento']);
    exit;
}

$sql = 'DELETE FROM TIPODOCUMENTO WHERE IDTIPODOCUMENTO = ?';
$stmt = mysqli_prepare($conexion, $sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => mysqli_error($conexion)]);
    exit;
}

mysqli_stmt_bind_param($stmt, 'i', $idTipoDocumento);

if (mysqli_stmt_execute($stmt)) {
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo json_encode(['resultado' => '1', 'mensaje' => 'Eliminado de la nube']);
    } else {
        echo json_encode(['resultado' => '0', 'mensaje' => 'No se encontro el tipo de documento']);
    }
} else {
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => mysqli_error($conexion)]);
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);