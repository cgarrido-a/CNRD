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

require_once($_SERVER['DOCUMENT_ROOT'] . '/app/func.inc.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/fpdf/fpdf.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/phpqrcode-master/qrlib.php');

function generarPDFConQR($idClinica, $nombreClinica)
{
    // Configurar el contenido del QR

    $contenidoQR =  $idClinica;

    // Ruta temporal para guardar el QR
    $rutaQR = __DIR__ . "/temp/clinica_" . $idClinica . ".png";

    // Crear el directorio si no existe
    if (!file_exists(__DIR__ . "/temp")) {
        mkdir(__DIR__ . "/temp", 0777, true);
    }

    // Generar el QR
    QRcode::png($contenidoQR, $rutaQR, QR_ECLEVEL_L, 10);

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

    // Salida del PDF
    $nombreArchivo = "Clinica_" . $idClinica . ".pdf";
    $pdf->Output('I', $nombreArchivo); // Inline view (mostrar en navegador)

    // Limpiar archivo QR temporal
    unlink($rutaQR);
}

// Detectar si el ID y el nombre están en el query string
if (isset($_GET['id']) && isset($_GET['nombre'])) {
    $idClinica = Clinicas::generarCadena(intval($_GET['id'])    );
    $nombreClinica = htmlspecialchars($_GET['nombre']); // Evitar inyecciones XSS
    generarPDFConQR($idClinica, $nombreClinica);
} else {
    echo "Faltan parámetros.";
}
