<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../conexion.php';

$nomUsuario = trim($_REQUEST['nomUsuario'] ?? '');
$clave = trim($_REQUEST['clave'] ?? '');

if ($nomUsuario === '' || $clave === '') {
    http_response_code(400);
    echo json_encode(['resultado' => '0', 'mensaje' => 'Faltan credenciales']);
    exit;
}

$sql = 'SELECT idUsuario, idEmpresa, idPostulante, nomUsuario, clave, rol
        FROM Usuario
        WHERE nomUsuario = ? AND clave = ?
        LIMIT 1';
$stmt = mysqli_prepare($conexion, $sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['resultado' => '0', 'mensaje' => mysqli_error($conexion)]);
    exit;
}

mysqli_stmt_bind_param($stmt, 'ss', $nomUsuario, $clave);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    echo json_encode([
        'resultado' => '1',
        'idUsuario' => (int) $row['idUsuario'],
        'idEmpresa' => $row['idEmpresa'] !== null ? (int) $row['idEmpresa'] : null,
        'idPostulante' => $row['idPostulante'] !== null ? (int) $row['idPostulante'] : null,
        'nomUsuario' => $row['nomUsuario'],
        'clave' => $row['clave'],
        'rol' => $row['rol']
    ]);
} else {
    echo json_encode(['resultado' => '0', 'mensaje' => 'Usuario o contraseña incorrectos']);
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);
