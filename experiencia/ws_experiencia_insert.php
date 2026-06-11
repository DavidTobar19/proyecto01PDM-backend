<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php';

$id = (int) ($_REQUEST['idPostulante'] ?? 0);
$puesto = $_REQUEST['puesto'] ?? '';
$fechaInicio = $_REQUEST['fechaInicio'] ?? '';
$fechaFin = $_REQUEST['fechaFin'] ?? '';
$funciones = $_REQUEST['funciones'] ?? '';
$nombreEmpresa = $_REQUEST['nombreEmpresa'] ?? '';
$contactoEmpresa = $_REQUEST['contactoEmpresa'] ?? '';

if ($id <= 0 || $puesto === '' || $fechaInicio === '' || $funciones === '' || $nombreEmpresa === '' || $contactoEmpresa === '') {
    http_response_code(400);
    echo json_encode(['resultado' => '0', 'mensaje' => 'Faltan parametros obligatorios']);
    exit;
}

$fechaFinDb = ($fechaFin === '') ? null : $fechaFin;

$sql = 'INSERT INTO EXPERIENCIALABORAL
        (IDPOSTULANTE, PUESTO, FECHAINICIO, FECHAFIN, FUNCIONES, NOMBREEMPRESA, CONTACTOEMPRESA)
        VALUES (?, ?, ?, ?, ?, ?, ?)';

$stmt = mysqli_prepare($conexion, $sql);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => mysqli_error($conexion)]);
    exit;
}

mysqli_stmt_bind_param($stmt, 'issssss', $id, $puesto, $fechaInicio, $fechaFinDb, $funciones, $nombreEmpresa, $contactoEmpresa);

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
