<?php
declare(strict_types=1);

function conectar(): PDO
{
    $host = getenv('DB_HOST') ?: 'db';
    $port = getenv('DB_PORT') ?: '3306';
    $base = getenv('DB_NAME') ?: 'publicaciones';
    $usuario = getenv('DB_USER') ?: 'app';
    $clave = getenv('DB_PASSWORD') !== false ? (string) getenv('DB_PASSWORD') : '';

    $pdo = new PDO(
        sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $host, $port, $base),
        $usuario,
        $clave,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
    $pdo->exec("SET time_zone = '-03:00'");

    return $pdo;
}

function leerArchivo(string $ruta, string $defecto): string
{
    if (!is_file($ruta)) {
        return $defecto;
    }

    $texto = trim((string) file_get_contents($ruta));

    return $texto !== '' ? $texto : $defecto;
}
