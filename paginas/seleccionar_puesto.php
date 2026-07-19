<?php

// ============================================================
// Pantalla de apoyo propia para la HU Aut3.
// entrada/salida que Aut3 necesita para funcionar y poder
// probarse de forma independiente, sin depender de nadie más.

require_once __DIR__ . '/../servicios/PuestoServicioClient.php';

$concursos = obtenerConcursosVigentes();

require_once __DIR__ . '/../plantilla/header_publico.php';
?>

<div class="tarjeta">
    <h1>Puestos disponibles</h1>
    <p class="ayuda-campo">
        Seleccione un puesto para completar el formulario de participación.
    </p>

    <table class="tabla-puestos">
        <thead>
            <tr>
                <th>Concurso</th>
                <th>Puesto</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($concursos)): ?>
                <tr><td colspan="3">No hay puestos disponibles en este momento.</td></tr>
            <?php endif; ?>
            <?php foreach ($concursos as $c): ?>
                <tr>
                    <td><?= htmlspecialchars($c['CodigoConcurso']) ?></td>
                    <td><?= htmlspecialchars($c['NombrePuesto']) ?></td>
                    <td>
                        <a class="boton-primario" href="participar.php?id_concurso=<?= urlencode((string)$c['IdConcurso']) ?>">
                            Participar
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../plantilla/footer_publico.php'; ?>