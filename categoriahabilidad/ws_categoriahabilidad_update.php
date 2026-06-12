<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php';

$idCategoria = (int) ($_REQUEST['idCategoriaHabilidad'] ?? 0);
$nombreCategoria = $_REQUEST['nombreCategoriaHabilidad'] ?? '';

if ($idCategoria <= 0 || $nombreCategoria === '') {
    http_response_code(400);
    echo json_encode(['resultado' => '0', 'mensaje' => 'Faltan parametros obligatorios']);
    exit;
}

$sql = 'UPDATE CATEGORIAHABILIDAD SET NOMBRECATEGORIAHABILIDAD = ? WHERE IDCATEGORIAHABILIDAD = ?';
$stmt = mysqli_prepare($conexion, $sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => mysqli_error($conexion)]);
    exit;
}
mysqli_stmt_bind_param($stmt, 'si', $nombreCategoria, $idCategoria);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['resultado' => '1', 'mensaje' => 'Categoria actualizada correctamente']);
} else {
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => 'Error al actualizar la categoria']);
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);
?>