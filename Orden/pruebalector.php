<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED);
session_start();

if (!isset($_SESSION['user_type'])) {
    header('Location: login.html'); // Redirige al login si no hay sesión activa
    exit();
}
include_once('app/func.inc.php');

include_once('plantillas/DecInc.inc.php');
?>
    <div class="row justify-content-center mt-5">
        <div class="col-sm-4 shadow p-3">
            <h5 class="text-center">Escanear Código QR</h5>
            <div class="row text-center">
                <canvas hidden id="qr-canvas" class="img-fluid"></canvas>
            </div>
            <div class="row mx-5 my-3">
                <button class="btn btn-success btn-sm rounded-3 mb-2" onclick="encenderCamara()">Encender Cámara</button>
                <button class="btn btn-danger btn-sm rounded-3" onclick="cerrarCamara()">Detener Cámara</button>
            </div>
        </div>
    </div>
    <script>
        // Crea el elemento video
        const video = document.createElement("video");
        // Obtiene el canvas para el escaneo
        const canvasElement = document.getElementById("qr-canvas");
        const canvas = canvasElement.getContext("2d");
        // Variable para indicar si está escaneando
        let scanning = false;

        // Función para encender la cámara
        const encenderCamara = () => {
            navigator.mediaDevices
                .getUserMedia({
                    video: {
                        facingMode: "environment"
                    }
                })
                .then(function (stream) {
                    scanning = true;
                    canvasElement.hidden = false;
                    video.setAttribute("playsinline", true); // Requerido para Safari en iOS
                    video.srcObject = stream;
                    video.play();
                    tick();
                    scan();
                })
                .catch(function (err) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo acceder a la cámara.',
                    });
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
        };

        // Callback cuando se lee correctamente el código QR
        qrcode.callback = (respuesta) => {
            if (respuesta) {
                Swal.fire({
                    icon: 'success',
                    title: 'Código QR Detectado',
                    text: respuesta,
                });
                cerrarCamara();
            }
        };

        // Evento que activa la cámara al cargar la página
        window.addEventListener('load', () => {
            encenderCamara();
        });
    </script>
</body>

</html>
