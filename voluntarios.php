<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED);
header('Content-Type: text/html; charset=UTF-8');
mb_internal_encoding("UTF-8");

include_once('app/conex.inc.php');
include_once('app/func.inc.php');
include_once('plantillas/DecInc.inc.php');
$voluntarios = Usuario::obtenerVoluntarios();

?>
<div class="container mt-5">
    <h2>Gestión de Voluntarios</h2>


    <!-- Filtros -->
    <div class="row g-3 mb-3">
        <div class="col-md">
            <input type="text" id="filtroNombre" class="form-control" placeholder="Nombre" oninput="filtrarTabla()">
        </div>
        <div class="col-md">
            <select id="filtroTipo" class="form-select" onchange="filtrarTabla()">
                <option value="">Tipo</option>
                <option value="Medico Veterinario">Médico Veterinario</option>
                <option value="Tecnico Veterinario">Técnico Veterinario</option>
                <option value="Estudiante Medicina Veterinaria">Estudiante Medicina Veterinaria</option>
                <option value="Estudiante Técnico Veterinario">Estudiante de Técnico Veterinario</option>
                <option value="Voluntario General">Voluntario General</option>
            </select>
        </div>
        <?php if ($_SESSION['region'] === 'Nacional') { ?>
            <div class="col-md">
                <select id="filtroRegion" class="form-select" onchange="filtrarTabla()">
                    <option value="">Región</option>
                    <option value="Arica y Parinacota">Arica y Parinacota</option>
                    <option value="Tarapacá">Tarapacá</option>
                    <option value="Antofagasta">Antofagasta</option>
                    <option value="Atacama">Atacama</option>
                    <option value="Coquimbo">Coquimbo</option>
                    <option value="Valparaíso">Valparaíso</option>
                    <option value="Metropolitana">Metropolitana de Santiago</option>
                    <option value="O'Higgins">Libertador General Bernardo O'Higgins</option>
                    <option value="Maule">Maule</option>
                    <option value="Ñuble">Ñuble</option>
                    <option value="Biobío">Biobío</option>
                    <option value="La Araucanía">La Araucanía</option>
                    <option value="Los Ríos">Los Ríos</option>
                    <option value="Los Lagos">Los Lagos</option>
                    <option value="Aysén">Aysén del General Carlos Ibáñez del Campo</option>
                    <option value="Magallanes">Magallanes y de la Antártica Chilena</option>
                </select>
            </div>
            <div class="col-md">
                <select id="filtroAutorizado" class="form-select" onchange="filtrarTabla()">
                    <option value="">Estado</option>
                    <option value="habilitado">Habilitado</option>
                    <option value="deshabilitado">Deshabilitado</option>
                </select>
            </div>
        <?php } else { ?>
            <div class="col-md" hidden>
                <input id="filtroRegion" class="form-control">
            </div>
            <div class="col-md" hidden>
                <select id="filtroAutorizado" class="form-select" onchange="filtrarTabla()">
                    <option value="">Estado</option>
                    <option value="habilitado">Habilitado</option>
                    <option value="deshabilitado">Deshabilitado</option>
                </select>
            </div>
        <?php } ?>
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
    let voluntarios = <?php echo json_encode($voluntarios); ?>;
    <?php
    if ($_SESSION['region'] != 'Nacional') {
    ?>
        var reg = "<?php echo $_SESSION['region']; ?>";
        voluntarios = voluntarios.filter(voluntario => voluntario.region === reg && voluntario.estado === 'habilitado');
    <?php
    }
    ?>
    voluntarios.forEach(voluntario => {
        voluntario.region = decodeHTML(voluntario.region);
    });
    console.log(voluntarios)
    let currentPage = 1; // Página actual
    const rowsPerPage = 25; // Número de filas por página

    // Función para filtrar los voluntarios
    function filtrarTabla() {
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
            <td>${voluntario.region}-${voluntario.comuna}</td>
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
        filtrarTabla(); // Vuelve a filtrar y mostrar la tabla
    }

    // Inicializar la tabla
    filtrarTabla();
</script>

<?php
include_once('plantillas/DecFin.inc.php');
?>