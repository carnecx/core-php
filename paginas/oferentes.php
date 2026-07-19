<?php

require_once __DIR__ . '/../servicios/PuestoServicioClient.php';
require_once __DIR__ . '/../servicios/OferenteServicioClient.php';

$codigoPuesto = trim((string)($_GET['codigo_puesto'] ?? ''));

$nombrePuesto = null;
$oferentes = [];

if ($codigoPuesto !== '') {
    foreach (obtenerPuestosActivos() as $puesto) {
        if ($puesto['Codigo'] === $codigoPuesto) {
            $nombrePuesto = $puesto['Nombre'];
            break;
        }
    }

    $oferentes = obtenerElegiblesPorPuesto($codigoPuesto);
}

require_once __DIR__ . '/../plantilla/header.php';
?>

<h1>Postulantes elegibles</h1>

<?php if ($codigoPuesto === ''): ?>
    <p class="ayuda-campo">No se indicó ningún puesto.</p>
    <a class="boton-secundario" href="puestos.php">Volver a puestos</a>
<?php else: ?>
    <p class="ayuda-campo">
        Puesto: <strong><?= htmlspecialchars($nombrePuesto ?? $codigoPuesto) ?></strong>
    </p>

    <table class="tabla-puestos">
        <thead>
            <tr>
                <th>Identificación</th>
                <th>Nombre completo</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($oferentes)): ?>
                <tr>
                    <td colspan="2">No hay postulantes elegibles para este puesto.</td>
                </tr>
            <?php endif; ?>
            <?php foreach ($oferentes as $oferente): ?>
                <tr>
                    <td><?= htmlspecialchars($oferente['Identificacion']) ?></td>
                    <td><?= htmlspecialchars($oferente['NombreCompleto']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a class="boton-secundario" href="puestos.php">Volver a puestos</a>
<?php endif; ?>

<?php require_once __DIR__ . '/../plantilla/footer.php'; ?>
