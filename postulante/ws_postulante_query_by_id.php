<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php';

$idPostulante = (int) ($_REQUEST['idPostulante'] ?? 0);

if ($idPostulante <= 0) {
    http_response_code(400);
    echo json_encode(['resultado' => '0', 'mensaje' => 'ID invalido']);
    exit;
}

$sql = 'SELECT IDPOSTULANTE, IDGENERO, IDTIPODOCUMENTO, IDUSUARIO2, NOMBREPOSTULANTE, APELLIDOPOSTULANTE, FECHANAC, DUI, NIT, DIRECCION, CORREO FROM POSTULANTE WHERE IDPOSTULANTE = ? LIMIT 1';
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, 'i', $idPostulante);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    echo json_encode($row);
} else {
    echo json_encode(['resultado' => '0', 'mensaje' => 'No encontrado']);
}
mysqli_stmt_close($stmt);
mysqli_close($conexion);
?>