    <!-- Footer -->
    <footer class="bg-light text-center p-3 mt-5">
        <p>&copy; 2024 Comisión Nacional de Respuesta a Desastres, Colegio Medico Veterinario de Chile Ag. COLMEVET. Todos los derechos reservados.</p>
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
                            if (response === 'correcto') {
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
    ?>
    </body>

    </html>