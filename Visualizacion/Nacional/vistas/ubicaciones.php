<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED);
//HAY QUE CORREGIR CON LOS CAMBIOS EN LA BASE DE DATOS

include_once('../plantillas/LLamstan.inc.php');
session_start();

$ruta = '';
include_once('../plantillas/DecInc.inc.php');

$vols = [];
$regiones = Usuario::obtener_regiones();
$clinicas = Clinicas::get_clinicas();

?>

<div class="container mt-5">
    <h2>Gestión de Clínicas y ubicaciones</h2>
    <!-- Botón para abrir el modal -->
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#nuevaClinicaModal">Crear Nueva Clínica</button>

    <!-- Modal para Crear Nueva Clínica -->
    <div class="modal fade" id="nuevaClinicaModal" tabindex="-1" aria-labelledby="nuevaClinicaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="../src/guardarClinica.php" method="post" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="nuevaClinicaModalLabel">Crear Nueva Clínica o Ubicación</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nombreClinica">Nombre</label>
                            <input type="text" class="form-control" id="nombreClinica" name="nombreClinica" required>
                        </div>
                        <div class="form-group">
                            <label for="tipo">Tipo</label>
                            <select class="form-control" id="tipo" name="tipo" required>
                                <option value="">Seleccionar Tipo</option>
                                <option value="Clínica">Clínica</option>
                                <option value="Punto de Atención">Punto de Atención</option>
                                <option value="Hospital">Hospital</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="direccion">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" required>
                        </div>
                        <div class="form-group">
                            <label for="region">Región</label>
                            <select class="form-control" id="id_region" name="id_region" required>
                                <option value="">Seleccionar Región</option>
                                <?php
                                foreach ($regiones as $region) {
                                    echo '<option value="' . $region["id"] . '">' . $region['nombre'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="correo">Correo</label>
                            <input type="email" class="form-control" id="correo" name="correo" required>
                        </div>
                        <div class="form-group">
                            <label for="clave">Clave</label>
                            <input type="password" class="form-control" id="clave" name="clave" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="row g-3 mb-3">
        <div class="col-md">
            <input type="text" id="filtroNombre" class="form-control" placeholder="Nombre de Clínica" onkeyup="filtrarTabla()">
        </div>
        <?php
        if ($_SESSION['UserLog']->obtener_TypeUser() === "Nacional") {
        ?>
            <div class="col-md">
                <select id="filtroRegion" class="form-control" onchange="filtrarTabla()">
                    <option value="">Región</option>
                    <?php
                    foreach ($regiones as $region) {
                        echo '<option value="' . $region["id"] . '">' . $region['nombre'] . '</option>';
                    }
                    ?>
                </select>
            </div>
        <?php
        } else {
        ?>
            <div hidden class="col-md">
                <select id="filtroRegion" class="form-control" onchange="filtrarTabla()">

                </select>
            </div>
        <?php
        }
        ?>

    </div>

    <!-- Tabla de Clínicas -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Dirección</th>
                <th>Región</th>
                <th>Correo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="clinicasTable">
            <!-- Las filas de la tabla se llenarán dinámicamente -->
        </tbody>
    </table>

    <!-- Paginador -->
    <nav>
        <ul class="pagination" id="paginadorClinicas">
            <!-- Los botones de la paginación se generarán dinámicamente -->
        </ul>
    </nav>
</div>


<script>
    // Datos de ejemplo de clínicas
    let clinicas = JSON.parse('<?php echo json_encode($clinicas); ?>');
        console.log(clinicas)
    let registrosPorPagina = 15; // Número de registros por página
    let paginaActual = 1; // Página actual

    // Función para renderizar la tabla de clínicas
    function renderizarTabla() {
        const tabla = document.getElementById('clinicasTable');
        const filtroNombre = document.getElementById('filtroNombre').value.toLowerCase();
        const filtroRegion = document.getElementById('filtroRegion').value;
        const paginador = document.getElementById('paginadorClinicas');

        // Filtrar los datos
        let clinicasFiltradas = clinicas.filter(clinica => {
            return (
                clinica.nombre.toLowerCase().includes(filtroNombre) &&
                (filtroRegion === '' || clinica.region === filtroRegion)
            );
        });

        // Calcular el total de páginas
        let totalPaginas = Math.ceil(clinicasFiltradas.length / registrosPorPagina);

        // Mostrar solo los registros de la página actual
        let startIndex = (paginaActual - 1) * registrosPorPagina;
        let endIndex = paginaActual * registrosPorPagina;
        let clinicasPagina = clinicasFiltradas.slice(startIndex, endIndex);

        // Limpiar tabla
        tabla.innerHTML = '';

        // Llenar la tabla con las clínicas filtradas
        clinicasPagina.forEach(clinica => {
            let tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${clinica.nombre}</td>
                <td>${clinica.Direccion}</td>
                <td>${clinica.id_region}</td>
                <td></td>
                <td>${clinica.Email}</td>
                <td>
                    <a href="editarClinica.php?id=${clinica.ID}" class="btn btn-sm btn-warning">Editar</a>
                </td>
            `;
            tabla.appendChild(tr);
        });

        // Crear el paginador
        paginador.innerHTML = '';
        for (let i = 1; i <= totalPaginas; i++) {
            let li = document.createElement('li');
            li.classList.add('page-item');
            if (i === paginaActual) {
                li.classList.add('active'); // Resaltar la página actual
            }
            li.innerHTML = `<a class="page-link" href="#" onclick="cambiarPagina(${i})">${i}</a>`;
            paginador.appendChild(li);
        }
    }

    // Función para cambiar de página
    function cambiarPagina(pagina) {
        paginaActual = pagina;
        renderizarTabla();
    }

    // Función para filtrar la tabla
    function filtrarTabla() {
        paginaActual = 1; // Reiniciar a la primera página al aplicar filtro
        renderizarTabla();
    }

    // Función para eliminar una clínica
    function eliminarClinica(nombre) {
        if (confirm(`¿Estás seguro de que deseas eliminar la clínica: ${nombre}?`)) {
            // Lógica para eliminar la clínica
            alert(`Clínica ${nombre} eliminada.`);
        }
    }

    // Inicializar tabla al cargar la página
    window.onload = renderizarTabla;
   
</script>
<?php
include_once('../plantillas/DecFin.inc.php');
?>