<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php';

$id = (int) ($_REQUEST['idPostulante'] ?? 0);
$nom = $_REQUEST['nombre'] ?? '';
$tip = $_REQUEST['tipo'] ?? '';
$cod = $_REQUEST['codigo'] ?? '';
$inst = $_REQUEST['institucion'] ?? '';
$fec = $_REQUEST['fecha'] ?? '';

if ($id <= 0 || $nom === '' || $tip === '' || $cod === '' || $inst === '' || $fec === '') {
    http_response_code(400);
    echo json_encode(['resultado' => '0', 'mensaje' => 'Faltan parametros obligatorios']);
    exit;
}

$sql = "INSERT INTO CERTIFICACION
        (IDPOSTULANTE, NOMBRECERTIFICACION, TIPOCERTIFICACION, CODIGOCERTIFICACION, INSTITUCIONCERTIFICACION, FECHACERTIFICACION)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conexion, $sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => mysqli_error($conexion)]);
    exit;
}

mysqli_stmt_bind_param($stmt, 'isssss', $id, $nom, $tip, $cod, $inst, $fec);

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
