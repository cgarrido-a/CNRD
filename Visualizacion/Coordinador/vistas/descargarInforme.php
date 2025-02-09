<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED);

include_once('../plantillas/LLamstan.inc.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está autenticado
if (!isset($_SESSION['UserLog'])) {
    die("Error: No tienes acceso a esta página.");
}

// Verificar si se recibió un ID de informe
if (!isset($_GET['id'])) {
    die("Error: ID de informe no proporcionado.");
}

$informe_id = $_GET['id'];

include_once(__DIR__ . '/../../../fpdf/fpdf.php');
include_once(__DIR__ . '/../../../app/conex.inc.php'); // Conexión a la BD

// Obtener el informe de la base de datos
$conexion = new PDO("mysql:host=localhost;dbname=cnrd_nueva", "root", "");
$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $conexion->prepare("SELECT * FROM informes WHERE id = ?");
$stmt->execute([$informe_id]);
$informe = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$informe) {
    die("Error: Informe no encontrado.");
}

// Obtener animales afectados
$stmtAnimales = $conexion->prepare("SELECT * FROM animales_afectados WHERE informe_id = ?");
$stmtAnimales->execute([$informe_id]);
$animales_afectados = $stmtAnimales->fetchAll(PDO::FETCH_ASSOC);

// Clase PDF extendida con Header y Footer
class PDF extends FPDF {
    function Header() {
        $this->Image(__DIR__ . '/../../../img/cnrd.png', 10, 6, 35);
        $this->SetY(30);
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, utf8_decode('Informe Detallado'), 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

// Datos del Informe
$pdf->Cell(50, 10, "Fecha: ", 0, 0);
$pdf->Cell(140, 10, $informe['fecha'], 0, 1);

$pdf->Cell(50, 10, "Región: ", 0, 0);
$pdf->Cell(140, 10, utf8_decode($informe['region']), 0, 1);

$pdf->Cell(50, 10, "Provincia: ", 0, 0);
$pdf->Cell(140, 10, utf8_decode($informe['provincia']), 0, 1);

$pdf->Cell(50, 10, "Comuna: ", 0, 0);
$pdf->Cell(140, 10, utf8_decode($informe['comuna']), 0, 1);

$pdf->Cell(50, 10, "Ubicación Georreferencial: ", 0, 0);
$pdf->MultiCell(140, 10, utf8_decode($informe['ubicacion_georreferencial']));

$pdf->Cell(50, 10, "Dirección: ", 0, 0);
$pdf->MultiCell(140, 10, utf8_decode($informe['direccion']));

$pdf->Cell(50, 10, "Tipo de Zona: ", 0, 0);
$pdf->Cell(140, 10, ucfirst($informe['tipo_zona']), 0, 1);

$pdf->Cell(50, 10, "Voluntario Responsable: ", 0, 0);
$pdf->Cell(140, 10, $informe['voluntario_id'], 0, 1);

$pdf->Cell(50, 10, "Tipo de Evento: ", 0, 0);
$pdf->Cell(140, 10, ucfirst($informe['tipo_evento']), 0, 1);

$pdf->Cell(50, 10, "Categoría: ", 0, 0);
$pdf->Cell(140, 10, ucfirst($informe['categoria']), 0, 1);

$pdf->Cell(50, 10, "Descripción del Evento: ", 0, 0);
$pdf->MultiCell(140, 10, utf8_decode($informe['descripcion_evento']));

$pdf->Cell(50, 10, "Procesos Realizados: ", 0, 0);
$pdf->MultiCell(140, 10, utf8_decode($informe['procesos_realizados']));

$pdf->Cell(50, 10, "Decisiones Tomadas: ", 0, 0);
$pdf->MultiCell(140, 10, utf8_decode($informe['decisiones_tomadas']));

$pdf->Cell(50, 10, "Fecha de Creación: ", 0, 0);
$pdf->Cell(140, 10, $informe['created_at'], 0, 1);

$pdf->Cell(50, 10, "Última Modificación: ", 0, 0);
$pdf->Cell(140, 10, $informe['updated_at'], 0, 1);

// Agregar Animales Afectados
if (!empty($animales_afectados)) {
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, utf8_decode('Animales Afectados'), 0, 1, 'C');

    // Encabezado tabla
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(50, 10, "Especie", 1, 0, 'C');
    $pdf->Cell(30, 10, "Atendidos", 1, 0, 'C');
    $pdf->Cell(30, 10, "Fallecidos", 1, 0, 'C');
    $pdf->Cell(30, 10, "Pendientes", 1, 1, 'C');

    $pdf->SetFont('Arial', '', 10);
    foreach ($animales_afectados as $animal) {
        $pdf->Cell(50, 10, utf8_decode(ucfirst($animal['especie'])), 1, 0, 'C');
        $pdf->Cell(30, 10, $animal['n_atendidos'], 1, 0, 'C');
        $pdf->Cell(30, 10, $animal['n_fallecidos'], 1, 0, 'C');
        $pdf->Cell(30, 10, $animal['n_pendientes'], 1, 1, 'C');
    }
}

$pdf->Output("Informe_{$informe['id']}.pdf", "D");
exit();
?>
