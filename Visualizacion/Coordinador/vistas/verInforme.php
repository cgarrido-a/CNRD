<?php
require_once __DIR__ . "/../../../app/class.inc.php";

session_start();

if (!isset($_SESSION['UserLog'])) {
    die("Error: No tienes acceso a esta página.");
}

if (!isset($_GET['id'])) {
    die("Error: No se proporcionó un ID de informe.");
}

$id_informe = $_GET['id'];

// Instanciar el informe
$conexion = new PDO("mysql:host=localhost;dbname=cnrd_nueva", "root", "");
$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $conexion->prepare("SELECT * FROM informes WHERE id = ?");
$stmt->execute([$id_informe]);
$informe = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$informe) {
    die("Error: Informe no encontrado.");
}

// Obtener el voluntario que hizo el informe
$stmtVoluntario = $conexion->prepare("SELECT nombre FROM voluntarios WHERE id = ?");
$stmtVoluntario->execute([$informe['voluntario_id']]);
$voluntario = $stmtVoluntario->fetch(PDO::FETCH_ASSOC);

include_once('../plantillas/LLamstan.inc.php');
?>

<div class="container mt-5">
    <h2>Detalles del Informe</h2>

    <table class="table table-bordered">
        <tbody>
            <tr>
                <th>ID Informe</th>
                <td><?php echo htmlspecialchars($informe['id']); ?></td>
            </tr>
            <tr>
                <th>Fecha</th>
                <td><?php echo htmlspecialchars($informe['fecha']); ?></td>
            </tr>
            <tr>
                <th>Región</th>
                <td><?php echo htmlspecialchars($informe['region']); ?></td>
            </tr>
            <tr>
                <th>Provincia</th>
                <td><?php echo htmlspecialchars($informe['provincia']); ?></td>
            </tr>
            <tr>
                <th>Comuna</th>
                <td><?php echo htmlspecialchars($informe['comuna']); ?></td>
            </tr>
            <tr>
                <th>Ubicación Georreferencial</th>
                <td><?php echo htmlspecialchars($informe['ubicacion_georreferencial'] ?? 'No especificado'); ?></td>
            </tr>
            <tr>
                <th>Dirección</th>
                <td><?php echo htmlspecialchars($informe['direccion']); ?></td>
            </tr>
            <tr>
                <th>Tipo de Zona</th>
                <td><?php echo htmlspecialchars($informe['tipo_zona']); ?></td>
            </tr>
            <tr>
                <th>Voluntario Responsable</th>
                <td><?php echo htmlspecialchars($voluntario['nombre'] ?? 'Desconocido'); ?></td>
            </tr>
            <tr>
                <th>Tipo de Evento</th>
                <td><?php echo htmlspecialchars($informe['tipo_evento']); ?></td>
            </tr>
            <tr>
                <th>Categoría</th>
                <td><?php echo htmlspecialchars($informe['categoria']); ?></td>
            </tr>
            <tr>
                <th>Descripción del Evento</th>
                <td><?php echo nl2br(htmlspecialchars($informe['descripcion_evento'])); ?></td>
            </tr>
            <tr>
                <th>Procesos Realizados</th>
                <td><?php echo nl2br(htmlspecialchars($informe['procesos_realizados'])); ?></td>
            </tr>
            <tr>
                <th>Decisiones Tomadas</th>
                <td><?php echo nl2br(htmlspecialchars($informe['decisiones_tomadas'])); ?></td>
            </tr>
            <tr>
                <th>Fecha de Creación</th>
                <td><?php echo htmlspecialchars($informe['created_at']); ?></td>
            </tr>
            <tr>
                <th>Última Modificación</th>
                <td><?php echo htmlspecialchars($informe['updated_at']); ?></td>
            </tr>
        </tbody>
    </table>

    <a href="informes.php" class="btn btn-secondary">Volver a la Lista</a>
</div>

<?php
include_once('../plantillas/DecFin.inc.php');
?>
