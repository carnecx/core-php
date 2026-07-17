<?php

// ============================================================
// Conexión a la base de datos "adminpersonal" (para Aut3)
// Los datos de host/puerto/usuario/clave deben coincidir con los
// que usa CoreWebService (ver Web.config -> connectionStrings).
// ============================================================
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3307');
define('DB_NAME', 'adminpersonal');
define('DB_USER', 'root');
define('DB_PASS', '12345');   // <-- mismo password que usa CoreWebService/Web.config

// Carpeta física donde se guardan los curriculums subidos
define('RUTA_CURRICULUMS', __DIR__ . '/../uploads/curriculums/');

// Usuario "de sistema" bajo el cual se registran en bitácora las
// acciones que hace un oferente sin sesión iniciada (Aut3 es público).
// bitacora.id_usuario es NOT NULL con FK a usuario, así que no puede
// quedar en blanco; usamos el usuario admin (id 1) que ya viene en el seed.
define('ID_USUARIO_PUBLICO', 1);

function obtenerConexion(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $opciones = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $opciones);
    }

    return $pdo;
}

/**
 * Registra una acción en la bitácora del sistema.
 * $datos se guarda como JSON en la descripción, según lo indicado
 * en "Manejo de bitácoras" del enunciado.
 */
function registrarBitacora(PDO $pdo, string $accion, array $datos, ?int $idUsuario = null): void
{
    $idUsuario = $idUsuario ?? ID_USUARIO_PUBLICO;
    $descripcion = $accion . ' ' . json_encode($datos, JSON_UNESCAPED_UNICODE);

    $stmt = $pdo->prepare("INSERT INTO bitacora (id_usuario, descripcion) VALUES (:id_usuario, :descripcion)");
    $stmt->execute([
        'id_usuario'  => $idUsuario,
        'descripcion' => $descripcion,
    ]);
}