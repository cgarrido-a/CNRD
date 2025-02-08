<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED);
//HAY QUE CORREGIR CON LOS CAMBIOS EN LA BASE DE DATOS

include_once('../plantillas/LLamstan.inc.php');
session_start();

$ruta = '';
include_once('../plantillas/DecInc.inc.php');
$voluntarios = Voluntarios::obtenerVoluntarios();

$vols = [];
$regiones = Usuario::obtener_regiones();
if (count($voluntarios)) {
    foreach ($voluntarios as $voluntario) {
        $vols[] = array(
            "id" => $voluntario->obtener_id(),
            "nombre" => $voluntario->obtener_nombre(),
            "telefono" => $voluntario->obtener_telefono(),
            "nombre_region" => $voluntario->obtener_id_region(),
            "region" => $voluntario->obtener_region(), //devuelve el nombre de la region
            "comuna" => $voluntario->obtener_comuna(),
            "estado" => $voluntario->obtener_estado(),
            "profesion" => $voluntario->obtener_profesion(),
        );
    }
}
?>
<div class="container mt-5">
    <h2>Gestión de Voluntarios</h2>


    <a href="listvol.php" class="btn btn-danger">
        <i class="fas fa-file-pdf"></i> Generar PDF
    </a>
    <!-- Filtros -->
    <div class="row g-3 mb-3">
        <div class="col-md">
            <input type="text" id="filtroNombre" class="form-control" placeholder="Nombre" oninput="filtrarTabla()">
        </div>
        <div class="col-md">
            <select id="filtroTipo" class="form-select" onchange="filtrarTabla('1')">
                <option value="">Tipo</option>
                <option value="Medico Veterinario">Médico Veterinario</option>
                <option value="Tecnico Veterinario">Técnico Veterinario</option>
                <option value="Estudiante Medicina Veterinaria">Estudiante Medicina Veterinaria</option>
                <option value="Estudiante Técnico Veterinario">Estudiante de Técnico Veterinario</option>
                <option value="Voluntario General">Voluntario General</option>
            </select>
        </div>

        <div class="col-md" hidden>
            <select id="filtroRegion" class="form-select" onchange="filtrarTabla('1')">
                <option value="">Región</option>
                <?php
                foreach ($regiones as $region) {
                    echo "<option value='" . $region['id'] . "'>" . $region['nombre'] . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md" hidden>
            <select id="filtroAutorizado" class="form-select" onchange="filtrarTabla('1')">
                <option value="">Estado</option>
                <option value="habilitado">Habilitado</option>
                <option value="deshabilitado">Deshabilitado</option>
            </select>
        </div>

    </div>

    <!-- Tabla de Voluntarios -->
    <table class="table table-striped" id="tablaVoluntarios">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Región</th>
                <th>Telefono</th>
                <th>Tipo</th>
                <th>Autorizado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <!-- Las filas de la tabla se llenarán dinámicamente con JavaScript -->
        </tbody>
    </table>

    <!-- Paginador -->
    <nav>
        <ul class="pagination" id="pagination">
            <!-- Los botones de paginación se generarán dinámicamente -->
        </ul>
    </nav>
</div>


<script>
    // Array de voluntarios (simulando la base de datos)
    function decodeHTML(html) {
        const txt = document.createElement("textarea");
        txt.innerHTML = html;
        return txt.value;
    }
    let voluntarios = <?php echo json_encode($vols); ?>;
    voluntarios.forEach(voluntario => {
        voluntario.nombre_region = decodeHTML(voluntario.nombre_region);
    });

    console.log(voluntarios)
    let currentPage = 1; // Página actual
    const rowsPerPage = 25; // Número de filas por página

    // Función para filtrar los voluntarios
    function filtrarTabla(pagina) {
        if (!pagina) {
            currentPage = 1
        }
        const filtroNombre = document.getElementById("filtroNombre").value.toLowerCase();
        const filtroTipo = document.getElementById("filtroTipo").value;
        const filtroAutorizado = document.getElementById("filtroAutorizado").value;
        // Filtrar voluntarios
        const filtroRegion = document.getElementById("filtroRegion").value;
        const voluntariosFiltrados = voluntarios.filter(voluntario => {
            return (
                (!filtroNombre ||
                    voluntario.nombre.toLowerCase().includes(filtroNombre) ||
                    voluntario.telefono.toLowerCase().includes(filtroNombre)) &&
                (!filtroRegion || voluntario.region === filtroRegion) &&
                (
                    !filtroTipo ||
                    (filtroTipo === "Voluntario General" ?
                        !["Médico Veterinario", "Técnico Veterinario", "Estudiante Técnico Veterinario",
                            "Estudiante Medicina Veterinaria", "Estudiante de Técnico Veterinario",
                            "Medico Veterinario", "Tecnico Veterinario"
                        ].includes(voluntario.profesion) :
                        voluntario.profesion === filtroTipo)
                ) &&
                (!filtroAutorizado || voluntario.estado === filtroAutorizado)
            );
        });


        mostrarTabla(voluntariosFiltrados);
        generarPaginador(voluntariosFiltrados);
    }

    // Función para mostrar la tabla de voluntarios
    function mostrarTabla(voluntariosFiltrados) {
        const tabla = document.getElementById("tablaVoluntarios").getElementsByTagName('tbody')[0];
        tabla.innerHTML = ''; // Limpiar tabla

        // Calcular los índices de las filas para la página actual
        const startIndex = (currentPage - 1) * rowsPerPage;
        const endIndex = startIndex + rowsPerPage;
        const voluntariosPagina = voluntariosFiltrados.slice(startIndex, endIndex);

        // Agregar filas a la tabla
        voluntariosPagina.forEach(voluntario => {
            const fila = document.createElement("tr");
            fila.innerHTML = `
            <td>${voluntario.nombre}</td>
            <td>${voluntario.nombre_region}-${voluntario.comuna}</td>
            <td>${voluntario.telefono}</td>
            <td>${voluntario.profesion}</td>
            <td>${voluntario.estado}</td>
                 <td><a href="verVoluntario.php?id=${voluntario.id}" class="btn btn-sm btn-success">Ver</a></td>
                    
                    <?php

                    ?>
        `;
            tabla.appendChild(fila);
        });
    }

    // Función para generar el paginador
    function generarPaginador(voluntariosFiltrados) {
        const pagination = document.getElementById("pagination");
        pagination.innerHTML = ''; // Limpiar paginador

        const totalPages = Math.ceil(voluntariosFiltrados.length / rowsPerPage);

        // Agregar botón "Anterior"
        if (currentPage > 1) {
            pagination.innerHTML += `<li class="page-item"><a class="page-link" href="#" onclick="cambiarPagina(${currentPage - 1})">Anterior</a></li>`;
        }

        // Agregar botones de página
        for (let i = 1; i <= totalPages; i++) {
            pagination.innerHTML += `
            <li class="page-item ${i === currentPage ? 'active' : ''}">
                <a class="page-link" href="#" onclick="cambiarPagina(${i})">${i}</a>
            </li>
        `;
        }

        // Agregar botón "Siguiente"
        if (currentPage < totalPages) {
            pagination.innerHTML += `<li class="page-item"><a class="page-link" href="#" onclick="cambiarPagina(${currentPage + 1})">Siguiente</a></li>`;
        }
    }

    // Función para cambiar de página
    function cambiarPagina(page) {
        currentPage = page;
        filtrarTabla(currentPage); // Vuelve a filtrar y mostrar la tabla
    }

    // Inicializar la tabla
    filtrarTabla(currentPage);
</script>

<?php
include_once('../plantillas/DecFin.inc.php');
?>