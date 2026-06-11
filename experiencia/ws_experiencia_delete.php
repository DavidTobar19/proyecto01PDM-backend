<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php';

$idExp = (int) ($_REQUEST['idExperiencia'] ?? 0);

if ($idExp <= 0) {
    http_response_code(400);
    echo json_encode(['resultado' => '0', 'mensaje' => 'Falta idExperiencia']);
    exit;
}

$sql = 'DELETE FROM EXPERIENCIALABORAL WHERE IDEXPERIENCIALABORAL = ?';
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, 'i', $idExp);

if (mysqli_stmt_execute($stmt)) {
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo json_encode(['resultado' => '1', 'mensaje' => 'Eliminado de la nube']);
    } else {
        echo json_encode(['resultado' => '0', 'mensaje' => 'No se encontro la experiencia']);
    }
} else {
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => mysqli_error($conexion)]);
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);
