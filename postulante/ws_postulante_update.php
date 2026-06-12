<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php';

$idPostulante = (int) ($_REQUEST['idPostulante'] ?? 0);
$idGenero = (int) ($_REQUEST['idGenero'] ?? 0);
$idTipoDocumento = (int) ($_REQUEST['idTipoDocumento'] ?? 0);
$nombrePostulante = $_REQUEST['nombrePostulante'] ?? '';
$apellidoPostulante = $_REQUEST['apellidoPostulante'] ?? '';
$fechaNac = $_REQUEST['fechaNac'] ?? '';
$dui = $_REQUEST['dui'] ?? '';
$nit = $_REQUEST['nit'] ?? '';
$direccion = $_REQUEST['direccion'] ?? '';
$correo = $_REQUEST['correo'] ?? '';

if ($idPostulante <= 0 || $nombrePostulante === '' || $apellidoPostulante === '') {
    http_response_code(400);
    echo json_encode(['resultado' => '0', 'mensaje' => 'Faltan parametros obligatorios']);
    exit;
}

$sql = 'UPDATE POSTULANTE SET IDGENERO = ?, IDTIPODOCUMENTO = ?, NOMBREPOSTULANTE = ?, APELLIDOPOSTULANTE = ?, FECHANAC = ?, DUI = ?, NIT = ?, DIRECCION = ?, CORREO = ? WHERE IDPOSTULANTE = ?';
$stmt = mysqli_prepare($conexion, $sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => mysqli_error($conexion)]);
    exit;
}

mysqli_stmt_bind_param($stmt, 'iisssssssi', $idGenero, $idTipoDocumento, $nombrePostulante, $apellidoPostulante, $fechaNac, $dui, $nit, $direccion, $correo, $idPostulante);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['resultado' => '1', 'mensaje' => 'Postulante actualizado']);
} else {
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => 'Error al actualizar']);
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);
?>