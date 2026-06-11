<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php';

$idTelefono = (int) ($_REQUEST['idTelefono'] ?? 0);

if ($idTelefono <= 0) {
    http_response_code(400);
    echo json_encode(['resultado' => '0', 'mensaje' => 'ID de telefono invalido']);
    exit;
}

// Eliminamos usando la llave primaria IDTELEFONO
$sql = 'DELETE FROM PHONE WHERE IDTELEFONO = ?';
$stmt = mysqli_prepare($conexion, $sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => mysqli_error($conexion)]);
    exit;
}

mysqli_stmt_bind_param($stmt, 'i', $idTelefono);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['resultado' => '1', 'mensaje' => 'Telefono eliminado correctamente']);
} else {
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => 'Error al eliminar el telefono']);
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);
?>