<?php
declare(strict_types=1);

require __DIR__ . '/db.php';

$autor = 'Jacobo Vocos';
$version = leerArchivo(__DIR__ . '/app_version.txt', 'dev');
$mensaje = leerArchivo(__DIR__ . '/commit_message.txt', 'sin mensaje');

if (strlen($mensaje) > 500) {
    $mensaje = substr($mensaje, 0, 500);
}

$pdo = null;
$ultimoError = 'sin detalle';

for ($intento = 1; $intento <= 40; $intento++) {
    try {
        $pdo = conectar();
        break;
    } catch (Throwable $error) {
        $ultimoError = $error->getMessage();
        fwrite(STDERR, "Intento {$intento}: {$ultimoError}\n");
        sleep(3);
    }
}

if (!$pdo instanceof PDO) {
    fwrite(STDERR, "No se pudo conectar a MySQL: {$ultimoError}\n");
    exit(1);
}

$pdo->exec(
    'CREATE TABLE IF NOT EXISTS publicaciones (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(120) NOT NULL,
        version VARCHAR(40) NOT NULL,
        mensaje VARCHAR(500) NOT NULL,
        creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY uq_publicaciones_version (version)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
);

$insertar = $pdo->prepare(
    'INSERT IGNORE INTO publicaciones (nombre, version, mensaje)
     VALUES (:nombre, :version, :mensaje)'
);
$insertar->execute([
    'nombre' => $autor,
    'version' => $version,
    'mensaje' => $mensaje,
]);

echo "Registrada la version {$version}\n";
