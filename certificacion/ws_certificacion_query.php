<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php';

$idPostulante = (int) ($_REQUEST['idPostulante'] ?? 0);

if ($idPostulante <= 0) {
    http_response_code(400);
    echo json_encode(['resultado' => '0', 'mensaje' => 'Falta idPostulante']);
    exit;
}

$sql = 'SELECT * FROM CERTIFICACION WHERE IDPOSTULANTE = ?';
$stmt = mysqli_prepare($conexion, $sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => mysqli_error($conexion)]);
    exit;
}

mysqli_stmt_bind_param($stmt, 'i', $idPostulante);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

$certificaciones = [];
while ($fila = mysqli_fetch_assoc($resultado)) {
    $certificaciones[] = $fila;
}

echo json_encode($certificaciones);

mysqli_stmt_close($stmt);
mysqli_close($conexion);
