<?php

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$nombreCompleto = $_SESSION['nombre_completo'] ?? 'Usuario';
$rol = $_SESSION['rol'] ?? 'Sin rol';

date_default_timezone_set('America/Costa_Rica');

$fechaActual = date('d/m/Y');
$horaActual = date('H:i');

$clasePagina = 'pagina-bienvenida';

require_once __DIR__ . '/../plantilla/header.php';
?>

<link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    rel="stylesheet"
>

<h1>Bienvenido</h1>

<div class="mensaje-exito">
    <i class="bi bi-check-circle"></i>

    <div>
        <strong>Autenticación realizada correctamente.</strong>

        <p>
            Bienvenido al sistema CORE Servicios Médicos.
            Su sesión ha iniciado correctamente.
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

<div class="paneles-bienvenida">

    <section class="panel-informativo">

        <div class="encabezado-panel">
            <h2>Información del sistema</h2>
        </div>

        <p class="descripcion-panel">
            Este sistema permite consultar los puestos disponibles,
            participar en concursos y utilizar las opciones disponibles
            según los permisos del usuario autenticado.
        </p>

        <div class="lista-informacion">

            <div class="elemento-informacion">
                Consulte los puestos disponibles actualmente.
            </div>

            <div class="elemento-informacion">
                Participe en los concursos disponibles.
            </div>

            <div class="elemento-informacion">
                Mantenga su información personal actualizada.
            </div>

            <div class="elemento-informacion">
                Su sesión permanecerá activa hasta cerrar sesión.
            </div>

        </div>

    </section>

    <section class="panel-informativo">

        <div class="encabezado-panel">
            <h2>Información de la sesión</h2>
        </div>

        <div class="tabla-sesion">

            <div class="fila-sesion">
                <strong>Usuario:</strong>

                <span>
                    <?= htmlspecialchars($nombreCompleto) ?>
                </span>
            </div>

            <div class="fila-sesion">
                <strong>Rol:</strong>

                <span>
                    <?= htmlspecialchars($rol) ?>
                </span>
            </div>

            <div class="fila-sesion">
                <strong>Estado:</strong>

                <span class="estado-activo">
                    Sesión activa
                </span>
            </div>

            <div class="fila-sesion">
                <strong>Fecha:</strong>

                <span>
                    <?= htmlspecialchars($fechaActual) ?>
                </span>
            </div>

            <div class="fila-sesion">
                <strong>Hora:</strong>

                <span>
                    <?= htmlspecialchars($horaActual) ?>
                </span>
            </div>

        </div>

    </section>

</div>

<section class="panel-accesos">

    <div class="encabezado-panel">
        <h2>Accesos rápidos</h2>
    </div>

    <p class="descripcion-panel">
        Ingrese rápidamente a las opciones principales del sistema.
    </p>

    <div class="accesos-rapidos">

        <a href="puestos.php" class="acceso-rapido">

            <div>
                <strong>Ver puestos disponibles</strong>

                <span>
                    Consulte todos los puestos registrados.
                </span>
            </div>

        </a>

        <a href="seleccionar_puesto.php" class="acceso-rapido">

            <div>
                <strong>Participar en un concurso</strong>

                <span>
                    Seleccione un puesto para participar.
                </span>
            </div>

        </a>

    </div>

</section>

<?php require_once __DIR__ . '/../plantilla/footer.php'; ?>