<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED);


include_once('../plantillas/LLamstan.inc.php');
session_start();

if (!isset($_SESSION['UserLog'])) {
    header('Location: login.html'); // Redirige al login si no hay sesión activa
    exit();
}


$ruta ='';

include_once('../plantillas/DecInc.inc.php');

foreach (glob("../modales/*.php") as $archivo) {
    include_once $archivo;

}
?>

<div class="container px-3 py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow border-0">
                <div class="card-header text-center bg-primary text-white py-3">
                    <h6 class="mb-0">Control de Asistencia</h6>
                </div>
                <div class="card-body p-4">
                    <h6 class="text-center text-muted mb-3">Escanea el código QR para registrar tu asistencia</h6>
                    <div class="text-center mb-3">
                        <canvas hidden id="qr-canvas" class="img-fluid w-100"></canvas>
                        <input hidden type="text" readonly id="accvol">
                    </div>
                    <div class="d-grid gap-2">
                        <!-- Botón Ingreso -->
                        <button id="btnINC" class="btn btn-success w-100 py-3" onclick="encenderCamara('qwert')">
                            <i class="bi bi-box-arrow-in-right me-2"></i> Ingreso
                        </button>
                        <!-- Botón Salida -->
                        <button id="btnCerr" class="btn btn-danger w-100 py-3" onclick="encenderCamara('asdfg')">
                            <i class="bi bi-box-arrow-left me-2"></i> Salida
                        </button>
                        <!-- Botón Detener Cámara -->
                        <button class="btn btn-warning w-100 py-3" onclick="cerrarCamara()">
                            <i class="bi bi-camera-video-off me-2"></i> Detener Cámara
                        </button>
                    </div>
                </div>
               
            </div>
        </div>
    </div>
</div>
<?php
include_once('../plantillas/DecFin.inc.php');
?>