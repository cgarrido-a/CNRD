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

$voluntarios = Voluntarios::obtenerVoluntarios();

class PDF extends FPDF {
    function Header() {
        // Logo ajustado
        $this->Image(__DIR__ . '/../../../img/cnrd.png', 10, 6, 35);
        $this->SetY(15); // Ajusta la posición para evitar que el título se superponga con el logo
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, utf8_decode('Listado de Voluntarios Habilitados'), 0, 1, 'C');
        $this->Ln(20);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
    $pdf->AddPage('L'); 
    $pdf->SetAutoPageBreak(true,15);  
$pdf->SetFont('Arial', 'B', 10);

// Encabezados de la tabla (sin la columna ID)
$headers = [
    'Nombre'    => 90,  // Ampliado para 15 caracteres más
    'Teléfono'  => 30,
    'Región'    => 45,  // Ajustado para 5 caracteres
    'Comuna'    => 45,  // Ajustado para 5 caracteres
    'Profesión' => 65   // Ajustado para 15 caracteres en una sola línea
];

foreach ($headers as $title => $width) {
    $pdf->Cell($width, 10, utf8_decode($title), 1, 0, 'C');
}
$pdf->Ln(); // Nueva línea

$pdf->SetFont('Arial', '', 10);

foreach ($voluntarios as $voluntario) {
    if (strtolower(trim($voluntario->obtener_estado())) === "habilitado") {
        $pdf->Cell(90, 10, mb_convert_encoding($voluntario->obtener_nombre(), 'ISO-8859-1', 'UTF-8'), 1, 0, 'L'); // Ampliado
        $pdf->Cell(30, 10, $voluntario->obtener_telefono(), 1, 0, 'C');
        $pdf->Cell(45, 10, mb_convert_encoding($voluntario->obtener_id_region(), 'ISO-8859-1', 'UTF-8'), 1, 0, 'C');
        $pdf->Cell(45, 10, mb_convert_encoding($voluntario->obtener_comuna(), 'ISO-8859-1', 'UTF-8'), 1, 0, 'C');
        $pdf->Cell(65, 10, mb_convert_encoding($voluntario->obtener_profesion(), 'ISO-8859-1', 'UTF-8'), 1, 0, 'C'); // Ajuste para 15 caracteres en una sola línea
        $pdf->Ln();
    }
}

// Salida del archivo PDF
$pdf->Output('I', 'Listado_Voluntarios_Habilitados.pdf');
?>
