<?php
/**
 * Conexion MySQL (Aiven + Render).
 *
 * Variables de entorno en Render:
 *   DB_HOST     = mysql-xxxx.d.aivencloud.com
 *   DB_USER     = avnadmin
 *   DB_PASSWORD = AVNS_XnriyVbx8qHXdJdw4VT
 *   DB_NAME     = defaultdb
 *   DB_PORT     = 14360
 *   DB_SSL      = true (opcional, por defecto activo)
 *   DB_SSL_CA   = ruta al certificado CA (opcional, default: config/ca.pem)
 *
 * Descarga el CA certificate desde Aiven y guardalo como config/ca.pem
 */

$host = getenv('DB_HOST') ?: 'localhost';
$user = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';
$database = getenv('DB_NAME') ?: 'defaultdb';
$port = (int) (getenv('DB_PORT') ?: 3306);
$sslEnabled = strtolower(getenv('DB_SSL') ?: 'true') !== 'false';
$sslCaPath = getenv('DB_SSL_CA') ?: __DIR__ . '/config/ca.pem';

$conexion = mysqli_init();

if (!$conexion) {
    responderError('No se pudo inicializar mysqli');
}

if ($sslEnabled && file_exists($sslCaPath)) {
    mysqli_ssl_set($conexion, null, null, $sslCaPath, null, null);
    $flags = MYSQLI_CLIENT_SSL;
} elseif ($sslEnabled) {
    responderError('SSL requerido pero no se encontro el certificado en: ' . $sslCaPath);
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
    responderError('Error de conexion: ' . mysqli_connect_error());
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
