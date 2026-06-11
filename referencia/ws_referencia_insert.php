<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php';

$id = (int) ($_REQUEST['idPostulante'] ?? 0);
$nombre = $_REQUEST['nombre'] ?? '';
$parentesco = $_REQUEST['parentesco'] ?? '';
$telefono = $_REQUEST['telefono'] ?? '';

if ($id <= 0 || $nombre === '' || $parentesco === '' || $telefono === '') {
    http_response_code(400);
    echo json_encode(['resultado' => '0', 'mensaje' => 'Faltan parametros obligatorios']);
    exit;
}

$sql = 'INSERT INTO REFERENCIA (IDPOSTULANTE, NOMBREFERENCIA, PARENTESCO, TELEFONO) VALUES (?, ?, ?, ?)';
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, 'isss', $id, $nombre, $parentesco, $telefono);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode([
        'resultado' => '1',
        'mensaje' => 'Guardado en la nube',
        'id' => mysqli_insert_id($conexion)
    ]);
} else {
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => mysqli_error($conexion)]);
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);
