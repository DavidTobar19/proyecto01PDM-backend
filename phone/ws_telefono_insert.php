<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php';
$idPostulante = (int) ($_REQUEST['idPostulante'] ?? 0);
$numeroTelefono = $_REQUEST['numeroTelefono'] ?? '';
$tipoTelefono = $_REQUEST['tipoTelefono'] ?? '';

// Validamos que no vengan vacios
if ($idPostulante <= 0 || $numeroTelefono === '' || $tipoTelefono === '') {
    http_response_code(400);
    echo json_encode(['resultado' => '0', 'mensaje' => 'Faltan parametros obligatorios']);
    exit;
}
$sql = 'INSERT INTO PHONE (IDPOSTULANTE, NUMEROTELEFONO, TIPOTELEFONO) VALUES (?, ?, ?)';
$stmt = mysqli_prepare($conexion, $sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => mysqli_error($conexion)]);
    exit;
}
mysqli_stmt_bind_param($stmt, 'iss', $idPostulante, $numeroTelefono, $tipoTelefono);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['resultado' => '1', 'mensaje' => 'Telefono guardado correctamente']);
} else {
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => 'Error al guardar el telefono']);
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);
?>