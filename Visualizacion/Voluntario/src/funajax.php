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
switch ($_POST['variable']) {
    case 'CamEstUs':
        $r = Usuario::actualizarUsuario($_POST['id'], $_POST['variable'], $_POST['nuevaClave']);
        if ($r) {
            echo 'correcto';
        }else{
            echo 'error';
        }
        break;
    
    case 'CambClavVol':
        error_log('Caso CambClavVol iniciado');
        if (!isset($_SESSION['UserLog'])) {
            error_log('No autorizado: sesión no iniciada');
            echo 'No autorizado';
            exit;
        }
        if ($_SESSION['UserLog']->obtener_id()!=$_POST['id']) {
            error_log('No autorizado: Usuario no corresponde');
            echo 'No autorizado';
            exit;
        }

        if (isset($_POST['id'], $_POST['nuevaClave'])) {
            $id = $_POST['id'];
            $nuevaClave = $_POST['nuevaClave'];
            error_log("Datos recibidos: ID=$id, Clave=$nuevaClave");

            if (strlen($nuevaClave) < 8) {
                error_log('Clave demasiado corta');
                echo 'La nueva clave debe tener al menos 8 caracteres';
                exit;
            }

            try {
                $resultado = Voluntarios::actualizarVol($id, 'CambClavVol', $nuevaClave);
                if ($resultado) {
                    echo 'correcto';
                } else {
                    error_log('Error en la actualización de usuario');
                    echo 'error';
                }
            } catch (Exception $e) {
                error_log('Excepción capturada: ' . $e->getMessage());
                echo 'Error en el servidor';
            }
        } else {
            error_log('Datos faltantes');
            echo 'Datos inválidos';
        }
        break;
}

    

?>