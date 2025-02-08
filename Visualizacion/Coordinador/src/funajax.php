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
    case 'CamUs22':
        // Validar que los campos obligatorios estén presentes
        if (!isset($_POST['tipo'], $_POST['id'], $_POST['camp'], $_POST['valCam'], $_POST['val2'])) {
            echo '0Faltan datos requeridos.';
            exit;
        }

        // Validar y sanitizar entradas
        $tipo = $_POST['tipo'];
        $id = $_POST['id'];
        $camp = $_POST['camp'];
        $valCam = $_POST['valCam'];
        $val2 = $_POST['val2'];

        if (!$id || !$camp || !$valCam) {
            echo '0Datos inválidos.';
            exit;
        }

        $r = false;

        $r = Voluntarios::actualizarVol($id, $camp, $valCam);

        // Manejo de respuesta
        if ($r) {
            $r2 = Usuario::ActCon($val2, $id);
            if ($r2) {
                echo '1correcto';
            } else {
                echo '0Error al actualizar el dato.';
            }
        } else {
            echo '0Error al actualizar el dato.';
        }
        break;
    
    case 'CamEstVol':
        if (isset($_POST['id']) && isset($_POST['valor'])) {
            $id = $_POST['id'];
            $valor = $_POST['valor'];
            $resultado = Usuario::CamEstVol($id, $valor);
            echo $resultado;
            if ($resultado) {
                echo "correcto";
            } else {
                echo "incorrecto";
            }
        }
        break;
    
    case 'ActCred':
        if (isset($_POST['id']) && isset($_POST['institucioncred'])) {
            $r = Usuario::actualizarCredencial($_POST['id'], $_POST['nombrecred'], $_POST['institucioncred'], $_POST['cargocred']);
            echo $r;
        }
        break;

    case 'CambProf':
        $r = Usuario::actualizarUsuario($_POST['id'], $_POST['variable'], $_POST['valor']);
        if ($r) {
            echo '1correcto';
        }
        break;

    case 'CamUs':
        // Validar que los campos obligatorios estén presentes
        if (!isset($_POST['tipo'], $_POST['id'], $_POST['camp'], $_POST['valCam'])) {
            echo '0Faltan datos requeridos.';
            exit;
        }

        // Validar y sanitizar entradas
        $tipo = $_POST['tipo'];
        $id = $_POST['id'];
        $camp = $_POST['camp'];
        $valCam = $_POST['valCam'];

        if (!$id || !$camp || !$valCam) {
            echo '0Datos inválidos.';
            exit;
        }

        $r = false;


        $r = Voluntarios::actualizarVol($id, $camp, $valCam);

        // Manejo de respuesta
        if ($r) {
            echo '1correcto';
        } else {
            echo '0Error al actualizar el dato.';
        }
        break;


    case 'MarAsisVol':

        // Validación de los parámetros recibidos
        if (isset($_POST['variable']) && isset($_POST['id']) && isset($_POST['valor']) && isset($_POST['accion'])) {
            $voluntarioId = intval($_POST['id']);
            $lugarId = $_POST['valor'];
            $accion = $_POST['accion'];
            echo '<br>' . $voluntarioId;
            echo '<br>' . $lugarId;
            if ($voluntarioId && $lugarId) {
                switch ($accion) {
                    case 'cerrar':
                        $partes = explode('|', $lugarId);
                        if (count($partes) < 3) {
                            throw new Exception("Formato de cadena inválido.");
                        }
                        $id = base64_decode($partes[1]); // Extraer y decodificar el ID

                        if (!is_numeric($id) || $id <= 0) {
                            $resultado = "ID de clínica no válido.";
                        }

                        $resultado = Voluntarios::registrarSalida($voluntarioId, $id);

                        break;

                    case 'iniciar':
                        $partes = explode('|', $lugarId);
                        if (count($partes) < 3) {
                            throw new Exception("Formato de cadena inválido.");
                        }
                        $id = base64_decode($partes[1]); // Extraer y decodificar el ID

                        if (!is_numeric($id) || $id <= 0) {
                            $resultado = "ID de clínica no válido.";
                        }

                        $resultado = Voluntarios::registrarEntrada($voluntarioId, $id);

                        break;
                }
            } else {
                $resultado = "ID de voluntario o lugar no válidos.";
            }
        } else {
            $resultado = "Faltan parámetros en la solicitud.";
        }

        echo $resultado;

        break;


    case 'CamEstUs':
        $r = Usuario::actualizarUsuario($_POST['id'], $_POST['variable'], $_POST['nuevaClave']);
        if ($r) {
            echo 'correcto';
        } else {
            echo 'error';
        }
        break;
    case 'CrearCred':
        if (isset($_POST['id']) && isset($_POST['institucioncred'])) {
            $r = Usuario::insertarCredencial($_POST['id'], $_POST['nombrecred'], $_POST['institucioncred'], $_POST['cargocred']);
            echo $r;
        }
        break;

    case 'CambClavVol':
        error_log('Caso CambClavVol iniciado');
        if (!isset($_SESSION['UserLog'])) {
            error_log('No autorizado: sesión no iniciada');
            echo 'No autorizado: sesión no iniciada';
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
