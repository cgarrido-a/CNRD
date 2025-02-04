    <!-- Footer -->
    <footer class="bg-light text-center p-3 mt-5">
        <p>&copy; 2024 Comisión Nacional de Respuesta a Desastres, COLMEVET. Todos los derechos reservados.</p>
    </footer>

    <!-- jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <?php
    switch (basename($_SERVER['PHP_SELF'])) {
        case 'asistencia.php':
        ?>
            <script>
                // Crea el elemento video
                const video = document.createElement("video");
                // Obtiene el canvas para el escaneo
                const canvasElement = document.getElementById("qr-canvas");
                const canvas = canvasElement.getContext("2d");
                // Variable para indicar si está escaneando
                let scanning = false;

                // Función para reiniciar QRCode
                function reiniciarQRCode() {
                    qrcode.decode(); // Fuerza a decodificar
                    qrcode.callback = function(response) {}; // Reinicia el callback
                }

                // Función para encender la cámara
                const encenderCamara = (variable) => {
                    // Ocultar los botones de ingreso y salida
                    document.getElementById("btnINC").style.display = 'none';
                    document.getElementById("btnCerr").style.display = 'none';

                    var elemt = document.getElementById('accvol');
                    switch (variable) {
                        case 'qwert':
                            elemt.value = 'iniciar';
                            break;
                        case 'asdfg':
                            elemt.value = 'cerrar';
                            break;
                    }
                    navigator.mediaDevices
                        .getUserMedia({
                            video: {
                                facingMode: "environment"
                            }
                        })
                        .then(function(stream) {
                            scanning = true;
                            canvasElement.hidden = false;
                            video.setAttribute("playsinline", true); // Requerido para Safari en iOS
                            video.srcObject = stream;
                            video.play();
                            tick();
                            reiniciarQRCode(); // Reinicia QRCode antes de comenzar el escaneo
                            scan();
                        })
                        .catch(function(err) {
                            console.log(err)
                        });
                };

                // Actualiza el canvas continuamente mientras escanea
                function tick() {
                    canvasElement.height = video.videoHeight;
                    canvasElement.width = video.videoWidth;
                    canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);

                    scanning && requestAnimationFrame(tick);
                }

                // Intenta leer el QR y sigue intentando si falla
                function scan() {
                    try {
                        qrcode.decode();
                    } catch (e) {
                        setTimeout(scan, 300);
                    }
                }

                // Función para detener la cámara
                const cerrarCamara = () => {
                    if (video.srcObject) {
                        video.srcObject.getTracks().forEach((track) => {
                            track.stop();
                        });
                    }
                    canvasElement.hidden = true;
                    scanning = false;

                    // Volver a mostrar los botones cuando se detiene la cámara
                    document.getElementById("btnINC").style.display = 'block';
                    document.getElementById("btnCerr").style.display = 'block';
                };

                // Callback cuando se lee correctamente el código QR
                qrcode.callback = (respuesta) => {
                    cerrarCamara();
                    if (respuesta) {
                        Swal.fire({
                            icon: 'info', // Cambio a 'info' para un ícono más neutral
                            title: 'Código QR Detectado',
                            text: 'Registrando asistencia, por favor espere...',
                            showConfirmButton: false, // Ocultar el botón de confirmación para evitar interacción
                            didOpen: () => {
                                Swal.showLoading(); // Muestra un ícono de carga mientras se procesa
                            }
                        });

                        var id = '<?php echo $_SESSION['user_id']; ?>';
                        var accion = document.getElementById('accvol').value;

                        $.ajax({
                            url: 'app/pasar.inc.php',
                            type: 'POST',
                            data: {
                                variable: 'MarAsisVol',
                                id: id,
                                valor: respuesta,
                                accion: accion
                            },
                            success: function(response) {
                                console.log("Respuesta del servidor:", response);

                                // Si la respuesta es un error, mostrar la alerta de error en lugar de éxito
                                if (response.includes('Error')) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error en la Solicitud',
                                        text: response, // Muestra el mensaje de error recibido del servidor
                                        showConfirmButton: true,
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Registro de Asistencia',
                                        text: response, // Mensaje de éxito si no hay errores
                                        showConfirmButton: true,
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                var erl = xhr.responseText;
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error en la Solicitud',
                                    text: 'Error al registrar la asistencia. Intente nuevamente.',
                                    showConfirmButton: true,
                                });
                            }
                        });
                    }

                };
            </script>

        <?php
            break;
        case 'miperfil.php':
        ?>
            <script>
                // Función para realizar el cambio
                function realizarCambio(valor) {
                    const nuevoValor = document.getElementById("inp2").value.trim();

                    if (!nuevoValor) {
                        alert(`Por favor, ingrese el nuevo ${valor}.`);
                        return;
                    }

                    console.log(`Nuevo valor a enviar: ${nuevoValor}`); // Depuración
                    const mensajeError = document.getElementById("mensajeError");

                    // Limpiar cualquier mensaje de error previo
                    if (mensajeError) {
                        mensajeError.textContent = "";
                        mensajeError.style.display = "none";
                    }

                    $.ajax({
                        url: 'app/pasar.inc.php',
                        type: 'POST',
                        data: {
                            variable: 'CamUs',
                            tipo: document.body.dataset.userType,
                            id: document.body.dataset.userId,
                            camp: valor,
                            valCam: nuevoValor,
                        },
                        success: function(response) {
                            console.log(`Respuesta del servidor: ${response}`);
                            if (response === '1correcto') {
                                location.reload();
                            } else {
                                alert(`Error: ${response}`);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(`Error en la solicitud: ${error}`);
                        },
                    });
                }


                function mostrarModal(fieldType) {
                    $('#modalCcambios').modal('show');

                    // Cambiar título del modal
                    const modalTitle = document.getElementById("modalCambioLabel");
                    modalTitle.textContent = `Cambiar ${fieldType}`;

                    // Limpiar contenido del modal
                    const modalBody = document.getElementById("modalCambioBody");
                    modalBody.innerHTML = "";

                    // Crear elementos del modal
                    const currentValue = document.createElement("p");
                    currentValue.id = "inp1";
                    currentValue.textContent = `El ${fieldType} que deseas cambiar es: ${document.getElementById(fieldType).value}`;

                    const newInput = document.createElement("input");
                    newInput.type = "text";
                    newInput.id = "inp2";
                    newInput.placeholder = `Ingrese su nuevo ${fieldType}`;
                    newInput.className = "form-control";

                    // Si fieldType es "Correo", agregar advertencia
                    if (fieldType === "Correo") {
                        const warningMessage = document.createElement("p");
                        warningMessage.textContent = "Advertencia: Cambiar el Correo afectará el Usuario de acceso.";
                        warningMessage.style.color = "red"; // Color rojo para la advertencia
                        modalBody.appendChild(warningMessage);
                    }

                    modalBody.append(currentValue, newInput);

                    // Configurar botón de cambio
                    const btn = document.getElementById("btnCambiarValorModal");
                    btn.textContent = `Cambiar ${fieldType}`;
                    btn.onclick = () => realizarCambio(fieldType); // Asigna la función directamente
                }


                document.addEventListener("DOMContentLoaded", () => {
                    document.body.dataset.userType = "<?php echo $_SESSION['user_type']; ?>";
                    document.body.dataset.userId = "<?php echo $_SESSION['user_id']; ?>";
                });
            </script>
            <?php


            switch ($_SESSION['user_type']) {
                case 'voluntario':
            ?>
                    <script>
                        document.getElementById('btnCambiarClave').addEventListener('click', function() {
                            $('#modalClave').modal('show');
                        });

                        document.getElementById('btnCambiarClaveModal').addEventListener('click', function() {
                            const nuevaClave = document.getElementById('nuevaClave').value;
                            const id = document.getElementById('id_usuario').value;
                            const confirmarClave = document.getElementById('confirmarClave').value;
                            if (nuevaClave === confirmarClave) {
                                $.ajax({
                                    url: 'app/pasar.inc.php', // El archivo PHP que manejará el guardado
                                    type: 'POST',
                                    data: {
                                        variable: 'CambClavVol',
                                        id: id,
                                        nuevaClave: nuevaClave
                                    },
                                    success: function(response) {
                                        console.log(response)
                                        if (response === 'correcto') {
                                            location.reload()
                                        } else {
                                            alert('Error al cambiar clave' + response);
                                        }
                                    },
                                    error: function(response) {
                                        console.log(response)
                                        alert('Hubo un problema al enviar los datos.' + response);
                                    }
                                });
                            } else {
                                alert('Las claves no coinciden');
                            }

                        });
                    </script>
                <?php
                    break;
                case 'Coordinacion':
                ?>
                    <script>
                        document.getElementById('btnCambiarClave').addEventListener('click', function() {
                            $('#modalClave').modal('show');
                        }); 
                        document.getElementById('btnCambiarClaveModal').addEventListener('click', function() {
                            const nuevaClave = document.getElementById('nuevaClave').value;
                            console.log(nuevaClave)
                            const id = document.getElementById('id_usuario').value;
                            console.log(id)
                            const confirmarClave = document.getElementById('confirmarClave').value;
                            console.log(confirmarClave)

                            if (nuevaClave === confirmarClave) {
                                $.ajax({
                                    url: 'app/pasar.inc.php', // El archivo PHP que manejará el guardado
                                    type: 'POST',
                                    data: {
                                        variable: 'CambClavUs',
                                        id: id,
                                        nuevaClave: nuevaClave
                                    },
                                    success: function(response) {
                                        console.log(response)
                                        if (response === 'correcto') {
                                            location.reload()
                                        } else {
                                            alert('Error al cambiar clave');
                                        }
                                    },
                                    error: function(response) {
                                        console.log(response)
                                        alert('Hubo un problema al enviar los datos.');
                                    }
                                });
                            } else {
                                alert('Las claves no coinciden');
                            }
                        });
                    </script>
            <?php
                    break;
            }
            break;
        case 'editarUsuario.php':
            ?>
            <script>
                document.getElementById('btnCambiarFoto').addEventListener('click', function() {
                    $('#modalFoto').modal('show');
                });

                document.getElementById('btnCambiarClave').addEventListener('click', function() {
                    $('#modalClave').modal('show');
                });

                // Cambiar la clave
                document.getElementById('btnCambiarClaveModal').addEventListener('click', function() {
                    const nuevaClave = document.getElementById('nuevaClave').value;
                    const id = document.getElementById('id_usuario').value;
                    const confirmarClave = document.getElementById('confirmarClave').value;

                    if (nuevaClave === confirmarClave) {
                        $.ajax({
                            url: 'app/pasar.inc.php', // El archivo PHP que manejará el guardado
                            type: 'POST',
                            data: {
                                variable: 'CambClavUs',
                                id: id,
                                nuevaClave: nuevaClave
                            },
                            success: function(response) {
                                console.log(response)
                                if (response === 'correcto') {
                                    location.reload()
                                } else {
                                    alert('Error al cambiar clave');
                                }
                            },
                            error: function(response) {
                                console.log(response)
                                alert('Hubo un problema al enviar los datos.');
                            }
                        });
                    } else {
                        alert('Las claves no coinciden');
                    }
                });


                // Abrir el modal para crear credencial
                document.getElementById('btnCrearCredencial').addEventListener('click', function() {
                    $('#modalCredencial').modal('show');
                });

                // Crear la credencial

                function ActCredencial() {
                    var nombrecred = document.getElementById("nombrecred").value;
                    var institucioncred = document.getElementById("institucioncred").value;
                    var cargocred = document.getElementById("cargocred").value;

                    $.ajax({
                        url: 'app/pasar.inc.php', // El archivo PHP que manejará el guardado
                        type: 'POST',
                        data: {
                            variable: 'ActCred',
                            id: "<?php echo 'c-' . $_GET['id'] ?>",
                            nombrecred: nombrecred,
                            institucioncred: institucioncred,
                            cargocred: cargocred
                        },
                        success: function(response) {
                            console.log(response)
                            if (response === 'correcto') {
                                location.reload()
                            } else {
                                alert('Error al crear credencial');
                            }
                        },
                        error: function(response) {
                            console.log(response)
                            alert('Hubo un problema al enviar los datos.');
                        }
                    });
                }

                function crearCredencial() {
                    var nombrecred = document.getElementById("nombrecred").value;
                    var institucioncred = document.getElementById("institucioncred").value;
                    var cargocred = document.getElementById("cargocred").value;

                    $.ajax({
                        url: 'app/pasar.inc.php', // El archivo PHP que manejará el guardado
                        type: 'POST',
                        data: {
                            variable: 'CrearCred',
                            id: "<?php echo 'c-' . $_GET['id'] ?>",
                            nombrecred: nombrecred,
                            institucioncred: institucioncred,
                            cargocred: cargocred
                        },
                        success: function(response) {
                            console.log(response)
                            if (response === 'correcto') {
                                location.reload()
                            } else {
                                alert('Error al crear credencial');
                            }
                        },
                        error: function(response) {
                            console.log(response)
                            alert('Hubo un problema al enviar los datos.');
                        }
                    });
                }


                function cambiarestado(accion) {
                    $.ajax({
                        url: 'app/pasar.inc.php', // El archivo PHP que manejará el guardado
                        type: 'POST',
                        data: {
                            variable: 'CamEstUs',
                            nuevaClave: accion,
                            id: '<?php echo $_GET['id'] ?>'
                        },
                        success: function(response) {
                            console.log(response)
                            if (response === '1correcto') {
                                location.reload()
                            } else {
                                alert('Error al actualizar el estado del voluntario');
                            }
                        },
                        error: function() {
                            alert('Hubo un problema al enviar los datos.');
                        }
                    });
                }
            </script>

        <?php
            break;
        case 'verVoluntario.php':
        ?>
        <script>
                // Función para realizar el cambio
                function realizarCambio(valor) {
                    const nuevoValor = document.getElementById("inp2").value.trim();

                    if (!nuevoValor) {
                        alert(`Por favor, ingrese el nuevo ${valor}.`);
                        return;
                    }

                    console.log(`Nuevo valor a enviar: ${nuevoValor}`); // Depuración
                    const mensajeError = document.getElementById("mensajeError");

                    // Limpiar cualquier mensaje de error previo
                    if (mensajeError) {
                        mensajeError.textContent = "";
                        mensajeError.style.display = "none";
                    }

                    $.ajax({
                        url: 'app/pasar.inc.php',
                        type: 'POST',
                        data: {
                            variable: 'CamUs',
                            tipo: 'voluntario',
                            id: <?php echo $_GET['id'] ?>,
                            camp: valor,
                            valCam: nuevoValor,
                        },
                        success: function(response) {
                            console.log(`Respuesta del servidor: ${response}`);
                            if (response === '1correcto') {
                                location.reload();
                            } else {
                                alert(`Error: ${response}`);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(`Error en la solicitud: ${error}`);
                        },
                    });
                }


                function mostrarModal(fieldType) {
                    $('#modalCcambios').modal('show');

                    // Cambiar título del modal
                    const modalTitle = document.getElementById("modalCambioLabel");
                    modalTitle.textContent = `Cambiar ${fieldType}`;

                    // Limpiar contenido del modal
                    const modalBody = document.getElementById("modalCambioBody");
                    modalBody.innerHTML = "";

                    // Crear elementos del modal
                    const currentValue = document.createElement("p");
                    currentValue.id = "inp1";
                    currentValue.textContent = `El ${fieldType} que deseas cambiar es: ${document.getElementById(fieldType).value}`;

                    const newInput = document.createElement("input");
                    newInput.type = "text";
                    newInput.id = "inp2";
                    newInput.placeholder = `Ingrese su nuevo ${fieldType}`;
                    newInput.className = "form-control";

                    // Si fieldType es "Correo", agregar advertencia
                    if (fieldType === "Correo") {
                        const warningMessage = document.createElement("p");
                        warningMessage.textContent = "Advertencia: Cambiar el Correo afectará el Usuario de acceso.";
                        warningMessage.style.color = "red"; // Color rojo para la advertencia
                        modalBody.appendChild(warningMessage);
                    }

                    modalBody.append(currentValue, newInput);

                    // Configurar botón de cambio
                    const btn = document.getElementById("btnCambiarValorModal");
                    btn.textContent = `Cambiar ${fieldType}`;
                    btn.onclick = () => realizarCambio(fieldType); // Asigna la función directamente
                }


                document.addEventListener("DOMContentLoaded", () => {
                    document.body.dataset.userType = "<?php echo $_SESSION['user_type']; ?>";
                    document.body.dataset.userId = "<?php echo $_SESSION['user_id']; ?>";
                });
            </script>
            <script>
                document.getElementById('btnCambiarClave').addEventListener('click', function() {
                    $('#modalClave').modal('show');
                });

                document.getElementById('btnCambiarClaveModal').addEventListener('click', function() {
                    const nuevaClave = document.getElementById('nuevaClave').value;
                    const id = "<?php echo $_GET['id'] ?>";
                    const confirmarClave = document.getElementById('confirmarClave').value;

                    if (nuevaClave === confirmarClave) {
                        $.ajax({
                            url: 'app/pasar.inc.php', // El archivo PHP que manejará el guardado
                            type: 'POST',
                            data: {
                                variable: 'CambClavVol',
                                id: id,
                                nuevaClave: nuevaClave
                            },
                            success: function(response) {
                                console.log(response)
                                if (response === 'correcto') {
                                    location.reload()
                                } else {
                                    alert('Error al cambiar clave');
                                }
                            },
                            error: function(response) {
                                console.log(response)
                                alert('Hubo un problema al enviar los datos.');
                            }
                        });
                    } else {
                        alert('Las claves no coinciden');
                    }

                });
            </script>
            <script>
                function ActCredencial() {
                    var nombrecred = document.getElementById("nombrecred").value;
                    var institucioncred = document.getElementById("institucioncred").value;
                    var cargocred = document.getElementById("cargocred").value;

                    $.ajax({
                        url: 'app/pasar.inc.php', // El archivo PHP que manejará el guardado
                        type: 'POST',
                        data: {
                            variable: 'ActCred',
                            id: '<?php echo $_GET['id'] ?>',
                            nombrecred: nombrecred,
                            institucioncred: institucioncred,
                            cargocred: cargocred
                        },
                        success: function(response) {
                            console.log(response)
                            if (response === 'correcto') {
                                location.reload()
                            } else {
                                alert('Error al crear credencial');
                            }
                        },
                        error: function(response) {
                            console.log(response)
                            alert('Hubo un problema al enviar los datos.');
                        }
                    });
                }

                function crearCredencial() {
                    var nombrecred = document.getElementById("nombrecred").value;
                    var institucioncred = document.getElementById("institucioncred").value;
                    var cargocred = document.getElementById("cargocred").value;

                    $.ajax({
                        url: 'app/pasar.inc.php', // El archivo PHP que manejará el guardado
                        type: 'POST',
                        data: {
                            variable: 'CrearCred',
                            id: '<?php echo $_GET['id'] ?>',
                            nombrecred: nombrecred,
                            institucioncred: institucioncred,
                            cargocred: cargocred
                        },
                        success: function(response) {
                            console.log(response)
                            if (response === 'correcto') {
                                location.reload()
                            } else {
                                alert('Error al crear credencial');
                            }
                        },
                        error: function(response) {
                            console.log(response)
                            alert('Hubo un problema al enviar los datos.');
                        }
                    });
                }

                function cambiarestado(accion) {
                    $.ajax({
                        url: 'app/pasar.inc.php', // El archivo PHP que manejará el guardado
                        type: 'POST',
                        data: {
                            variable: 'CamEstVol',
                            valor: accion,
                            id: '<?php echo $_GET['id'] ?>'
                        },
                        success: function(response) {
                            console.log(response)
                            if (response === '1correcto') {
                                location.reload()
                            } else {
                                alert('Error al actualizar el estado del voluntario');
                            }
                        },
                        error: function() {
                            alert('Hubo un problema al enviar los datos.');
                        }
                    });
                }

                function cambiarprof() {
                    var valor2 = document.getElementById('selectProfesion').value;
                    var accion = valor2 === 'Otra' ?
                        document.getElementById('otraProfesion').value :
                        valor2;
                    console.log(accion)

                    $.ajax({
                        url: 'app/pasar.inc.php', // El archivo PHP que manejará el guardado
                        type: 'POST',
                        data: {
                            variable: 'CambProf',
                            valor: accion,
                            id: '<?php echo $_GET['id'] ?>'
                        },
                        success: function(response) {
                            console.log(response)
                            if (response === '1correcto') {
                                location.reload()
                            } else {
                                alert('Error al actualizar el estado del voluntario');
                            }
                        },
                        error: function() {
                            alert('Hubo un problema al enviar los datos.');
                        }
                    });
                }

                function toggleProfesionInput() {
                    var profesionSelect = document.getElementById('selectProfesion');
                    var otraProfesionDiv = document.getElementById('otraprof');
                    if (profesionSelect.value === 'Otra') {
                        otraProfesionDiv.style.display = 'block';
                    } else {
                        otraProfesionDiv.style.display = 'none';
                    }
                }
                $(document).ready(function() {
                    // Detectar cambios en el select dentro del modal
                    $('#modalProfesion').on('change', '#selectProfesion', function() {
                        var profesion = $(this).val(); // Obtener el valor seleccionado
                        var otraprof = $('#otraprof'); // Contenedor donde se añade el input

                        if (profesion === 'Otra') {
                            // Agregar input para especificar otra profesión
                            otraprof.html(`
                            <input type="text" class="form-control mt-2" id="otraProfesion" 
                            name="otraProfesion" placeholder="Especifica tu profesión u oficio">
                        `);
                        } else {
                            // Limpiar el contenedor si no es "Otra"
                            otraprof.empty();
                        }
                        console.log('Profesión seleccionada:', profesion);
                    });
                });
            </script>

        <?php
            break;
        case 'usuario.php':
        ?>
            <script>
                // Array de usuarios (simulando la base de datos)
                function decodeHTML(html) {
                    const txt = document.createElement("textarea");
                    txt.innerHTML = html;
                    return txt.value;
                }
                let usuarios = <?php echo json_encode($usuarios); ?>;
                usuarios.forEach(usuario => {
                    usuario.region = decodeHTML(usuario.region);
                });
                console.log(usuarios)
                let currentPage = 1; // Página actual
                const rowsPerPage = 5; // Número de filas por página

                // Función para filtrar los usuarios
                function filtrarTabla() {
                    const filtroNombre = document.getElementById("filtroNombre").value.toLowerCase();
                    const filtroConsejo = document.getElementById("filtroConsejo").value;
                    const filtroRegion = document.getElementById("filtroRegion").value;
                    const filtroEstado = document.getElementById("filtroEstado").value;

                    // Filtrar usuarios
                    const usuariosFiltrados = usuarios.filter(usuario => {
                        return (
                            (!filtroNombre || usuario.nombre.toLowerCase().includes(filtroNombre)) &&
                            (!filtroConsejo || usuario.consejo === filtroConsejo) &&
                            (!filtroRegion || usuario.region === filtroRegion) &&
                            (!filtroEstado || usuario.estado === filtroEstado)
                        );
                    });

                    mostrarTabla(usuariosFiltrados);
                    generarPaginador(usuariosFiltrados);
                }

                // Función para mostrar la tabla de usuarios
                function mostrarTabla(usuariosFiltrados) {
                    const tabla = document.getElementById("tablaUsuarios").getElementsByTagName('tbody')[0];
                    tabla.innerHTML = ''; // Limpiar tabla

                    // Calcular los índices de las filas para la página actual
                    const startIndex = (currentPage - 1) * rowsPerPage;
                    const endIndex = startIndex + rowsPerPage;
                    const usuariosPagina = usuariosFiltrados.slice(startIndex, endIndex);

                    // Agregar filas a la tabla
                    usuariosPagina.forEach(usuario => {
                        const fila = document.createElement("tr");
                        fila.innerHTML = `
                            <td>${usuario.nombre}</td>
                            <td>${usuario.correo}</td>
                            <td>${usuario.region}</td>
                            <td>${usuario.consejo}</td>
                            <td>${usuario.estado}</td>
                            <td><a href="editarUsuario.php?id=${usuario.id}" class="btn btn-sm btn-warning">Editar</a></td>
                        `;
                        tabla.appendChild(fila);
                    });
                }

                // Función para generar el paginador
                function generarPaginador(usuariosFiltrados) {
                    const pagination = document.getElementById("pagination");
                    pagination.innerHTML = ''; // Limpiar paginador

                    const totalPages = Math.ceil(usuariosFiltrados.length / rowsPerPage);

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
                // Función para manejar la creación de un nuevo usuario
                $(document).ready(function() {
                    $("#btnCrearUsuario").click(function() {
                        // Obtener los valores del formulario
                        const nuevoUsuario = {
                            nombre: document.getElementById('nombreUsuario').value,
                            correo: document.getElementById('correoUsuario').value,
                            clave: document.getElementById('claveUsuario').value,
                            region: document.getElementById('regionUsuario').value,
                            consejo: document.getElementById('consejoUsuario').value
                        };

                        // Validar que todos los campos necesarios estén completos
                        if (!nuevoUsuario.nombre || !nuevoUsuario.correo || !nuevoUsuario.clave || !nuevoUsuario.region || !nuevoUsuario.consejo) {
                            alert("Por favor complete todos los campos obligatorios.");
                            return;
                        }

                        // Enviar los datos al servidor usando AJAX
                        $.ajax({
                            url: 'app/pasar.inc.php', // Archivo PHP donde se manejará el guardado
                            type: 'POST',
                            data: {
                                nombre: document.getElementById('nombreUsuario').value,
                                correo: document.getElementById('correoUsuario').value,
                                clave: document.getElementById('claveUsuario').value,
                                region: document.getElementById('regionUsuario').value,
                                consejo: document.getElementById('consejoUsuario').value,
                                variable: 'nuevUser'
                            },
                            success: function(response) {
                                alert('Usuario creado exitosamente');
                                console.log(response);
                            },
                            error: function(xhr, status, error) {
                                // Manejar errores de la solicitud AJAX
                                alert('Hubo un error en la solicitud: ' + error);
                            }
                        });
                    });
                })
            </script>
    <?php
            break;
    }
    ?>
    </body>

    </html>