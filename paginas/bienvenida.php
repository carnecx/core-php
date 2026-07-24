<?php

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$nombreCompleto = $_SESSION['nombre_completo'] ?? 'Usuario';
$rol = $_SESSION['rol'] ?? 'Sin rol';

$clasePagina = 'pagina-bienvenida';

require_once __DIR__ . '/../plantilla/header.php';
?>

<link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    rel="stylesheet"
>

<h1>Bienvenido</h1>

<div class="mensaje-exito">
    <i class="bi bi-check-circle-fill"></i>

    <div>
        <strong>Autenticación realizada correctamente.</strong>

        <p>
            Bienvenido al sistema CORE Servicios Médicos.
        </p>
    </div>
</div>

<div class="resumen-usuario">

    <div class="dato">

        <div class="icono icono-usuario">
            <i class="bi bi-person-fill"></i>
        </div>

        <div>
            <div class="titulo">Usuario</div>

            <div class="valor">
                <?= htmlspecialchars($nombreCompleto) ?>
            </div>
        </div>

    </div>

    <div class="dato">

        <div class="icono icono-rol">
            <i class="bi bi-shield-check"></i>
        </div>

        <div>
            <div class="titulo">Rol</div>

            <div class="valor">
                <?= htmlspecialchars($rol) ?>
            </div>
        </div>

    </div>

</div>

<?php require_once __DIR__ . '/../plantilla/footer.php'; ?>