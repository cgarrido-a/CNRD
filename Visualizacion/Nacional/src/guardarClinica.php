<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
include_once '../plantillas/LLamstan.inc.php';
error_reporting(E_ALL & ~E_DEPRECATED);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_type'])) {
    header('Location: login.html');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger datos desde la solicitud POST
    $nombreClinica = $_POST['nombreClinica'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $region = $_POST['id_region'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $clave = $_POST['clave'] ?? '';
    $tipo = $_POST['tipo'] ?? '';

    // Validar que los campos obligatorios no estén vacíos
    if (empty($nombreClinica) || empty($direccion) || empty($region) || empty($correo) || empty($clave) || empty($tipo)) {
        echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios.']);
        exit();
    }

    // Llamar a la función para guardar la clínica
    $resultado = Clinicas::guardar_clinica($nombreClinica, $tipo, $direccion, $region, $correo, $clave);

    // Responder en formato JSON
    echo json_encode($resultado);
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
?>
