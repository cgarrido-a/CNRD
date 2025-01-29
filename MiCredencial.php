<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_type'])) {
    header('Location: login.html');
    exit();
}

include_once('app/func.inc.php');
require_once('phpqrcode-master/qrlib.php');
$user_type = $_SESSION['user_type'];
$servidor = 'http://cnrd-intranet.free.nf/';


if (!isset($_SESSION['user_type']) || !isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

// Inicializar variables
$id = $_GET['id'] ?? null;
$getId = $_GET['id'] ?? null;
// Validar si $id es null

// Función para validar si el ID tiene formato 'c-[número]'
function esIdCoordinacionValido($getId, $userId)
{
    return strpos($getId, 'c-') === 0 && $getId === 'c-' . $userId;
}

// Validar según el tipo de usuario
switch ($_SESSION['user_type']) {
    case 'voluntario':
        $id = $_SESSION['user_id'];
        $usuario = Usuario::get_cedusuario($id);
        $usuario2 = Usuario::obtenerVoluntarioPorId($id);
        $fotoperfil = $usuario2['Fotoperfil'];
        break;

    case 'Coordinacion':
        if ($_SESSION['region'] === 'Nacional') {
            // Nacional puede trabajar con ambos tipos de ID
            if ($getId) {
                if (strpos($getId, 'c-') === 0) {
                    $idConPrefijo = $getId;
                    $idSinPrefijo = explode('c-', $getId)[1];
                    $usuario2 = Usuario::obtenerUsuariosId($idSinPrefijo);
                    $usuario = Usuario::get_cedusuario($idConPrefijo);
                    $fotoperfil = $usuario2['foto_perfil'];
                } else {
                    $id = $getId;
                    $usuario2 = Usuario::obtenerVoluntarioPorId($id);
                    $usuario = Usuario::get_cedusuario($id);
                    $fotoperfil = $usuario2['Fotoperfil'];
                }
            } else {
                header("Location: index.php");
                exit;
            }
        } else {
            // Solo IDs sin prefijo son válidos
            if ($getId) {
                if (strpos($getId, 'c-') === 0) {
                    if ($getId === 'c-' . $_SESSION['user_id']) {
                        $idConPrefijo = $getId;
                        $idSinPrefijo = explode('-', $getId)[1];
                        $usuario2 = Usuario::obtenerUsuariosId($idSinPrefijo);
                        $usuario = Usuario::get_cedusuario($idConPrefijo);
                        $fotoperfil = $usuario2['foto_perfil'];
                    } else {
                        header("Location: index.php");
                        exit;
                    }
                } else {
                    $id = $getId;
                    $usuario = Usuario::get_cedusuario($id);
                    $usuario2 = Usuario::obtenerVoluntarioPorId($usuario['id_voluntario']);
                    if (isset($usuario2['foto_perfil'])) {
                        $fotoperfil = $usuario2['foto_perfil'];
                    } else {
                        $fotoperfil = $usuario2['Fotoperfil'];
                    }
                }
            } else {
                header("Location: index.php");
                exit;
            }
        }

        break;
}

if ($id === null) {
    header("Location: index.php");
    exit;
}
// URL que se codifica en el QR
if (!$usuario) {
    die('Error: Usuario sin credencial habilitada.');
}
if (!$usuario || !$usuario2) {
    die('Error: Usuario no encontrado.');
}


if ($usuario2['estado'] !== 'habilitado') {
    die('Error: El usuario no está activo.');
}

function validarFotoperfil($fotoperfil) {
    // Verificar si el fotoperfil está vacío
    if ($fotoperfil === "") {
        die('Error: Sin foto de perfil.');
        
    }

    // Obtener la extensión del archivo
    $ext = pathinfo($fotoperfil, PATHINFO_EXTENSION);

    // Verificar si la extensión es pdf o webp
    if (strtolower($ext) === "word" || strtolower($ext) === "pdf" || strtolower($ext) === "webp") {
        die('Error: Foto de perfil en formato invalido.');
        
    }
}

// Ejemplos de uso
validarFotoperfil($fotoperfil);

$url = 'https://cnrd-intranet.free.nf/validacion.php?validador=' . $usuario['codigo_verificacion'];

// Generación del código QR en memoria
ob_start();
QRcode::png($url, null, QR_ECLEVEL_L, 10);
$qrImage = base64_encode(ob_get_clean());

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credencial CNRD</title>

    <!-- Enlace a Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEJx3+8pA9gF/jaN63r07YdsmmBvY4hVtExMk2zkP5zJEd20kpspa5Pqf5q4P" crossorigin="anonymous">

    <!-- Enlace a los iconos de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f4f4f4;
        }

        .credencial {
            width: 320px;
            height: 540px;
            position: relative;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 15px;
        }

        .credencial .logo {
            width: 80%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 5px;
        }

        .credencial .logo img {
            max-width: 50%;
            height: auto;
        }

        .credencial .perfil {
            width: 180px;
            height: 180px;
            margin-top: -25px;
            margin-bottom: 0px;
            border-radius: 25%;
            overflow: hidden;
            border: 3px solid #ccc;
        }

        .credencial .perfil img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .credencial .info {
            width: 100%;
            text-align: center;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .credencial .info h2 {
            font-size: 18px;
            color: #333;
            margin-bottom: 5px;
        }

        .credencial .info p {
            font-size: 14px;
            color: #555;
            margin-bottom: 3px;
        }

        .credencial .info .institucion {
            font-size: 16px;
            font-weight: bold;
            color: #d32f2f;
            margin-top: 10px;
        }

        .qr-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            /* Espacio entre el QR y la ID */
        }

        .id-vertical {
            writing-mode: vertical-rl;
            /* Escribe en vertical */
            text-orientation: sideways;
            /* Asegura que los caracteres estén orientados correctamente */
            font-size: 16px;
            color: #333;
            font-weight: bold;

        }

        .credencial .qr {
            width: 120px;
            height: 120px;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 2px solid #ccc;
            border-radius: 8px;
            margin-top: auto;
        }

        .credencial .qr img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Estilo adicional para los botones */
        .btn:hover {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }



        @media (max-width: 768px) {
            .credencial {
                width: 90%;
                height: auto;
            }

            .credencial .perfil {
                width: 150px;
                height: 150px;
            }

            .credencial .info h2 {
                font-size: 16px;
            }

            .credencial .info p {
                font-size: 12px;
            }

            .credencial .info .institucion {
                font-size: 14px;
            }

            .credencial .qr {
                width: 100px;
                height: 100px;
            }
        }
    </style>
