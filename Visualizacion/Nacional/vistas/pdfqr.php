<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED);

include_once('../plantillas/LLamstan.inc.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_type'])) {
    header('Location: login.html');
    exit();
}

include_once(__DIR__ . '/../../../fpdf/fpdf.php');
include_once(__DIR__ . '/../../../phpqrcode-master/qrlib.php');

// Validación de acceso
if (!isset($_GET['id']) || !isset($_GET['nombre'])) {
    die('Error: Parámetros faltantes.');
}

// Obtener parámetros
$idClinica = htmlspecialchars($_GET['id']); // Validar el ID de la clínica
$nombreClinica = htmlspecialchars($_GET['nombre']); // Evitar inyecciones XSS

// Configurar QR
$contenidoQR = $idClinica;
$tempDir = __DIR__ . '/../temp/';
if (!file_exists($tempDir)) {
    mkdir($tempDir, 0755, true);
}
$qrFile = $tempDir . uniqid('qr_') . '.png';
QRcode::png($contenidoQR, $qrFile, QR_ECLEVEL_L, 10, 0);

// Crear PDF


$pdf = new FPDF('P', 'mm', 'Letter');
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, utf8_decode('' . $nombreClinica), 0, 1, 'C');
$pdf->Ln(5);
$pdf->SetFont('Arial', '', 12);


// Agregar el QR al PDF
if (file_exists($qrFile)) {
    $pdf->Image($qrFile, 75, 50, 80, 80); // Posición y tamaño del QR
} else {
    $pdf->Cell(0, 10, 'Error al generar el QR', 0, 1, 'C');
}

// Salida del PDF
$nombreArchivo = "Clinica_" . $idClinica . ".pdf";
$pdf->Output('I', $nombreArchivo); // Inline view (mostrar en navegador)

// Verificar si el archivo PDF se generó correctamente
$pdfOutputPath = __DIR__ . "/temp/Clinica_" . $idClinica . ".pdf";
$pdf->Output('I', $pdfOutputPath);

// Verificar si el archivo PDF se guardó correctamente
if (file_exists($pdfOutputPath)) {
    echo "El archivo PDF se generó correctamente. Puedes descargarlo <a href='temp/Clinica_" . $idClinica . ".pdf'>aquí</a>.<br>";
} else {
    echo "Error al generar el archivo PDF.<br>";
}

// Limpiar archivo QR temporal
unlink($qrFile);

?>
