<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php';

$idUsuario = (int) ($_REQUEST['idUsuario'] ?? 0);

if ($idUsuario <= 0) {
    http_response_code(400);
    echo json_encode(['resultado' => '0', 'mensaje' => 'ID de usuario invalido']);
    exit;
}

$sql = 'SELECT IDPOSTULANTE, IDGENERO, IDTIPODOCUMENTO, IDUSUARIO2, NOMBREPOSTULANTE, APELLIDOPOSTULANTE, FECHANAC, DUI, NIT, DIRECCION, CORREO FROM POSTULANTE WHERE IDUSUARIO2 = ? LIMIT 1';
$stmt = mysqli_prepare($conexion, $sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => mysqli_error($conexion)]);
    exit;
}

mysqli_stmt_bind_param($stmt, 'i', $idUsuario);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if ($row = mysqli_fetch_assoc($result)) {
    echo json_encode($row);
} else {
    echo json_encode(['resultado' => '0', 'mensaje' => 'No se encontro el postulante']);
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);
?>