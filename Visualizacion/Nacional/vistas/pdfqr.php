<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_type'])) {
    header('Location: login.html');
    exit();
}

require_once('../../../app/func.inc.php');
require_once('../../../fpdf/fpdf.php');
require_once('../../../app/phpqrcode-master/qrlib.php');

function generarPDFConQR($idClinica, $nombreClinica)
{
    // Limpiar el ID de la clínica para evitar caracteres especiales en la ruta
    $idClinica = preg_replace("/[^a-zA-Z0-9]/", "_", $idClinica); // Reemplaza caracteres no alfanuméricos por _

    // Configurar el contenido del QR
    $contenidoQR =  $idClinica;

    // Ruta temporal para guardar el QR (usando __DIR__ para obtener una ruta absoluta)
    $rutaQR = __DIR__ . "/temp/clinica_" . $idClinica . ".png";

    // Crear el directorio si no existe
    $tempDir = __DIR__ . "/temp/";
    if (!file_exists($tempDir)) {
        mkdir($tempDir, 0777, true); // 0777 es un permiso amplio para garantizar que se pueda crear el directorio
    }

    // Generar el QR
    QRcode::png($contenidoQR, $rutaQR, QR_ECLEVEL_L, 10);

    // Verificar si el QR se generó correctamente
    if (!file_exists($rutaQR)) {
        echo "Error al generar el QR. Ruta: " . $rutaQR . "<br>";
        return;
    }

    echo "QR generado con éxito. Ruta: " . $rutaQR . "<br>";

    // Comenzamos el almacenamiento en búfer de salida
    ob_start(); 

    // Crear el PDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, utf8_decode('' . $nombreClinica), 0, 1, 'C');
    $pdf->Ln(10);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Ln(20);

    // Agregar el QR al PDF
    if (file_exists($rutaQR)) {
        $pdf->Image($rutaQR, 75, 50, 60, 60); // Posición y tamaño del QR
    } else {
        $pdf->Cell(0, 10, 'Error al generar el QR', 0, 1, 'C');
    }

    // Guardar el archivo PDF en el servidor (para evitar errores en el navegador)
    $nombreArchivo = __DIR__ . "/temp/Clinica_" . $idClinica . ".pdf";
    $pdf->Output('F', $nombreArchivo); // Guardar el archivo en el servidor

    // Verificar si el archivo se guardó correctamente
    if (file_exists($nombreArchivo)) {
        echo "El archivo PDF se generó correctamente. Puedes descargarlo <a href='temp/Clinica_" . $idClinica . ".pdf'>aquí</a>.<br>";
    } else {
        echo "Error al generar el archivo PDF.<br>";
    }

    // Limpiar el buffer de salida
    ob_end_clean(); 

    // Limpiar archivo QR temporal
    unlink($rutaQR);
}

// Detectar si el ID y el nombre están en el query string
if (isset($_GET['id']) && isset($_GET['nombre'])) {
    $idClinica = Clinicas::generarCadena(intval($_GET['id']));
    $nombreClinica = htmlspecialchars($_GET['nombre']); // Evitar inyecciones XSS
    generarPDFConQR($idClinica, $nombreClinica);
} else {
    echo "Faltan parámetros.<br>";
}
?>
