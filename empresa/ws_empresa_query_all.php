<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php';

$sql = 'SELECT IDEMPRESA, IDUSUARIO, NOMBREEMPRESA, RAZONSOCIAL, CORREOEMPRESA, TELEFONOEMPRESA
        FROM EMPRESA
        ORDER BY IDEMPRESA ASC';
$result = mysqli_query($conexion, $sql);

if (!$result) {
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => mysqli_error($conexion)]);
    exit;
}

$empresas = [];
while ($row = mysqli_fetch_assoc($result)) {
    $empresas[] = [
        'IDEMPRESA' => (int) $row['IDEMPRESA'],
        'IDUSUARIO' => (int) $row['IDUSUARIO'],
        'NOMBREEMPRESA' => $row['NOMBREEMPRESA'],
        'RAZONSOCIAL' => $row['RAZONSOCIAL'],
        'CORREOEMPRESA' => $row['CORREOEMPRESA'],
        'TELEFONOEMPRESA' => $row['TELEFONOEMPRESA']
    ];
}

echo json_encode($empresas);
mysqli_close($conexion);
