<?php

// ============================================================
// Pantalla de apoyo propia para la HU Aut3.
// NO es la HU Aut2 (esa lleva su propio diseño, WordPress, etc.
// y le correspondía a otro integrante). Esta es solo el punto de
// entrada/salida que Aut3 necesita para funcionar y poder
// probarse de forma independiente, sin depender de nadie más.
// ============================================================

require_once __DIR__ . '/../config/db.php';

$pdo = obtenerConexion();

$stmt = $pdo->query("
    SELECT c.id_concurso, c.codigo AS codigo_concurso, p.nombre AS nombre_puesto
    FROM concurso c
    INNER JOIN puesto p ON p.id_puesto = c.id_puesto
    WHERE c.estado = 'Vigente'
    ORDER BY p.nombre ASC
");
$concursos = $stmt->fetchAll();

$pdo->prepare("INSERT INTO bitacora (id_usuario, descripcion) VALUES (:id_usuario, :descripcion)")
    ->execute([
        'id_usuario'  => ID_USUARIO_PUBLICO,
        'descripcion' => 'El usuario consulta puestos disponibles',
    ]);

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
                    <td><?= htmlspecialchars($c['codigo_concurso']) ?></td>
                    <td><?= htmlspecialchars($c['nombre_puesto']) ?></td>
                    <td>
                        <a class="boton-primario" href="participar.php?id_concurso=<?= urlencode((string)$c['id_concurso']) ?>">
                            Participar
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../plantilla/footer_publico.php'; ?>