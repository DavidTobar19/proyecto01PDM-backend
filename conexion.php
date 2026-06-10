<?php
/**
 * Conexion MySQL (Aiven + Render).
 *
 * NO pongas credenciales aqui. Configuralas solo en Render:
 *   Environment -> Environment Variables
 *
 *   DB_HOST      = host de Aiven (mysql-xxxx.d.aivencloud.com)
 *   DB_USER      = avnadmin
 *   DB_PASSWORD  = (password de Aiven, solo en Render)
 *   DB_NAME      = defaultdb
 *   DB_PORT      = 14360
 *   DB_SSL       = true
 *
 * El certificado CA va en config/ca.pem (no es secreto, puede estar en el repo).
 */

$host = getenv('DB_HOST') ?: '';
$user = getenv('DB_USER') ?: '';
$password = getenv('DB_PASSWORD') ?: '';
$database = getenv('DB_NAME') ?: 'defaultdb';
$port = (int) (getenv('DB_PORT') ?: 3306);
$sslEnabled = strtolower(getenv('DB_SSL') ?: 'true') !== 'false';
$sslCaPath = getenv('DB_SSL_CA') ?: __DIR__ . '/config/ca.pem';

if ($host === '' || $user === '' || $password === '') {
    responderError(
        'Faltan variables de entorno DB_HOST, DB_USER o DB_PASSWORD. Configuralas en Render.'
    );
}

$conexion = mysqli_init();

if (!$conexion) {
    responderError('No se pudo inicializar mysqli');
}

if ($sslEnabled && file_exists($sslCaPath)) {
    mysqli_ssl_set($conexion, null, null, $sslCaPath, null, null);
    $flags = MYSQLI_CLIENT_SSL;
} elseif ($sslEnabled) {
    responderError('SSL requerido pero no se encontro config/ca.pem en el servidor');
} else {
    $flags = 0;
}

$conectado = mysqli_real_connect(
    $conexion,
    $host,
    $user,
    $password,
    $database,
    $port,
    null,
    $flags
);

if (!$conectado) {
    responderError('Error de conexion a MySQL. Revisa host, puerto, password y SSL en Render.');
}

mysqli_set_charset($conexion, 'utf8mb4');

function responderError(string $mensaje): void
{
    if (!headers_sent()) {
        http_response_code(500);
        header('Content-Type: application/json; charset=utf-8');
    }
    echo json_encode([
        'resultado' => '0',
        'mensaje' => $mensaje
    ]);
    exit;
}
