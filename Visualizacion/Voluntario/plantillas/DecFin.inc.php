    <!-- Footer -->
    <footer class="bg-light text-center p-3 mt-5">
        <p>&copy; 2024 Comisión Nacional de Respuesta a Desastres, COLMEVET. Todos los derechos reservados.</p>
    </footer>

    <!-- jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcode/1.5.1/qrcode.js" referrerpolicy="no-referrer"></script>

    <?php
    switch (basename($_SERVER['PHP_SELF'])) {
        case 'asistencia.php':
    ?>
            <script>
                $(document).ready(function() {
                    // Verifica si qrcode.js está cargado solo una vez
                    if (typeof qrcode === "undefined") {
                        console.error("qrcode.js no se ha cargado correctamente.");
                        alert("qrcode.js no se ha cargado correctamente.");
                    } else {
                        console.log("qrcode.js cargado exitosamente.");
                    }
                });

                // Crea el elemento video
                const video = document.createElement("video");
                // Obtiene el canvas para el escaneo
                const canvasElement = document.getElementById("qr-canvas");
                const canvas = canvasElement.getContext("2d");
                // Variable para indicar si está escaneando
                let scanning = false;

                // Función para reiniciar QRCode (se configura el callback una sola vez)
                function reiniciarQRCode() {
                    if (typeof qrcode === "undefined") {
                        console.error("qrcode.js no se ha cargado correctamente.");
                        alert("La biblioteca qrcode.js no se ha cargado correctamente.");
                        return;
                    }
                    qrcode.callback = (respuesta) => {
                        if (!respuesta) {
                            alert("Código QR no detectado o inválido.");
                            console.warn("QR inválido o no detectado. Reintentando...");
                            setTimeout(scan, 500); // Reintenta después de 500ms
                            return;
                        }

                        alert("Código QR detectado: " + respuesta);
                        cerrarCamara();

                        Swal.fire({
                            icon: 'info',
                            title: 'Código QR Detectado',
                            text: 'Registrando asistencia, por favor espere...',
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        var id = '<?php echo $_SESSION['user_id']; ?>';
                        var accion = document.getElementById('accvol').value;

                        $.ajax({
                            url: '../src/funajax.php',
                            type: 'POST',
                            data: {
                                variable: 'MarAsisVol',
                                id: id,
                                valor: respuesta,
                                accion: accion
                            },
                            success: function(response) {
                                console.log("Respuesta del servidor:", response);
                                if (response.includes('Error')) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error en la Solicitud',
                                        text: response,
                                        showConfirmButton: true,
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Registro de Asistencia',
                                        text: response,
                                        showConfirmButton: true,
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error en la Solicitud',
                                    text: 'Error al registrar la asistencia. Intente nuevamente.',
                                    showConfirmButton: true,
                                });
                            }
                        });
                    };
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

                            video.onloadedmetadata = () => {
                                if (video.videoWidth === 0 || video.videoHeight === 0) {
                                    console.error("La cámara no se ha inicializado correctamente.");
                                    alert("Error al inicializar la cámara.");
                                    return;
                                }
                                tick();
                                reiniciarQRCode(); // Reinicia QRCode antes de comenzar el escaneo
                                scan();
                            };
                        })
                        .catch(function(err) {
                            console.error("Error al acceder a la cámara: ", err);
                            alert("No se pudo acceder a la cámara.");
                        });
                };

                // Actualiza el canvas continuamente mientras escanea
                function tick() {
                    if (!video.videoWidth || !video.videoHeight) {
                        requestAnimationFrame(tick);
                        return;
                    }

                    canvasElement.height = video.videoHeight;
                    canvasElement.width = video.videoWidth;
                    canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);

                    if (scanning) {
                        requestAnimationFrame(tick);
                    }
                }

                // Intenta leer el QR y sigue intentando si falla
                function scan() {
                    try {
                        // Intenta escanear el código QR
                        qrcode.decode();
                    } catch (e) {
                        console.error('Error al intentar escanear el código QR: ', e);
                        alert('Error al intentar escanear el código QR. Verifica la calidad del código y la cámara.');
                        setTimeout(scan, 2000); // Reintenta después de 2 segundos
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
            </script>


        <?php
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
    }
    ?>
    </body>

    </html>