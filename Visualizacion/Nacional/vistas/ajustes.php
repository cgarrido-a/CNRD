<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
include_once '../plantillas/LLamstan.inc.php';
error_reporting(E_ALL & ~E_DEPRECATED);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['UserLog'])) {
    header('Location: login.html');
    exit();
}
include '../plantillas/DecInc.inc.php';
?>

<div class="container mt-4">
    <h2 class="text-center mb-4">Ajustes</h2>

    <!-- Sección Consejos Regionales -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">Consejos Regionales</div>
        <div class="card-body">
            <table class="table table-hover table-bordered text-center">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="consejos-list">
                    <!-- Aquí se llenará dinámicamente con AJAX -->
                </tbody>
            </table>
            <button class="btn btn-primary mt-2" data-bs-toggle="modal" id="btnNuevoConsejo" data-bs-target="#nuevoConsejoModal">
                <i class="bi bi-plus-circle"></i> Agregar Consejo Regional
            </button>
        </div>
    </div>

    <!-- Sección Regiones -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white">Regiones</div>
        <div class="card-body text-center">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#nuevaRegionModal">
                <i class="bi bi-plus-circle"></i> Agregar Región
            </button>
        </div>
    </div>
</div>

<!-- Modal Nuevo Consejo Regional -->
<div class="modal fade" id="nuevoConsejoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Nuevo Consejo Regional</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formNuevoConsejo">
                    <div class="mb-3">
                        <label class="form-label">Región:</label>
                        <select class="form-select" id="SelectRegion" name="Selectregion_id" required>
                            <!-- Opciones de regiones obtenidas de la base de datos -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nombre:</label>
                        <input type="text" class="form-control" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Correo:</label>
                        <input type="email" class="form-control" name="correo" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Clave:</label>
                        <input type="password" class="form-control" name="clave" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nueva Región -->
<div class="modal fade" id="nuevaRegionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Nueva Región</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formNuevaRegion">
                    <div class="mb-3">
                        <label class="form-label">Nombre de la Región:</label>
                        <input type="text" class="form-control" name="nombreRegion" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Cargar las regiones desde PHP en una variable JS
        let Regiones = <?php echo json_encode($listaDeRegiones); ?>; // Se obtiene desde PHP

        // Llenar el select de regiones al cargar la página
        CargarRegiones();

        // Enviar nueva región por AJAX y agregarla dinámicamente
        $('#formNuevaRegion').submit(function(e) {
            e.preventDefault();
            $.post('acciones.php', $(this).serialize() + '&accion=agregarRegion', function(response) {
                let jsonResponse = JSON.parse(response);
                
                if (jsonResponse.success) {
                    let nuevaRegion = {
                        ID: jsonResponse.id, 
                        nombre: jsonResponse.nombreRegion
                    };

                    // Agregar nueva región a la lista de regiones
                    Regiones.push(nuevaRegion);

                    // Agregar la nueva región al select de consejos
                    let select = document.getElementById('SelectRegion');
                    let option = document.createElement('option');
                    option.value = nuevaRegion.ID;
                    option.textContent = nuevaRegion.nombre;
                    select.appendChild(option);

                    // Resetear formulario
                    $('#formNuevaRegion')[0].reset();

                    // Cerrar modal
                    $('#nuevaRegionModal').modal('hide');

                    console.log("Nueva región agregada:", nuevaRegion);
                }
            });
        });
    });

    function CargarRegiones() {
        let select = document.getElementById('SelectRegion');
        select.innerHTML = ''; // Limpiar antes de agregar opciones

        Regiones.forEach(region => {
            let option = document.createElement('option');
            option.textContent = region.nombre;
            option.value = region.ID;
            select.appendChild(option);
        });
    }
</script>


<?php
include '../plantillas/DecFin.inc.php';
?>
