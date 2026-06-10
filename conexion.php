<?php
/**
 * Configuracion de base de datos para Render.
 *
 * En el Web Service de PHP (Render -> Environment Variables) define:
 *   DB_HOST     = hostname de tu MySQL
 *   DB_USER     = usuario (ej: root)
 *   DB_PASSWORD = contrasena
 *   DB_NAME     = nombre de la base (ej: bolsatrabajo)
 *   DB_PORT     = 3306 (opcional)
 */

$host = getenv('DB_HOST') ?: 'localhost';
$user = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';
$database = getenv('DB_NAME') ?: 'bolsatrabajo';
$port = (int) (getenv('DB_PORT') ?: 3306);

$conexion = mysqli_connect($host, $user, $password, $database, $port);

if (!$conexion) {
    http_response_code(500);
    echo json_encode([
        'resultado' => '0',
        'mensaje' => 'Error de conexion: ' . mysqli_connect_error()
    ]);
    exit;
}

mysqli_set_charset($conexion, 'utf8mb4');
