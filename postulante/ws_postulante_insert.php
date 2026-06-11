<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php';

$idGenero = (int) ($_REQUEST['idGenero'] ?? 0);
$idTipoDocumento = (int) ($_REQUEST['idTipoDocumento'] ?? 0);
$idUsuario = (int) ($_REQUEST['idUsuario'] ?? 0);
$nombrePostulante = $_REQUEST['nombrePostulante'] ?? '';
$apellidoPostulante = $_REQUEST['apellidoPostulante'] ?? '';
$fechaNac = $_REQUEST['fechaNac'] ?? '';
$dui = $_REQUEST['dui'] ?? '';
$nit = $_REQUEST['nit'] ?? '';
$direccion = $_REQUEST['direccion'] ?? '';
$correo = $_REQUEST['correo'] ?? '';

// Validacion basica
if ($idGenero <= 0 || $idTipoDocumento <= 0 || $idUsuario <= 0 || $nombrePostulante === '' || $apellidoPostulante === '') {
    http_response_code(400);
    echo json_encode(['resultado' => '0', 'mensaje' => 'Faltan parametros obligatorios']);
    exit;
}

$sql = 'INSERT INTO POSTULANTE (IDGENERO, IDTIPODOCUMENTO, IDUSUARIO, NOMBREPOSTULANTE, APELLIDOPOSTULANTE, FECHANAC, DUI, NIT, DIRECCION, CORREO) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
$stmt = mysqli_prepare($conexion, $sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => mysqli_error($conexion)]);
    exit;
}

mysqli_stmt_bind_param($stmt, 'iiisssssss', $idGenero, $idTipoDocumento, $idUsuario, $nombrePostulante, $apellidoPostulante, $fechaNac, $dui, $nit, $direccion, $correo);

if (mysqli_stmt_execute($stmt)) {
    $nuevoId = mysqli_insert_id($conexion);
    echo json_encode(['resultado' => '1', 'mensaje' => 'Postulante guardado', 'idPostulante' => $nuevoId]);
} else {
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => 'Error al guardar el postulante']);
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);
?>