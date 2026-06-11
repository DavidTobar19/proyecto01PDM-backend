<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php';

$idRef = (int) ($_REQUEST['idReferencia'] ?? 0);

if ($idRef <= 0) {
    http_response_code(400);
    echo json_encode(['resultado' => '0', 'mensaje' => 'Falta idReferencia']);
    exit;
}

$sql = 'DELETE FROM REFERENCIA WHERE IDREFERENCIA = ?';
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, 'i', $idRef);

if (mysqli_stmt_execute($stmt)) {
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo json_encode(['resultado' => '1', 'mensaje' => 'Eliminado de la nube']);
    } else {
        echo json_encode(['resultado' => '0', 'mensaje' => 'No se encontro la referencia']);
    }
} else {
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => mysqli_error($conexion)]);
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);
