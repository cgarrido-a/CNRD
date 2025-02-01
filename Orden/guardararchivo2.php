<?php
ob_start();  // Inicia el almacenamiento en búfer de salida

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'app/conex.inc.php'; // Incluye la conexión a la base de datos
require_once 'app/func.inc.php';  // Incluye las clases necesarias

if (!isset($_SESSION['user_type'])) {
    header('Location: login.html');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idVoluntario = $_POST['id'] ?? null;
    $campo = null;
    $archivoSubido = null;

    // Determina qué archivo se está subiendo
    if (isset($_FILES['newFotoPerfil'])) {
        $campo = 'Fotoperfil';
        $archivoSubido = $_FILES['newFotoPerfil'];
    } elseif (isset($_FILES['newCertificadoTitulo'])) {
        $campo = 'certificado_titulo';
        $archivoSubido = $_FILES['newCertificadoTitulo'];
    } elseif (isset($_FILES['newCertificadoAntecedentes'])) {
        $campo = 'certificadoAntecedentes';
        $archivoSubido = $_FILES['newCertificadoAntecedentes'];
    }

    // Verifica si se recibieron datos válidos
    if (!$idVoluntario || !$campo || !$archivoSubido) {
        header("Location: verVoluntario.php?id=$idVoluntario&error=Datos no válidos");
        exit;
    }

    // Crea el directorio específico para el idVoluntario si no existe
    $directorio = "uploads/" . $idVoluntario;
    if (!is_dir($directorio)) {
        mkdir($directorio, 0777, true);
    }

    // Genera la ruta completa para el archivo
    $rutaArchivo = $directorio . "/" . basename($archivoSubido['name']);

    // Valida y mueve el archivo
    if (move_uploaded_file($archivoSubido['tmp_name'], $rutaArchivo)) {
        if (strpos($idVoluntario, 'c-') === 0) {
            $partes = explode('-', $idVoluntario, 2);
            $prefijo = $partes[0] . '-';
            $resto = $partes[1];

            $r = Usuario::actualizarUsuario($resto, 'foto', $rutaArchivo);
            if ($r) {
                header("Location: miperfil.php?id=.$resto.&success=Archivo subido correctamente");
            } else {
                header("Location: miperfil.php?id=.$resto.&error=Error al actualizar la base de datos");
            }
        } else {
            $resultado = Usuario::actualizarArchivo($idVoluntario, $campo, $rutaArchivo);
            if ($resultado) {
                header("Location: miperfil.php?id=.$idVoluntario.&success=Archivo subido correctamente");
            } else {
                header("Location: miperfil.php?id=.$idVoluntario.&error=Error al actualizar la base de datos");
            }
        }
    } else {
        header("Location: miperfil.php?id=.$idVoluntario.&error=Error al subir el archivo");
    }
}

ob_end_flush();  // Finaliza y envía el contenido del búfer
?>
