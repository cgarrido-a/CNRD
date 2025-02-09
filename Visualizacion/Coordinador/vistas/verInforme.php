<?php
require_once __DIR__ . "/../../../app/class.inc.php";

session_start();
include_once('../plantillas/DecFin.inc.php');
include_once('../plantillas/LLamstan.inc.php');

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
    <h2 class="mb-4 text-primary"><i class="fas fa-file-alt"></i> Detalles del Informe</h2>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Campo</th>
                    <th scope="col">Información</th>
                </tr>
            </thead>
            <tbody>
                <tr><th scope="row">ID Informe</th> <td><?php echo htmlspecialchars($informe['id']); ?></td></tr>
                <tr><th scope="row">Fecha</th> <td><?php echo htmlspecialchars($informe['fecha']); ?></td></tr>
                <tr><th scope="row">Región</th> <td><?php echo htmlspecialchars($informe['region']); ?></td></tr>
                <tr><th scope="row">Provincia</th> <td><?php echo htmlspecialchars($informe['provincia']); ?></td></tr>
                <tr><th scope="row">Comuna</th> <td><?php echo htmlspecialchars($informe['comuna']); ?></td></tr>
                <tr><th scope="row">Ubicación Georreferencial</th> <td><?php echo htmlspecialchars($informe['ubicacion_georreferencial'] ?? 'No especificado'); ?></td></tr>
                <tr><th scope="row">Dirección</th> <td><?php echo htmlspecialchars($informe['direccion']); ?></td></tr>
                <tr><th scope="row">Tipo de Zona</th> <td><?php echo htmlspecialchars($informe['tipo_zona']); ?></td></tr>
                <tr><th scope="row">Voluntario Responsable</th> <td><?php echo htmlspecialchars($voluntario['nombre'] ?? 'Desconocido'); ?></td></tr>
                <tr><th scope="row">Tipo de Evento</th> <td><?php echo htmlspecialchars($informe['tipo_evento']); ?></td></tr>
                <tr><th scope="row">Categoría</th> <td><?php echo htmlspecialchars($informe['categoria']); ?></td></tr>
                <tr><th scope="row">Descripción del Evento</th> <td><?php echo nl2br(htmlspecialchars($informe['descripcion_evento'])); ?></td></tr>
                <tr><th scope="row">Procesos Realizados</th> <td><?php echo nl2br(htmlspecialchars($informe['procesos_realizados'])); ?></td></tr>
                <tr><th scope="row">Decisiones Tomadas</th> <td><?php echo nl2br(htmlspecialchars($informe['decisiones_tomadas'])); ?></td></tr>
                <tr><th scope="row">Fecha de Creación</th> <td><?php echo htmlspecialchars($informe['created_at']); ?></td></tr>
                <tr><th scope="row">Última Modificación</th> <td><?php echo htmlspecialchars($informe['updated_at']); ?></td></tr>
            </tbody>
        </table>
    </div>

    <h4 class="mt-4 text-primary"><i class="fas fa-paw"></i> Animales Afectados</h4>
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

    <div class="d-flex justify-content-between mt-4">
        <a href="descargarInforme.php?id=<?php echo $informe['id']; ?>" class="btn btn-danger">
            <i class="fas fa-file-pdf"></i> Descargar PDF
        </a>
        <a href="informes.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a la Lista
        </a>
    </div>
</div>

<?php include_once('../plantillas/DecFin.inc.php'); ?>
