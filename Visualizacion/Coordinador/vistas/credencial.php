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
if (!isset($_GET['id'])) {
    die('Error: Parámetro de ID faltante.');
}

$userType = $_SESSION['user_type'];

// Validar permisos
if ($userType !== 'Coordinacion' && $_GET['id'] != $_SESSION['user_id'] && $userType !== 'admin') {
    die('Error: No tienes permisos para acceder a esta credencial.');
}

// Crear PDF
class PDF_Rotate extends FPDF
{
    protected $angle = 0;

    function Rotate($angle, $x = -1, $y = -1)
    {
        if ($x == -1) $x = $this->x;
        if ($y == -1) $y = $this->y;
        if ($this->angle != 0) $this->_out('Q');
        $this->angle = $angle;
        if ($angle != 0) {
            $angle *= M_PI / 180;
            $c = cos($angle);
            $s = sin($angle);
            $cx = $x * $this->k;
            $cy = ($this->h - $y) * $this->k;
            $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.5F %.5F cm 1 0 0 1 %.5F %.5F cm', $c, $s, -$s, $c, $cx, $cy, -$cx, -$cy));
        }
    }

    function _endpage()
    {
        if ($this->angle != 0) {
            $this->angle = 0;
            $this->_out('Q');
        }
        parent::_endpage();
    }

    function TextWithRotation($x, $y, $txt, $txt_angle)
    {
        $this->Rotate($txt_angle, $x, $y);
        $this->Text($x, $y, $txt);
        $this->Rotate(0);
    }
}

// Obtener información del usuario o voluntario
$id = $_SESSION['UserLog']->obtener_id();
$id_formateado = '';

$id = $_SESSION['UserLog']->obtener_id();
$usuario = Usuario::get_cedusuario($id);
$usuario2 = Voluntarios::obtenerVoluntarioPorId($id);
$fotoperfil = $usuario2->obtener_fotoperfil();
$nombre= $usuario['nombre'];
$cargo= $usuario['cargo'];
$institucion= $usuario['institucion'];

if (!$usuario || !$usuario2) {
    die('Error: Usuario no encontrado.');
}

if ($_SESSION['UserLog']->obtener_estado() !== 'habilitado') {
    die('Error: El usuario no está activo.');
}

// Generar URL para QR
$url = 'https://cnrd-intranet.free.nf/validacion.php?validador=' . $usuario['codigo_verificacion'];
$tempDir = __DIR__ . '/../temp/';
if (!file_exists($tempDir)) {
    mkdir($tempDir, 0755, true);
}
$qrFile = $tempDir . uniqid('qr_') . '.png';
QRcode::png($url, $qrFile, QR_ECLEVEL_L, 10, 0);

// Crear PDF
$pdf = new PDF_Rotate('P', 'mm', 'Letter');
$pdf->AddPage();
$pdf->SetAutoPageBreak(false);

// Fondo blanco
$pdf->SetFillColor(255, 255, 255);
$pdf->Rect(0, 0, 215.9, 279.4, 'F');

// Credencial
$credencialX = ($pdf->GetPageWidth() - 54) / 2;
$credencialY = ($pdf->GetPageHeight() - 86) / 2;
$pdf->SetFillColor(240, 240, 240);
$pdf->Rect($credencialX, $credencialY, 54, 86, 'DF');

// Logo
$pdf->Image(__DIR__ . '/../../../img/cnrd.png', $credencialX + 14, $credencialY - 2, 25);

// Foto de perfil
if ($fotoperfil && file_exists(__DIR__ . $fotoperfil)) {
    $perfilPath = __DIR__ .  $fotoperfil;
    $pdf->Image($perfilPath, $credencialX + 17, $credencialY + 23.5, 20, 26.6669);
}

// Información del nombre
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetXY($credencialX, $credencialY + 50);
$nombreText = mb_convert_encoding($nombre, 'ISO-8859-1', 'UTF-8');
$pdf->MultiCell(54, 5, $nombreText, 0, 'C');
$rt = $credencialY + 54;



