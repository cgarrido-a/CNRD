<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED);
// Incluir la biblioteca PHPExcel (asegúrate de que la ruta sea correcta)
require_once( '/PHPExcel-1.8/PHPExcel-1.8/Classes/PHPExcel.php');

// Incluir la clase de base de datos y otras dependencias necesarias
require_once('app/func.inc.php');

// Clase para generar el Excel
class ExportarExcel
{
    public static function generarExcel()
    {
        // Crear un objeto de PHPExcel
        $objPHPExcel = new PHPExcel();

        // Configuración del archivo
        $objPHPExcel->getProperties()
            ->setCreator("TuNombre")
            ->setTitle("Reporte de Voluntarios")
            ->setSubject("Voluntarios habilitados")
            ->setDescription("Este archivo contiene información de los voluntarios.")
            ->setKeywords("voluntarios excel reporte")
            ->setCategory("Reporte");

        // Agregar encabezados de la tabla
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'ID')
            ->setCellValue('B1', 'Nombre')
            ->setCellValue('C1', 'Correo')
            ->setCellValue('D1', 'Teléfono')
            ->setCellValue('E1', 'Región')
            ->setCellValue('F1', 'Estado');

        // Obtener los datos de la consulta
        $datosJSON = Usuario::obtenerVoluntarios(); // Llama a la función existente
        $voluntarios = json_decode($datosJSON, true);

        if (!$voluntarios) {
            echo 'Error al obtener los datos.';
            return;
        }

        // Agregar los datos al archivo
        $fila = 2; // Comenzar desde la segunda fila
        foreach ($voluntarios as $voluntario) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $fila, $voluntario['id_voluntario'])
                ->setCellValue('B' . $fila, $voluntario['nombre'])
                ->setCellValue('C' . $fila, $voluntario['correo'])
                ->setCellValue('D' . $fila, $voluntario['telefono'])
                ->setCellValue('E' . $fila, $voluntario['region'])
                ->setCellValue('F' . $fila, $voluntario['estado']);
            $fila++;
        }

        // Ajustar el ancho de las columnas
        foreach (range('A', 'F') as $columna) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columna)->setAutoSize(true);
        }

        // Renombrar la hoja de cálculo
        $objPHPExcel->getActiveSheet()->setTitle('Voluntarios');

        // Establecer la hoja activa
        $objPHPExcel->setActiveSheetIndex(0);

        // Enviar el archivo Excel al navegador para descarga
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Reporte_Voluntarios.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit();
    }
}

// Llamar a la función para generar el archivo Excel
ExportarExcel::generarExcel();
?>
