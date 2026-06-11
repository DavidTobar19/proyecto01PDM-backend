<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php';

$idRed = (int) ($_REQUEST['idRed'] ?? 0);

if ($idRed <= 0) {
    http_response_code(400);
    echo json_encode(['resultado' => '0', 'mensaje' => 'ID de Red invalido']);
    exit;
}

$sql = "DELETE FROM REDSOCIAL WHERE idRed = ?";
$stmt = mysqli_prepare($conexion, $sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => mysqli_error($conexion)]);
    exit;
}

mysqli_stmt_bind_param($stmt, 'i', $idRed);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode([
        'resultado' => '1',
        'mensaje' => 'Red social eliminada de la nube'
    ]);
} else {
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => mysqli_error($conexion)]);
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);