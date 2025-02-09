<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED);

// 🟢 Incluir archivos necesarios
include_once('../plantillas/LLamstan.inc.php');
session_start();

$ruta = '';
include_once('../plantillas/DecInc.inc.php');

// 🟢 Verificar autenticación con UserLog
if (!isset($_SESSION['UserLog'])) {
    die("Error: No tienes acceso a esta página.");
}

// 🟢 Obtener el usuario desde la sesión
$voluntario = $_SESSION['UserLog']; // Ya es un objeto Voluntario

// 🟢 Obtener informes del voluntario
$informes = $voluntario->obtenerInformes() ?? [];

?>
<div class="container mt-5">
    <h2>Gestión de Informes</h2>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="crearInforme.php" class="btn btn-success">
            <i class="fas fa-plus"></i> Crear Informe
        </a>
    </div>


    <table class="table table-striped table-bordered mt-3">
        <thead class="table-dark">
            <tr>
                <th>Fecha</th>
                <th>Región</th>
                <th>Provincia</th>
                <th>Comuna</th>
                <th>Tipo de Evento</th>
                <th>Categoría</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (empty($informes)) {
                echo "<tr><td colspan='7' class='text-center'>No hay informes disponibles</td></tr>";
            } else {
                foreach ($informes as $informe) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($informe->fecha) . "</td>";
                    echo "<td>" . htmlspecialchars($informe->region) . "</td>";
                    echo "<td>" . htmlspecialchars($informe->provincia) . "</td>";
                    echo "<td>" . htmlspecialchars($informe->comuna) . "</td>";
                    echo "<td>" . htmlspecialchars($informe->tipo_evento) . "</td>";
                    echo "<td>" . htmlspecialchars($informe->categoria) . "</td>";
                    echo "<td>";
                    echo "<a href='verInforme.php?id=" . htmlspecialchars($informe->id) . "' class='btn btn-sm btn-primary'>Ver</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            }
            ?>
        </tbody>
    </table>
</div>

<script>
    let informes = <?php echo json_encode($informes); ?>;
    console.log(informes);
</script>

<?php
include_once('../plantillas/DecFin.inc.php');
?>
