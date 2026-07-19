<?php

require_once __DIR__ . '/../servicios/PuestoServicioClient.php';
require_once __DIR__ . '/../servicios/OferenteServicioClient.php';

$idConcurso = filter_input(INPUT_GET, 'id_concurso', FILTER_VALIDATE_INT)
    ?: filter_input(INPUT_POST, 'id_concurso', FILTER_VALIDATE_INT);

$concurso = null;

if ($idConcurso) {
    $concurso = obtenerConcursoPorId($idConcurso);
}

if (!$concurso || $concurso['Estado'] !== 'Vigente') {
    require_once __DIR__ . '/../plantilla/header_publico.php';
    ?>
    <div class="tarjeta">
        <div class="caja-errores">
            El puesto seleccionado no existe o ya no se encuentra disponible.
        </div>
        <a class="boton-secundario" href="seleccionar_puesto.php">Volver a puestos disponibles</a>
    </div>
    <?php
    require_once __DIR__ . '/../plantilla/footer_publico.php';
    exit;
}

$errores = [];
$guardadoExitoso = false;

$identificacion     = '';
$tipoIdentificacion = '';
$nombreCompleto     = '';
$fechaNacimiento    = '';
$correos            = [''];
$telefonos          = [''];

$tiposIdentificacionValidos = ['Cédula de identidad', 'DIMEX', 'Pasaporte'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $identificacion     = trim((string)($_POST['identificacion'] ?? ''));
    $tipoIdentificacion = trim((string)($_POST['tipo_identificacion'] ?? ''));
    $nombreCompleto     = trim((string)($_POST['nombre_completo'] ?? ''));
    $fechaNacimiento    = trim((string)($_POST['fecha_nacimiento'] ?? ''));

    $correos   = array_filter(array_map('trim', $_POST['correos'] ?? []), fn($v) => $v !== '');
    $telefonos = array_filter(array_map('trim', $_POST['telefonos'] ?? []), fn($v) => $v !== '');
    $correos   = array_values($correos);
    $telefonos = array_values($telefonos);

    // ---------- Validaciones ----------
    if ($identificacion === '' || mb_strlen($identificacion) > 20) {
        $errores['identificacion'] = 'Indique un número de identificación válido.';
    }

    if (!in_array($tipoIdentificacion, $tiposIdentificacionValidos, true)) {
        $errores['tipo_identificacion'] = 'Seleccione un tipo de identificación válido.';
    }

    if ($nombreCompleto === '' || mb_strlen($nombreCompleto) > 150) {
        $errores['nombre_completo'] = 'Indique el nombre completo.';
    }

    $fechaNacimientoValida = DateTime::createFromFormat('Y-m-d', $fechaNacimiento);
    if (!$fechaNacimientoValida || $fechaNacimientoValida > new DateTime()) {
        $errores['fecha_nacimiento'] = 'Indique una fecha de nacimiento válida.';
    }

    if (empty($correos)) {
        $errores['correos'] = 'Indique al menos un correo electrónico.';
    } else {
        foreach ($correos as $correo) {
            if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                $errores['correos'] = 'Uno o más correos electrónicos no tienen un formato válido.';
                break;
            }
        }
    }

    if (empty($telefonos)) {
        $errores['telefonos'] = 'Debe indicar al menos un número de teléfono.';
    } else {
        foreach ($telefonos as $telefono) {
            if (!preg_match('/^[0-9+\-\s]{8,20}$/', $telefono)) {
                $errores['telefonos'] = 'Uno o más teléfonos no tienen un formato válido.';
                break;
            }
        }
    }

    $extension              = null;
    $extensionesPermitidas  = ['pdf', 'doc', 'docx'];
    $tamanoMaximoBytes      = 5 * 1024 * 1024; 

    if (!isset($_FILES['curriculum']) || $_FILES['curriculum']['error'] === UPLOAD_ERR_NO_FILE) {
        $errores['curriculum'] = 'Debe adjuntar su curriculum.';
    } elseif ($_FILES['curriculum']['error'] !== UPLOAD_ERR_OK) {
        $errores['curriculum'] = 'Ocurrió un error al subir el archivo. Intente nuevamente.';
    } else {
        $archivo = $_FILES['curriculum'];
        $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $extensionesPermitidas, true)) {
            $errores['curriculum'] = 'El curriculum debe ser un archivo PDF o Word (.pdf, .doc, .docx).';
        } elseif ($archivo['size'] > $tamanoMaximoBytes) {
            $errores['curriculum'] = 'El archivo del curriculum no debe superar 5 MB.';
        }
    }

    if (empty($errores)) {
        try {
            $contenidoCurriculum = file_get_contents($_FILES['curriculum']['tmp_name']);
            if ($contenidoCurriculum === false) {
                throw new RuntimeException('No fue posible leer el archivo del curriculum.');
            }

            $datosOferente = [
                'Identificacion'         => $identificacion,
                'TipoIdentificacion'     => $tipoIdentificacion,
                'NombreCompleto'         => $nombreCompleto,
                'FechaNacimiento'        => $fechaNacimiento,
                'Correos'                => $correos,
                'Telefonos'              => $telefonos,
                'CodigoConcurso'         => $concurso['CodigoConcurso'],
                'CurriculumBase64'       => base64_encode($contenidoCurriculum),
                'CurriculumNombreArchivo'=> $_FILES['curriculum']['name'],
            ];

            $resultado = registrarOferente($datosOferente);

            if ($resultado['Exito']) {
                $guardadoExitoso = true;
            } else {
                $errores['general'] = $resultado['Mensaje'];
            }

        } catch (Throwable $e) {
            $errores['general'] = 'Ocurrió un error al guardar la información. Intente nuevamente.';
        }
    }
}

