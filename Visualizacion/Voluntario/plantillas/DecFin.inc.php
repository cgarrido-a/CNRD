    <!-- Footer -->
    <footer class="bg-light text-center p-3 mt-5">
        <p>&copy; 2024 Comisión Nacional de Respuesta a Desastres, COLMEVET. Todos los derechos reservados.</p>
    </footer>

    <!-- jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

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

            <script>
              document.getElementById('btnCambiarClave').addEventListener('click', function() {
                    $('#modalClave').modal('show');
                });

                document.getElementById('btnCambiarClaveModal').addEventListener('click', function() {
                    const nuevaClave = document.getElementById('nuevaClave').value;
                    const id = "<?php echo $_SESSION['UserLog']->obtener_id(); ?>";
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
        <?php


            break;

        case 'verVoluntario.php':
        ?>
            <script>
                function Mostrarbtn() {
                    var btncam = document.getElementById('btnConTip')
                    btncam.removeAttribute('hidden')
                }

                function cambTyUs() {
                    var valor = document.getElementById('tipoUsuario').value
                    $.ajax({
                        url: '../src/funajax.php',
                        type: 'POST',
                        data: {
                            variable: 'CamUs',
                            tipo: 'voluntario',
                            id: <?php echo $_GET['id'] ?>,
                            camp: 'TyUs',
                            valCam: valor,
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