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
$regiones = Usuario::obtener_regiones();


?>
<div class="container mt-5">
    <h2>Voluntarios Desplegados</h2>
    <!-- Filtros -->
    <div class="row g-3 mb-3">

        <div class="col-md">
            <select id="filtroRegion" class="form-select">
                <?php
                foreach ($regiones as $region) {
                    echo "<option value='" . $region['id'] . "'>" . $region['nombre'] . "</option>";
                }
                ?>
            </select>
        </div>

    </div>
    <table class="table table-striped table-bordered mt-3">
        <thead class="table-dark">
            <tr>
                <th>Ubicación</th>
                <th>Alimentacion</th>
                <th>Casos alergia alimentaria</th>
            </tr>
        </thead>
        <tbody id="tablaVoluntarios">
            
        <tfoot id="totalVoluntarios">
            
        </tfoot>
        </tbody>
    </table>
</div>
<script id="data-ubicaciones" type="application/json">
    <?php echo json_encode($resultados); ?>
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    let dataElement = document.getElementById("data-ubicaciones");
    if (!dataElement) {
        console.error("Error: No se encontró el elemento con id 'data-ubicaciones'");
        return;
    }

    let ubicaciones = JSON.parse(dataElement.textContent);
    let filtroRegion = document.getElementById("filtroRegion");
    let tbody = document.getElementById("tablaVoluntarios");
    let totalVoluntariosElemento = document.getElementById("totalVoluntarios");

    if (!tbody || !filtroRegion || !totalVoluntariosElemento) {
        console.error("Error: Faltan elementos en el DOM.");
        return;
    }

    function filtrarTabla() {
        let regionSeleccionada = filtroRegion.value;
        tbody.innerHTML = "";
        let totalVoluntarios = 0;

        Object.entries(ubicaciones).forEach(([direccion, infoUbicacion]) => {
            if (regionSeleccionada === "" || infoUbicacion.id_region == regionSeleccionada) {
                let fila = document.createElement("tr");
                fila.innerHTML = `
                    <td>${infoUbicacion.nombre}</td>
                    <td><ul>${Object.entries(infoUbicacion.tipos_alimentacion).map(([tipo, cantidad]) => {
                        totalVoluntarios += cantidad;
                        return `<li>${tipo}: ${cantidad}</li>`;
                    }).join("")}</ul></td>
                    <td>${infoUbicacion.voluntarios_con_enfermedades.length > 0 ?
                        `<ul>${infoUbicacion.voluntarios_con_enfermedades.map(v => `<li>${v}</li>`).join("")}</ul>`
                        : "Ninguno"}</td>
                `;
                tbody.appendChild(fila);
            }
        });
        totalVoluntariosElemento.textContent = `Total Voluntarios: ${totalVoluntarios}`;
    }

    filtroRegion.addEventListener("change", filtrarTabla);
    filtrarTabla(); // Aplicar filtro inicial
});


</script>
<?php
include_once('../plantillas/DecFin.inc.php');
?>