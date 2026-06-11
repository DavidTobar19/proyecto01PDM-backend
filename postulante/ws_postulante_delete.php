<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php';

$idPostulante = (int) ($_REQUEST['idPostulante'] ?? 0);

if ($idPostulante <= 0) {
    http_response_code(400);
    echo json_encode(['resultado' => '0', 'mensaje' => 'ID invalido']);
    exit;
}

$sql = 'DELETE FROM POSTULANTE WHERE IDPOSTULANTE = ?';
$stmt = mysqli_prepare($conexion, $sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => mysqli_error($conexion)]);
    exit;
}

mysqli_stmt_bind_param($stmt, 'i', $idPostulante);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['resultado' => '1', 'mensaje' => 'Postulante eliminado']);
} else {
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => 'Error al eliminar']);
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);
?>