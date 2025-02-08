<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
include_once '../plantillas/LLamstan.inc.php';
error_reporting(E_ALL & ~E_DEPRECATED);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['UserLog'])) {
    header('Location: login.html');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idVoluntario = $_POST['id'] ?? null;
    $campo = null;
    $archivoSubido = null;

    // Determina qué archivo se está subiendo
    if (isset($_FILES['newCertificadoTitulo'])) {
        $campo = 'certificado_titulo';
        $archivoSubido = $_FILES['newCertificadoTitulo'];
    }

    // Verifica si se recibieron datos válidos
    if (!$idVoluntario || !$campo || !$archivoSubido) {
        header("Location: ../vistas/miperfil.php?id=$idVoluntario&error=Datos no válidos");
        exit;
    }

    // Crea el directorio específico para el idVoluntario si no existe
    $directorio = "../../../uploads/" . $idVoluntario;
    if (!is_dir($directorio)) {
        mkdir($directorio, 0777, true);
    }

    // Genera la ruta completa para el archivo
    $rutaArchivo = $directorio . "/" . basename($archivoSubido['name']);

    // Valida y mueve el archivo
    if (move_uploaded_file($archivoSubido['tmp_name'], $rutaArchivo)) {
       // ingreso de solicitud 1


       //ingreso de solicitud 2
        if ($resultado) {
            header("Location: ../vistas/miperfil.php?success=Archivo subido correctamente");
        } else {
            header("Location: ../vistas/miperfil.php?error=Error al actualizar la base de datos");
        }
    } else {
        header("Location: ../vistas/miperfil.php?error=Error al subir el archivo");
    }
}
