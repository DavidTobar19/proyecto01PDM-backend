<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php';

$idTelefono = (int) ($_REQUEST['idTelefono'] ?? 0);
$numeroTelefono = $_REQUEST['numeroTelefono'] ?? '';
$tipoTelefono = $_REQUEST['tipoTelefono'] ?? '';

if ($idTelefono <= 0 || $numeroTelefono === '' || $tipoTelefono === '') {
    http_response_code(400);
    echo json_encode(['resultado' => '0', 'mensaje' => 'Faltan parametros obligatorios']);
    exit;
}
$sql = 'UPDATE PHONE SET NUMEROTELEFONO = ?, TIPOTELEFONO = ? WHERE IDTELEFONO = ?';
$stmt = mysqli_prepare($conexion, $sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => mysqli_error($conexion)]);
    exit;
}

mysqli_stmt_bind_param($stmt, 'ssi', $numeroTelefono, $tipoTelefono, $idTelefono);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['resultado' => '1', 'mensaje' => 'Telefono actualizado correctamente']);
} else {
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => 'Error al actualizar el telefono']);
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);
?>