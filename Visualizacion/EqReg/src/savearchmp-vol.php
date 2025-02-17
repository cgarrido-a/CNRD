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
    $idVoluntario = $_POST['id'] ?? null;
    $campo = null;
    $archivoSubido = null;

    // Determina qué archivo se está subiendo
    if (isset($_FILES['newFotoPerfil'])) {
        $campo = 'Fotoperfil';
        $archivoSubido = $_FILES['newFotoPerfil'];
    } elseif (isset($_FILES['newCertificadoTitulo'])) {
        $campo = 'Certificado_titulo';
        $archivoSubido = $_FILES['newCertificadoTitulo'];
    } elseif (isset($_FILES['newCertificadoAntecedentes'])) {
        $campo = 'certificadoAntecedentes';
        $archivoSubido = $_FILES['newCertificadoAntecedentes'];
    }

    // Verifica si se recibieron datos válidos
    if (!$idVoluntario || !$campo || !$archivoSubido) {
        header("Location: ../vistas/verVoluntario.php?id=$idVoluntario&error=Datos no válidos");
        exit;
    }

    // Crea el directorio específico para el idVoluntario si no existe
    $directorio = "../../../uploads/".$idVoluntario;
    if (!is_dir($directorio)) {
        mkdir($directorio, 0777, true);
    }

    // Genera la ruta completa para el archivo
    $rutaArchivo = $directorio . "/" . basename($archivoSubido['name']);

    // Valida y mueve el archivo
    if (move_uploaded_file($archivoSubido['tmp_name'], $rutaArchivo)) {
      switch ($campo) {
            case 'Fotoperfil':
                $resultado = Usuario::actualizarArchivo($idVoluntario, $campo, $rutaArchivo);
                break;
            case 'Certificado_titulo':
                $resultado =Usuario::actualizarArchivo($idVoluntario, $campo, $rutaArchivo);
                //Agregar funcion para que se ingrese como solicitud (no se debe cambiar en la base de datos)
                break;
            case 'CertificadoAntecedentes':
                $resultado =Usuario::actualizarArchivo($idVoluntario, $campo, $rutaArchivo);
                //Agregar funcion para que se ingrese como solicitud (no se debe cambiar en la base de datos)
                break;
        }
       
            if ($resultado) {
                header("Location: ../vistas/verVoluntario.php?id=$idVoluntario&success=Archivo subido correctamente");
            } else {
                header("Location: ../vistas/verVoluntario.php?id=$idVoluntario&error=Error al actualizar la base de datos");
            }
       
    } else {
        header("Location: ../vistas/verVoluntario.php?id=$idVoluntario&error=Error al subir el archivo");
    }
}
