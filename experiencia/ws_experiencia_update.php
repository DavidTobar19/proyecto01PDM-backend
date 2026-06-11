<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php';

$idExp = (int) ($_REQUEST['idExperiencia'] ?? 0);
$puesto = $_REQUEST['puesto'] ?? '';
$fechaInicio = $_REQUEST['fechaInicio'] ?? '';
$fechaFin = $_REQUEST['fechaFin'] ?? '';
$funciones = $_REQUEST['funciones'] ?? '';
$nombreEmpresa = $_REQUEST['nombreEmpresa'] ?? '';
$contactoEmpresa = $_REQUEST['contactoEmpresa'] ?? '';

if ($idExp <= 0 || $puesto === '' || $fechaInicio === '' || $funciones === '' || $nombreEmpresa === '' || $contactoEmpresa === '') {
    http_response_code(400);
    echo json_encode(['resultado' => '0', 'mensaje' => 'Faltan parametros obligatorios']);
    exit;
}

$fechaFinDb = ($fechaFin === '') ? null : $fechaFin;

$sql = 'UPDATE EXPERIENCIALABORAL SET
        PUESTO = ?, FECHAINICIO = ?, FECHAFIN = ?, FUNCIONES = ?,
        NOMBREEMPRESA = ?, CONTACTOEMPRESA = ?
        WHERE IDEXPERIENCIALABORAL = ?';

$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, 'ssssssi', $puesto, $fechaInicio, $fechaFinDb, $funciones, $nombreEmpresa, $contactoEmpresa, $idExp);

if (mysqli_stmt_execute($stmt)) {
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo json_encode(['resultado' => '1', 'mensaje' => 'Actualizado en la nube']);
    } else {
        $check = mysqli_prepare($conexion, 'SELECT IDEXPERIENCIALABORAL FROM EXPERIENCIALABORAL WHERE IDEXPERIENCIALABORAL = ?');
        mysqli_stmt_bind_param($check, 'i', $idExp);
        mysqli_stmt_execute($check);
        $exists = mysqli_stmt_get_result($check)->num_rows > 0;
        mysqli_stmt_close($check);

        if ($exists) {
            echo json_encode(['resultado' => '1', 'mensaje' => 'Sin cambios en la nube']);
        } else {
            echo json_encode(['resultado' => '0', 'mensaje' => 'No se encontro la experiencia']);
        }
    }
} else {
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => mysqli_error($conexion)]);
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);
