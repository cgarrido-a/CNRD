<?php

include_once('plantillas/DecInc.inc.php');
$clinicas = Clinicas::get_clinicas();

?>

<div class="container mt-5">
    <h2>Gestión de Clínicas</h2>
    <!-- Botón para abrir el modal -->
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#nuevaClinicaModal">Crear Nueva Clínica</button>

    <!-- Modal para Crear Nueva Clínica -->
    <div class="modal fade" id="nuevaClinicaModal" tabindex="-1" aria-labelledby="nuevaClinicaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="guardarClinica.php" method="post" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="nuevaClinicaModalLabel">Crear Nueva Clínica</h5>
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
                            <label for="nombreRepresentante">Representante</label>
                            <input type="text" class="form-control" id="nombreRepresentante" name="nombreRepresentante" required>
                        </div>
                        <div class="form-group">
                            <label for="direccion">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" required>
                        </div>
                        <div class="form-group">
                            <label for="region">Región</label>
                            <select class="form-control" id="region" name="region" required>
                                <option value="">Selecciona una región</option>
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
                        <div class="form-group">
                            <label for="comuna">Comuna</label>
                            <input type="text" class="form-control" id="comuna" name="comuna" required>
                        </div>
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" required>
                        </div>
                        <div class="form-group">
                            <label for="correo">Correo</label>
                            <input type="email" class="form-control" id="correo" name="correo" required>
                        </div>
                        <div class="form-group">
                            <label for="acuerdoClinica">Acuerdo Clínica firmado</label>
                            <input type="file" class="form-control" id="acuerdoClinica" name="acuerdoClinica" required>
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
        if ($_SESSION['region'] === "Nacional") {
        ?>
            <div class="col-md">
                <select id="filtroRegion" class="form-control" onchange="filtrarTabla()">
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
        <?php
        }else{
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
                <th>Teléfono</th>
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
    let clinicas = JSON.parse('<?php echo $clinicas; ?>');

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
                <td>${clinica.direccion}</td>
                <td>${clinica.region} -     ${clinica.comuna}</td>
                <td>${clinica.telefono}</td>
                <td>${clinica.correo}</td>
                <td>
                    <a href="editarClinica.php?id=${clinica.id}" class="btn btn-sm btn-warning">Editar</a>
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
    $(document).ready(function() {
        // Función para enviar los datos del formulario de nueva clínica
        $('#nuevaClinicaModal form').submit(function(e) {
            e.preventDefault(); // Evita el comportamiento predeterminado del formulario

            // Obtener los valores del formulario
            var nombreClinica = $('#nombreClinica').val();
            var nombreRepresentante = $('#nombreRepresentante').val();
            var direccion = $('#direccion').val();
            var region = $('#region').val();
            var comuna = $('#comuna').val();
            var telefono = $('#telefono').val();
            var correo = $('#correo').val();

            // Asignar la clave predeterminada


            // Enviar la solicitud AJAX
            $.ajax({
                url: 'guardarClinica.php', // El archivo PHP que manejará el guardado
                type: 'POST',
                data: {
                    nombreClinica: nombreClinica,
                    nombreRepresentante: nombreRepresentante,
                    direccion: direccion,
                    region: region,
                    comuna: comuna,
                    telefono: telefono,
                    correo: correo,

                },
                success: function(response) {
                    // Si la solicitud es exitosa, agregar la clínica a la lista y recargar la tabla
                    if (response.trim() === 'success') {
                        // Agregar la nueva clínica a la tabla (o actualizarla desde el servidor)
                        agregarClinicaATabla({
                            nombre: nombreClinica,
                            direccion: direccion,
                            region: region,
                            comuna: comuna,
                            telefono: telefono,
                            correo: correo,

                        });

                        // Cerrar el modal
                        $('#nuevaClinicaModal').modal('hide');

                        // Limpiar el formulario
                        $('#nuevaClinicaModal form')[0].reset();
                    } else {
                        alert('Hubo un error al guardar la clínica.');
                    }
                },
                error: function() {
                    alert('Hubo un problema al enviar los datos.');
                }
            });
        });
    });
</script>
<?php
include_once('plantillas/DecFin.inc.php');
?>