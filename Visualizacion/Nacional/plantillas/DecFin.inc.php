    <!-- Footer -->
    <footer class="bg-light text-center p-3 mt-5">
        <p>&copy; 2024 Comisión Nacional de Respuesta a Desastres, COLMEVET. Todos los derechos reservados.</p>
    </footer>

    <!-- jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <script>
        let Regiones = [] <?php // echo json_encode($listaDeRegiones); 
                            ?>; // Se obtiene desde PHP

        $(document).ready(function() {
            // Cargar las regiones desde PHP en una variable JS
            // Llenar el select de regiones al cargar la página
            CargarRegiones();

            // Enviar nueva región por AJAX y agregarla dinámicamente
            $('#enviarRegion').click(function(e) {
                var valor = document.getElementById('nombreRegion').value;

                alert('valor:'+valor)
                $.ajax({
                    url: '../src/funajax.php',
                    type: 'POST',
                    data: {
                        variable: 'AgReg',
                        accion: 'agregarRegion',
                        nombreRegion: valor
                    },
                    success: function(response) {
                        console.log(`Respuesta del servidor: ${response}`);
                        console.log(response);
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
                        } else {
                            alert(response.error)
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(`Error en la solicitud: ${error}`);
                        alert(`Error en la solicitud: ${error}`);
                    },
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
    switch (basename($_SERVER['PHP_SELF'])) {

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
                        url: '../src/funajax.php',
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
                                    url: '../src/funajax.php', // El archivo PHP que manejará el guardado
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
                                    url: '../src/funajax.php', // El archivo PHP que manejará el guardado
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

        case 'verVoluntario.php':
            ?>
            <script>
                function Mostrarbtn() {
                    var btncam = document.getElementById('btnConTip')
                    var tipoUsuario = document.getElementById('tipoUsuario').value
                    if (tipoUsuario === 'Coordinador') {
                        document.getElementById('SelectorConsejo').removeAttribute('hidden')
                    } else {
                        document.getElementById('SelectorConsejo').setAttribute('hidden', true)

                    }
                    btncam.removeAttribute('hidden')
                }

                function cambTyUs() {
                    var valor = document.getElementById('tipoUsuario').value;

                    var valor2 = 0; // Valor por defecto vacío
                    if (valor === 'Coordinador') {
                        valor2 = document.getElementById('SelectorConsejo2').value;
                    }

                    let data2 = {
                        variable: 'CamUs22',
                        tipo: 'voluntario',
                        id: <?php echo $_GET['id']; ?>,
                        camp: 'TyUs',
                        valCam: valor,
                        val2: valor2, // Valor obtenido correctamente
                    };

                    console.log(data2);

                    $.ajax({
                        url: '../src/funajax.php',
                        type: 'POST',
                        data: data2, // Usamos `data2` directamente
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
            </script>
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
                        url: '../src/funajax.php',
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
                            url: '../src/funajax.php', // El archivo PHP que manejará el guardado
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
                        url: '../src/funajax.php', // El archivo PHP que manejará el guardado
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
                        url: '../src/funajax.php', // El archivo PHP que manejará el guardado
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
                        url: '../src/funajax.php', // El archivo PHP que manejará el guardado
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
                        url: '../src/funajax.php', // El archivo PHP que manejará el guardado
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
    }
    ?>
    </body>

    </html>