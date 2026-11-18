<?php
declare(strict_types=1);

require __DIR__ . '/db.php';

$publicaciones = [];
$error = null;

try {
    $pdo = conectar();
    $publicaciones = $pdo->query(
        'SELECT id, nombre, version, mensaje, creado_en
         FROM publicaciones
         ORDER BY id ASC'
    )->fetchAll();
} catch (Throwable $exception) {
    $error = 'La base de datos no está disponible en este momento.';
}

function e(string $valor): string
{
    return htmlspecialchars($valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function fechaVisible(?string $valor): string
{
    if ($valor === null || $valor === '') {
        return '';
    }

    $fecha = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $valor);

    return $fecha instanceof DateTimeImmutable ? $fecha->format('d/m/Y H:i') : $valor;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Jacobo Vocos</title>
    <style>
        :root {
            color-scheme: light;
            --ink: #1c1915;
            --muted: #5e584f;
            --line: #e4dccf;
            --paper: #f7f3ec;
            --card: #fffdf9;
            --accent: #8c3a2f;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            background: var(--paper);
            color: var(--ink);
            font: 16px/1.5 "Segoe UI", sans-serif;
        }

        main {
            width: min(960px, calc(100% - 32px));
            margin: 0 auto;
            padding: 48px 0 64px;
        }

        header {
            border-bottom: 3px solid var(--accent);
            margin-bottom: 28px;
            padding-bottom: 18px;
        }

        h1 {
            margin: 0;
            font-size: clamp(2.2rem, 5vw, 3.4rem);
            line-height: 1.05;
            letter-spacing: -0.03em;
        }

        p {
            margin: 8px 0 0;
            color: var(--muted);
        }

        .aviso {
            background: #fff4f2;
            border: 1px solid #e7b2aa;
            padding: 12px 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: var(--card);
            border: 1px solid var(--line);
        }

        th, td {
            padding: 12px 14px;
            text-align: left;
            vertical-align: top;
            border-bottom: 1px solid var(--line);
        }

        th {
            font-size: 0.78rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: var(--muted);
        }

        tr:last-child td { border-bottom: 0; }

        .vacio {
            padding: 28px 14px;
            color: var(--muted);
        }
    </style>
</head>
<body>
<main>
    <header>
        <h1>Jacobo Vocos</h1>
        <p>Versiones publicadas, en el orden en que salieron.</p>
    </header>

    <?php if ($error !== null): ?>
        <p class="aviso"><?= e($error) ?></p>
    <?php elseif ($publicaciones === []): ?>
        <p class="vacio">Todavía no hay publicaciones.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Orden</th>
                    <th>Nombre</th>
                    <th>Versión</th>
                    <th>Mensaje del commit</th>
                    <th>Publicada</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($publicaciones as $fila): ?>
                    <tr>
                        <td><?= e((string) $fila['id']) ?></td>
                        <td><?= e((string) $fila['nombre']) ?></td>
                        <td><?= e((string) $fila['version']) ?></td>
                        <td><?= e((string) $fila['mensaje']) ?></td>
                        <td><?= e(fechaVisible(isset($fila['creado_en']) ? (string) $fila['creado_en'] : null)) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>
</body>
</html>