</head>

<body>
    <?php



    ?>

    <div class="credencial">
        <div class="logo">
            <img src="img/cnrd.png" alt="Logo CNRD">
        </div>
        <div class="perfil">
            <img src="<?php echo htmlspecialchars($fotoperfil); ?>" alt="Foto de perfil">
        </div>
        <div class="info">
            <h2><?php echo htmlspecialchars($usuario['nombre']); ?></h2>
            <p><strong>Cargo:</strong> <?php echo htmlspecialchars($usuario['cargo']); ?></p>
            <p class="institucion"><?php echo htmlspecialchars($usuario['institucion']); ?></p>
        </div>
        <div class="qr-container">
            <div class="qr">
                <img src="data:image/png;base64,<?php echo $qrImage; ?>" alt="Código QR">
            </div>
            <div class="id-vertical">
                <?php
                if (isset(explode('-', $id)[1])) {
                    echo htmlspecialchars($id);
                } else {
                    echo htmlspecialchars('C-' . $id);
                }
                ?>
            </div>
        </div>
        <br>
        <div class="botones-container">
            <!-- Botón Volver -->
            <a href="index.php" class="btn btn-outline-primary btn-lg px-4 py-2 shadow rounded-pill">
                <i class="bi bi-arrow-left-circle"></i> Volver
            </a>

            <!-- Botón Imprimir -->
            <a href="plantillas/credencial.php?id=<?php echo $id; ?>" class="btn btn-outline-success btn-lg px-4 py-2 shadow rounded-pill">
                <i class="bi bi-printer"></i> Imprimir
            </a>
        </div>
    </div>

    <!-- Botones fuera del contenedor de la credencial -->

    <!-- Enlace a Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0pA6Vdd97kz5X5z6c7k2y5z7Ed5z5AKZGb7mIghZlCpCpRzM" crossorigin="anonymous"></script>
</body>

</html>