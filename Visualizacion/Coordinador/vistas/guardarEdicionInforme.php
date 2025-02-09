<?php
// Activar errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Iniciar sesión y cargar clases necesarias
include_once __DIR__ . "/../../../app/class.inc.php"; 
session_start();
include_once __DIR__ . "/../../../app/conex.inc.php"; 

// Verificar sesión
if (!isset($_SESSION['UserLog'])) {
    die("Error: No tienes acceso a esta página.");
}

// Obtener el usuario logueado
$voluntario = $_SESSION['UserLog'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Conexión a la base de datos
        $conexion = new PDO("mysql:host=localhost;dbname=cnrd_nueva", "root", "");
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Obtener datos del formulario
        $id_informe = $_POST['id_informe'] ?? null;
        $fecha = $_POST['fecha'];
        $region = $_POST['region'];
        $provincia = $_POST['provincia'];
        $comuna = $_POST['comuna'];
        $ubicacion = $_POST['ubicacion_georreferencial'] ?? null;
        $direccion = $_POST['direccion'];
        $tipo_zona = $_POST['tipo_zona'];
        $tipo_evento = $_POST['tipo_evento'];
        $categoria = $_POST['categoria'];
        $descripcion_evento = $_POST['descripcion_evento'];
        $procesos_realizados = $_POST['procesos_realizados'];
        $decisiones_tomadas = $_POST['decisiones_tomadas'];
        $fecha_actualizacion = date('Y-m-d H:i:s'); // Fecha de actualización

        if (!$id_informe) {
            die("Error: No se recibió un ID de informe válido.");
        }

        // Iniciar transacción
        $conexion->beginTransaction();

        // 🔹 **Actualizar el informe principal**
        $sql = "UPDATE informes SET 
                    fecha = ?, 
                    region = ?, 
                    provincia = ?, 
                    comuna = ?, 
                    ubicacion_georreferencial = ?, 
                    direccion = ?, 
                    tipo_zona = ?, 
                    tipo_evento = ?, 
                    categoria = ?, 
                    descripcion_evento = ?, 
                    procesos_realizados = ?, 
                    decisiones_tomadas = ?, 
                    updated_at = ?
                WHERE id = ?";

        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            $fecha, $region, $provincia, $comuna, $ubicacion, $direccion, 
            $tipo_zona, $tipo_evento, $categoria, 
            $descripcion_evento, $procesos_realizados, $decisiones_tomadas, 
            $fecha_actualizacion, $id_informe
        ]);

        // 🔹 **Actualizar los animales afectados**
        if (!empty($_POST['especie']) && is_array($_POST['especie'])) {
            // Eliminar los registros actuales de animales afectados para este informe
            $sqlEliminar = "DELETE FROM animales_afectados WHERE informe_id = ?";
            $stmtEliminar = $conexion->prepare($sqlEliminar);
            $stmtEliminar->execute([$id_informe]);

            // Insertar los nuevos datos de animales afectados
            $sqlAnimales = "INSERT INTO animales_afectados (
                informe_id, especie, n_atendidos, n_fallecidos, n_pendientes, created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?)";

            $stmtAnimales = $conexion->prepare($sqlAnimales);

            foreach ($_POST['especie'] as $index => $especie) {
                $n_atendidos = $_POST['n_atendidos'][$index] ?? 0;
                $n_fallecidos = $_POST['n_fallecidos'][$index] ?? 0;
                $n_pendientes = $_POST['n_pendientes'][$index] ?? 0;

                $stmtAnimales->execute([
                    $id_informe, $especie, $n_atendidos, $n_fallecidos, $n_pendientes,
                    $fecha_actualizacion, $fecha_actualizacion
                ]);
            }
        }

        // Confirmar transacción
        $conexion->commit();

        // ✅ Redirigir a la vista del informe actualizado
        header("Location: verInforme.php?id=$id_informe&success=1");
        exit();

    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conexion->rollBack();
        die("Error al actualizar el informe: " . $e->getMessage());
    }
} else {
    die("Método no permitido");
}
?>
