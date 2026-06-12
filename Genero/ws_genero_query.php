<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php';

$idGenero = (int) ($_REQUEST['idGenero'] ?? 0);

if ($idGenero > 0) {
    $sql = 'SELECT * FROM GENERO WHERE IDPGENERO = ?';
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $idGenero);
} else {
    $sql = 'SELECT * FROM GENERO';
    $stmt = mysqli_prepare($conexion, $sql);
}

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => mysqli_error($conexion)]);
    exit;
}

mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

$generos = [];
while ($fila = mysqli_fetch_assoc($resultado)) {
    $generos[] = $fila;
}

echo json_encode($generos);

mysqli_stmt_close($stmt);
mysqli_close($conexion);