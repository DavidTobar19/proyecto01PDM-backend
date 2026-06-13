<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php';

$sql = 'SELECT idUsuario, idEmpresa, idPostulante, nomUsuario, clave, rol
        FROM Usuario
        ORDER BY idUsuario ASC';
$result = mysqli_query($conexion, $sql);

if (!$result) {
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => mysqli_error($conexion)]);
    exit;
}

$usuarios = [];
while ($row = mysqli_fetch_assoc($result)) {
    $usuarios[] = [
        'idUsuario' => (int) $row['idUsuario'],
        'idEmpresa' => $row['idEmpresa'] !== null ? (int) $row['idEmpresa'] : null,
        'idPostulante' => $row['idPostulante'] !== null ? (int) $row['idPostulante'] : null,
        'nomUsuario' => $row['nomUsuario'],
        'clave' => $row['clave'],
        'rol' => $row['rol']
    ];
}

echo json_encode($usuarios);

mysqli_close($conexion);