if (empty($correos)) {
    $correos = [''];
}
if (empty($telefonos)) {
    $telefonos = [''];
}

require_once __DIR__ . '/../plantilla/header_publico.php';
?>

<div class="tarjeta">
    <h1>Formulario de participación</h1>

    <div class="subtitulo-puesto">
        Está participando por el puesto: <strong><?= htmlspecialchars($concurso['NombrePuesto']) ?></strong>
    </div>

    <?php if (!empty($errores['general'])): ?>
        <div class="caja-errores"><?= htmlspecialchars($errores['general']) ?></div>
    <?php endif; ?>

    <form method="POST" action="participar.php?id_concurso=<?= (int)$idConcurso ?>" enctype="multipart/form-data" novalidate>
        <input type="hidden" name="id_concurso" value="<?= (int)$idConcurso ?>">

        <div class="form-grupo">
            <label for="identificacion">Identificación</label>
            <input type="text" id="identificacion" name="identificacion" maxlength="20"
                   value="<?= htmlspecialchars($identificacion) ?>" required>
            <?php if (!empty($errores['identificacion'])): ?>
                <div class="error-campo"><?= htmlspecialchars($errores['identificacion']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-grupo">
            <label for="tipo_identificacion">Tipo de identificación</label>
            <select id="tipo_identificacion" name="tipo_identificacion" required>
                <option value="">Seleccione...</option>
                <?php foreach ($tiposIdentificacionValidos as $tipo): ?>
                    <option value="<?= htmlspecialchars($tipo) ?>"
                        <?= $tipoIdentificacion === $tipo ? 'selected' : '' ?>>
                        <?= htmlspecialchars($tipo) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (!empty($errores['tipo_identificacion'])): ?>
                <div class="error-campo"><?= htmlspecialchars($errores['tipo_identificacion']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-grupo">
            <label for="nombre_completo">Nombre completo</label>
            <input type="text" id="nombre_completo" name="nombre_completo" maxlength="150"
                   value="<?= htmlspecialchars($nombreCompleto) ?>" required>
            <?php if (!empty($errores['nombre_completo'])): ?>
                <div class="error-campo"><?= htmlspecialchars($errores['nombre_completo']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-grupo">
            <label for="fecha_nacimiento">Fecha de nacimiento</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento"
                   value="<?= htmlspecialchars($fechaNacimiento) ?>" required>
            <?php if (!empty($errores['fecha_nacimiento'])): ?>
                <div class="error-campo"><?= htmlspecialchars($errores['fecha_nacimiento']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-grupo">
            <label>Correo(s) electrónico(s)</label>
            <div id="contenedor-correos">
                <?php foreach ($correos as $correo): ?>
                    <div class="fila-dinamica">
                        <input type="email" name="correos[]" value="<?= htmlspecialchars($correo) ?>" placeholder="correo@ejemplo.com">
                        <button type="button" class="boton-quitar" onclick="quitarFila(this)">&times;</button>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="boton-agregar" onclick="agregarCampo('contenedor-correos', 'correos[]', 'email', 'correo@ejemplo.com')">+ Agregar otro correo</button>
            <?php if (!empty($errores['correos'])): ?>
                <div class="error-campo"><?= htmlspecialchars($errores['correos']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-grupo">
            <label>Teléfono(s) de contacto</label>
            <div id="contenedor-telefonos">
                <?php foreach ($telefonos as $telefono): ?>
                    <div class="fila-dinamica">
                        <input type="tel" name="telefonos[]" value="<?= htmlspecialchars($telefono) ?>" placeholder="8888-8888">
                        <button type="button" class="boton-quitar" onclick="quitarFila(this)">&times;</button>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="boton-agregar" onclick="agregarCampo('contenedor-telefonos', 'telefonos[]', 'tel', '8888-8888')">+ Agregar otro teléfono</button>
            <div class="ayuda-campo">Se requiere al menos un teléfono.</div>
            <?php if (!empty($errores['telefonos'])): ?>
                <div class="error-campo"><?= htmlspecialchars($errores['telefonos']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-grupo">
            <label for="curriculum">Curriculum (PDF o Word, máx. 5 MB)</label>
            <input type="file" id="curriculum" name="curriculum" accept=".pdf,.doc,.docx" required>
            <?php if (!empty($errores['curriculum'])): ?>
                <div class="error-campo"><?= htmlspecialchars($errores['curriculum']) ?></div>
            <?php endif; ?>
        </div>

        <div class="acciones-formulario">
            <button type="submit" class="boton-primario">Aceptar</button>
            <a href="seleccionar_puesto.php" class="boton-secundario">Cancelar</a>
        </div>
    </form>
</div>

<!-- Modal de éxito -->
<div class="modal-fondo <?= $guardadoExitoso ? 'activo' : '' ?>" id="modalExito">
    <div class="modal-caja">
        <p>Datos guardados de manera satisfactoria</p>
        <a href="seleccionar_puesto.php" class="boton-primario">Aceptar</a>
    </div>
</div>

<script>
function agregarCampo(idContenedor, nombreCampo, tipo, placeholder) {
    const contenedor = document.getElementById(idContenedor);
    const fila = document.createElement('div');
    fila.className = 'fila-dinamica';
    fila.innerHTML = `
        <input type="${tipo}" name="${nombreCampo}" placeholder="${placeholder}">
        <button type="button" class="boton-quitar" onclick="quitarFila(this)">&times;</button>
    `;
    contenedor.appendChild(fila);
}

function quitarFila(boton) {
    const contenedor = boton.closest('div[id^="contenedor-"]');
    if (contenedor.children.length > 1) {
        boton.closest('.fila-dinamica').remove();
    } else {
        boton.previousElementSibling.value = '';
    }
}
</script>

<?php require_once __DIR__ . '/../plantilla/footer_publico.php'; ?>