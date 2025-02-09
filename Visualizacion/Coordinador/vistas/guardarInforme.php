<?php
// Activar errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Iniciar sesión y cargar clases necesarias
include_once __DIR__ . "/../../../app/class.inc.php"; // Cargar la clase Voluntario
session_start();
include_once __DIR__ . "/../../../app/conex.inc.php"; // Asegura la conexión a la base de datos

// Verificar si hay sesión iniciada
if (!isset($_SESSION['UserLog'])) {
    die("Error: No tienes acceso a esta página.");
}

// Obtener el usuario logueado
$voluntario = $_SESSION['UserLog']; // Ahora `Voluntario` estará definido

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Conexión a la base de datos
        $conexion = new PDO("mysql:host=localhost;dbname=cnrd_nueva", "root", "");
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Obtener datos del formulario
        $fecha = $_POST['fecha'];
        $region = $_POST['region'];
        $provincia = $_POST['provincia'];
        $comuna = $_POST['comuna'];
        $ubicacion = $_POST['ubicacion_georreferencial'] ?? null;
        $direccion = $_POST['direccion'];
        $tipo_zona = $_POST['tipo_zona'];
        $voluntario_id = $voluntario->obtener_id(); // Obtener ID del usuario logueado
        $tipo_evento = $_POST['tipo_evento'];
        $categoria = $_POST['categoria'];
        $descripcion_evento = $_POST['descripcion_evento'];
        $procesos_realizados = $_POST['procesos_realizados'];
        $decisiones_tomadas = $_POST['decisiones_tomadas'];
        $fecha_creacion = date('Y-m-d H:i:s'); // Obtener fecha y hora actual

        // Preparar la consulta SQL
        $sql = "INSERT INTO informes (
            fecha, region, provincia, comuna, ubicacion_georreferencial, direccion, 
            tipo_zona, voluntario_id, tipo_evento, categoria, descripcion_evento, 
            procesos_realizados, decisiones_tomadas, created_at, updated_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Ejecutar la consulta
        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            $fecha, $region, $provincia, $comuna, $ubicacion, $direccion, 
            $tipo_zona, $voluntario_id, $tipo_evento, $categoria, 
            $descripcion_evento, $procesos_realizados, $decisiones_tomadas, 
            $fecha_creacion, $fecha_creacion
        ]);

        // ✅ Redirigir al listado de informes después de guardar
        header("Location: informes.php?success=1");
        exit();
    } catch (Exception $e) {
        die("Error al guardar el informe: " . $e->getMessage());
    }
} else {
    die("Método no permitido");
}
?>
