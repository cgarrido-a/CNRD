<?php
require('phpqrcode-master/qrlib.php');
require('fpdf/fpdf.php');

// Datos del voluntario
$nombre = "Cristobal Viveros";
$cargo = "Estudiante medicina veterinaria";
$institucion = "Colegio Médico Veterinario";
$voluntarioId = 85;
$qr_url = "https://www.tusitio.com/validarVoluntario.php?id=" . $voluntarioId;

// Ruta para guardar el QR
$rutaImagenQR = 'qrs/voluntario_' . $voluntarioId . '.png';

// Generar el código QR si no existe
if (!file_exists($rutaImagenQR)) {
    if (!is_dir('qrs')) {
        mkdir('qrs', 0755, true);
    }
    QRcode::png($qr_url, $rutaImagenQR, 'L', 10, 2);
}

// Crear la credencial como imagen
$width_px = 1003; // 8.5 cm a 300 dpi
$height_px = 625; // 5.3 cm a 300 dpi

$image = imagecreatetruecolor($width_px, $height_px);
$white = imagecolorallocate($image, 255, 255, 255);
$black = imagecolorallocate($image, 0, 0, 0);
$red = imagecolorallocate($image, 200, 0, 0);
$gray = imagecolorallocate($image, 230, 230, 230);

// Fondo blanco
imagefill($image, 0, 0, $white);

// Borde negro alrededor
$borderThickness = 10;
imagefilledrectangle($image, 0, 0, $width_px - 1, $height_px - 1, $black);
imagefilledrectangle($image, $borderThickness, $borderThickness, $width_px - $borderThickness - 1, $height_px - $borderThickness - 1, $white);

// Fondo gris para el área del logo
imagefilledrectangle($image, $borderThickness, $borderThickness, 250, $height_px - $borderThickness, $gray);

// Colocar el logo
$logo_path = 'img/cnrd.png';
if (file_exists($logo_path)) {
    $logo = imagecreatefrompng($logo_path);
    $logo_width = 260;
    $logo_height = 370;
    $logo_x = 0;
    $logo_y = 0;
    imagecopyresampled($image, $logo, $logo_x, $logo_y, 0, 0, $logo_width, $logo_height, imagesx($logo), imagesy($logo));
}

// Fuente
$font = 'phpqrcode-master/Arial.ttf';
if (!file_exists($font)) {
    echo "Fuente no encontrada.";
    exit;
}

// Texto de encabezado en rojo
imagettftext($image, 30, 0, 330, 80, $red, $font, "EQUIPO MÉDICO VETERINARIO");
imagettftext($image, 30, 0, 410, 130, $red, $font, "ZONA DE EMERGENCIA");

// Etiquetas y cuadros para Nombre, Cargo e Institución
$startY = 200;
$fieldHeight = 50;
$spacing = 100;

$labels = ["Nombre:", "Cargo:", "Institución:"];
$values = [$nombre, $cargo, $institucion];

foreach ($labels as $index => $label) {
    $yPosition = $startY + ($index * $spacing);

    // Etiqueta
    imagettftext($image, 20, 0, 265, $yPosition, $black, $font, $label);

    // Cuadro para el campo
    $boxX1 = 405;
    $boxY1 = $yPosition - 30;
    $boxX2 = 960;
    $boxY2 = $boxY1 + $fieldHeight;
    imagerectangle($image, $boxX1, $boxY1, $boxX2, $boxY2, $black);

    // Texto dentro del cuadro
    imagettftext($image, 20, 0, $boxX1 + 10, $yPosition+5, $black, $font, $values[$index]);
}

// Código QR
$qr_image = imagecreatefrompng($rutaImagenQR);
$qr_width = 200;
$qr_height = 200;
$qr_x = 25;
$qr_y = 350;
imagecopyresampled($image, $qr_image, $qr_x, $qr_y, 0, 0, $qr_width, $qr_height, imagesx($qr_image), imagesy($qr_image));

// Guardar la credencial como imagen temporal
$credencialPath = 'credencial_voluntario.png';
imagepng($image, $credencialPath);
imagedestroy($image);
if (isset($logo)) imagedestroy($logo);
imagedestroy($qr_image);

// Generar el PDF con la credencial
$pdf = new FPDF('P','mm','Letter'); // 85 mm x 53 mm
$pdf->AddPage();
$pdf->Image($credencialPath, 20, 20, 85, 53); // Insertar la imagen ajustada al tamaño
$pdf->Output('credencial_voluntario.pdf', 'I'); // Mostrar el PDF en el navegador
?>
