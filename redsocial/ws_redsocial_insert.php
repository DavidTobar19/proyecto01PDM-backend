<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php';

$idPostulante = (int) ($_REQUEST['idPostulante'] ?? 0);
$nombreRed = $_REQUEST['nombreRed'] ?? '';
$enlace = $_REQUEST['enlace'] ?? '';

if ($idPostulante <= 0 || $nombreRed === '' || $enlace === '') {
    http_response_code(400);
    echo json_encode(['resultado' => '0', 'mensaje' => 'Faltan parametros obligatorios']);
    exit;
}

$sql = "INSERT INTO REDSOCIAL (idPostulante, nombreRed, enlace) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($conexion, $sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => mysqli_error($conexion)]);
    exit;
}

mysqli_stmt_bind_param($stmt, 'iss', $idPostulante, $nombreRed, $enlace);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode([
        'resultado' => '1',
        'mensaje' => 'Red social guardada en la nube',
        'idRed' => mysqli_insert_id($conexion)
    ]);
} else {
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => mysqli_error($conexion)]);
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);