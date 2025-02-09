<?php
require_once __DIR__ . "/../../../app/class.inc.php";

session_start();
include_once('../plantillas/LLamstan.inc.php');
include_once('../plantillas/DecInc.inc.php');

if (!isset($_SESSION['UserLog'])) {
    die("<div class='container mt-5 alert alert-danger'>Error: No tienes acceso a esta página.</div>");
}

if (!isset($_GET['id'])) {
    die("<div class='container mt-5 alert alert-danger'>Error: No se proporcionó un ID de informe.</div>");
}

$id_informe = $_GET['id'];

try {
    $conexion = new PDO("mysql:host=localhost;dbname=cnrd_nueva", "root", "");
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conexion->prepare("SELECT * FROM informes WHERE id = ?");
    $stmt->execute([$id_informe]);
    $informe = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$informe) {
        die("<div class='container mt-5 alert alert-warning'>Error: Informe no encontrado.</div>");
    }

    // Obtener el voluntario responsable
    $stmtVoluntario = $conexion->prepare("SELECT nombre FROM voluntarios WHERE id = ?");
    $stmtVoluntario->execute([$informe['voluntario_id']]);
    $voluntario = $stmtVoluntario->fetch(PDO::FETCH_ASSOC);

    // Obtener los animales afectados relacionados con el informe
    $stmtAnimales = $conexion->prepare("SELECT * FROM animales_afectados WHERE informe_id = ?");
    $stmtAnimales->execute([$id_informe]);
    $animales = $stmtAnimales->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("<div class='container mt-5 alert alert-danger'>Error en la base de datos: " . $e->getMessage() . "</div>");
}
?>

<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white text-center">
            <h3><i class="fas fa-file-alt"></i> Detalles del Informe</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Información General -->
                <div class="col-md-6">
                    <h5 class="text-muted">Información General</h5>
                    <hr>
                    <?php generarDetalle([
                        'ID Informe' => $informe['id'],
                        'Fecha' => $informe['fecha'],
                        'Región' => $informe['region'],
                        'Provincia' => $informe['provincia'],
                        'Comuna' => $informe['comuna'],
                        'Ubicación Georreferencial' => $informe['ubicacion_georreferencial'] ?? 'No especificado',
                        'Dirección' => $informe['direccion'],
                        'Tipo de Zona' => ucfirst($informe['tipo_zona']),
                        'Tipo de Evento' => ucfirst($informe['tipo_evento']),
                        'Categoría' => ucfirst($informe['categoria']),
                    ]); ?>
                </div>

                <!-- Información del Voluntario -->
                <div class="col-md-6">
                    <h5 class="text-muted">Voluntario Responsable</h5>
                    <hr>
                    <?php generarDetalle([
                        'Nombre' => $voluntario['nombre'] ?? 'Desconocido',
                        'Email' => $voluntario['correo'] ?? 'Desconocido',
                        'Email' => $voluntario['region_id'] ?? 'Desconocido',
                    ]); ?>
                </div>
            </div>

            <!-- Detalles del Evento -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <h5 class="text-muted">Detalles del Evento</h5>
                    <hr>
                    <?php generarDetalle([
                        'Descripción del Evento' => nl2br($informe['descripcion_evento']),
                        'Procesos Realizados' => nl2br($informe['procesos_realizados']),
                        'Decisiones Tomadas' => nl2br($informe['decisiones_tomadas']),
                    ]); ?>
                </div>
            </div>

            <!-- Tabla de Animales Afectados -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <h5 class="text-muted"><i class="fas fa-paw"></i> Animales Afectados</h5>
                    <hr>
                    <?php if (count($animales) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered mt-3">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Especie</th>
                                        <th>N° Atendidos</th>
                                        <th>N° Fallecidos</th>
                                        <th>N° Pendientes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($animales as $animal): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($animal['especie']); ?></td>
                                            <td><?php echo htmlspecialchars($animal['n_atendidos']); ?></td>
                                            <td><?php echo htmlspecialchars($animal['n_fallecidos']); ?></td>
                                            <td><?php echo htmlspecialchars($animal['n_pendientes']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No se han registrado animales afectados en este informe.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-between">
            <a href="descargarInforme.php?id=<?php echo $informe['id']; ?>" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Descargar PDF
            </a>
            <a href="informes.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver a la Lista
            </a>
        </div>
    </div>
</div>

<?php
// Función para mostrar los detalles de forma organizada
function generarDetalle($detalles)
{
    echo '<ul class="list-group">';
    foreach ($detalles as $label => $valor) {
        echo '<li class="list-group-item"><strong>' . htmlspecialchars($label) . ':</strong> ' . htmlspecialchars($valor) . '</li>';
    }
    echo '</ul>';
}

include_once('../plantillas/DecFin.inc.php');
?>