// Calcular cuántas líneas ocupa el texto de $institucion
$max_width = 50; // Ancho de la celda donde va el texto
$line_height = 3; // Altura de cada línea (de acuerdo a tu configuración)
$font_size = 9;   // Tamaño de la fuente

// Establecer fuente y obtener el ancho de los caracteres en la fuente
$pdf->SetFont('Arial', '', $font_size);
$word_width2 = $pdf->GetStringWidth('W'); // Ancho promedio de un caracter (en píxeles)

$words_in_line2 = floor($max_width / $word_width2); // Aproximadamente cuántos caracteres caben en una línea (ajustado por la fuente)

// Calcular el total de líneas necesarias
$total_lines2 = ceil(strlen('Cargo: ' . $cargo) / $words_in_line2);

$cargo2 = mb_convert_encoding('Cargo: ' . $cargo, 'ISO-8859-1', 'UTF-8');
// Si ocupa más de una línea, usamos esta configuración
if ($total_lines2 > 1) {
    $pdf->SetFont('Arial', '', 8);   // Fuente en itálica (modo I)
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetXY($credencialX, $rt);
    $pdf->MultiCell(54, 2.8, $cargo2, 0, 'C');
} else {
    $pdf->SetFont('Arial', '', 8);   // Fuente en itálica (modo I)
    $pdf->SetTextColor(255, 0, 0);
    $pdf->SetXY($credencialX, $rt);
    $pdf->MultiCell(54, 2.8, $cargo2, 0, 'C');
}



// Calcular cuántas líneas ocupa el texto de $institucion
$max_width = 50; // Ancho de la celda donde va el texto
$line_height = 3; // Altura de cada línea (de acuerdo a tu configuración)
$font_size = 9;   // Tamaño de la fuente

// Establecer fuente y obtener el ancho de los caracteres en la fuente
$pdf->SetFont('Arial', 'IB', $font_size);
$word_width = $pdf->GetStringWidth('W'); // Ancho promedio de un caracter (en píxeles)

$words_in_line = floor($max_width / $word_width); // Aproximadamente cuántos caracteres caben en una línea (ajustado por la fuente)

// Calcular el total de líneas necesarias
$total_lines = ceil(strlen($institucion) / $words_in_line);

if ($total_lines > 1 && $total_lines2 > 1) {
    $rt += 3;
}


// Si ocupa más de una línea, usamos esta configuración
if ($total_lines > 1) {
    $pdf->SetFont('Arial', 'IB', 9);   // Fuente en itálica (modo I)
    $pdf->SetTextColor(255, 0, 0);
    $instituciont = mb_convert_encoding($institucion, 'ISO-8859-1', 'UTF-8');
    $pdf->SetXY($credencialX, $rt += 2.9);
    $pdf->MultiCell(54, 2.8, $instituciont, 0, 'C');

    $rt += 6;
    // Código QR
    $pdf->Image($qrFile, $credencialX + 17, $rt, 18.5);
    // Texto al lado del QR
    $pdf->SetFont('Arial', '', 7);
    $pdf->SetTextColor(0, 0, 0);   // Color rojo (RGB)
    $idText = 'ID: ' . $id_formateado;
    $pdf->TextWithRotation($credencialX + 35.8, $rt + 2, $idText, 270);
} else {
    $pdf->SetFont('Arial', 'IB', 9);   // Fuente en itálica (modo I)
    $pdf->SetTextColor(255, 0, 0);
    $instituciont = mb_convert_encoding($institucion, 'ISO-8859-1', 'UTF-8');
    $pdf->SetXY($credencialX, $rt += 6.5);
    $pdf->MultiCell(54, 2, $instituciont, 0, 'C');
    $rt += 4;
    // Código QR
    $pdf->Image($qrFile, $credencialX + 17, $rt, 20);
    // Texto al lado del QR
    $pdf->SetFont('Arial', '', 7);
    $pdf->SetTextColor(0, 0, 0);   // Color rojo (RGB)
    $idText = 'ID: ' . $id_formateado;
    $pdf->TextWithRotation($credencialX + 37.5, $rt + 2, $idText, 270);
}







if (file_exists($qrFile)) {
    unlink($qrFile);
}

$pdf->Output('I', 'Credencial_CNRD.pdf');
