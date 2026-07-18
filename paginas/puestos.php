<?php

require_once __DIR__ . '/../servicios/PuestoServicioClient.php';

$puestos = obtenerPuestosActivos();

require_once __DIR__ . '/../plantilla/header.php';
?>

<h1>Puestos disponibles</h1>

<table class="tabla-puestos">
    <thead>
        <tr>
            <th>Nombre del puesto</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($puestos as $puesto): ?>
            <tr>
                <td>
                    <a href="oferentes.php?codigo_puesto=<?= urlencode($puesto['Codigo']) ?>">
                        <?= htmlspecialchars($puesto['Nombre']) ?>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once __DIR__ . '/../plantilla/footer.php'; ?>