<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php';

$idPostulante = (int) ($_REQUEST['idPostulante'] ?? 0);

if ($idPostulante <= 0) {
    http_response_code(400);
    echo json_encode(['resultado' => '0', 'mensaje' => 'Falta idPostulante']);
    exit;
}

$sql = 'SELECT * FROM REFERENCIA WHERE IDPOSTULANTE = ?';
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, 'i', $idPostulante);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

$referencias = [];
while ($fila = mysqli_fetch_assoc($resultado)) {
    $referencias[] = $fila;
}

echo json_encode($referencias);

mysqli_stmt_close($stmt);
mysqli_close($conexion);
