<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED);
//HAY QUE CORREGIR CON LOS CAMBIOS EN LA BASE DE DATOS

include_once('../plantillas/LLamstan.inc.php');
session_start();

$ruta = '';
include_once('../plantillas/DecInc.inc.php');
$resultados = Voluntarios::obtenervoluntariosdesplegados();

?>
<div class="container mt-5">
    <h2>Voluntarios Desplegados</h2>

    <table class="table table-striped table-bordered mt-3">
        <thead class="table-dark">
            <tr>
                <th>Ubicación</th>
                <th>Alimentacion</th>
                <th>Casos alergia alimentaria</th>
            </tr>
        </thead>
        <tbody>
            <?php

            $totalVoluntarios = 0;
            foreach ($resultados as $ubicacion => $infoUbicacion) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($infoUbicacion['nombre']) . "</td>"; // Mostrar el nombre
                echo "<td>";
                echo "<ul>";
                foreach ($infoUbicacion['tipos_alimentacion'] as $tipo => $cantidad) {
                    $totalVoluntarios += $cantidad; // Sumar al total
                    echo "<li>" . htmlspecialchars($tipo) . ": " . htmlspecialchars($cantidad) . "</li>";
                }
                echo "</ul>";
                echo "</td>";
                echo "<td>";
                if (!empty($infoUbicacion['voluntarios_con_enfermedades'])) {
                    echo "<ul>";
                    foreach ($infoUbicacion['voluntarios_con_enfermedades'] as $voluntario) {
                        echo "<li>" . htmlspecialchars($voluntario) . "</li>";
                    }
                    echo "</ul>";
                } else {
                    echo "Ninguno";
                }
                echo "</td>";
                echo "</tr>";
            }
            ?>
        <tfoot>
            <?php
            echo "<tr><td colspan='3'><strong>Total Voluntarios: $totalVoluntarios</strong></td></tr>";
            ?>
        </tfoot>
        </tbody>
    </table>
</div>

<script>
    let ubicaciones = <?php echo json_encode($resultados); ?>;
    console.log(ubicaciones)
</script>
<?php
include_once('../plantillas/DecFin.inc.php');
?>