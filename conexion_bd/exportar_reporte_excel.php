<?php
require_once('conexion.php');
require_once('../vendor/phpoffice/phpspreadsheet/src/Bootstrap.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="reporte.xlsx"');
header('Cache-Control: max-age=0');

$datos = json_decode(file_get_contents('php://input'), true);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Aquí va la lógica para obtener y mostrar los datos según el tipo de reporte
// ...

$writer = new Xlsx($spreadsheet);
$writer->save('php://output'); 