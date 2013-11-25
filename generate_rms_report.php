<?php

require_once(dirname(__FILE__).'/config/bs_model.php');
require_once 'inc/PHPExcel.php';

$oModel = new BSModel();
$from_datetime = $_GET['from_datetime'];
$to_datetime = $_GET['to_datetime'];
$query_rms_report = "SELECT t1.valor, t2.identificador, t1.fecha_hora from rms as t1 join radios as t2 on t1.radio_id = t2.id where t1.fecha_hora < '".$from_datetime."' and t1.fecha_hora > '".$to_datetime."' order by t1.fecha_hora desc limit 100000";
$aRmsReport = $oModel->Select($query_rms_report);
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Cavex")
                 ->setTitle("Rms report")
                 ->setCategory("");
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'From:'.$from_datetime);
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'To:'.$to_datetime);

if(empty($aRmsReport)){
	$objPHPExcel->getActiveSheet()->setCellValue('A3', 'No data available');
}else{
	$objPHPExcel->getActiveSheet()->setCellValue('A3', 'Radio Identifier');
	$objPHPExcel->getActiveSheet()->setCellValue('B3', 'Rms value');
	$objPHPExcel->getActiveSheet()->setCellValue('C3', 'Datetime');
	$i = 4;
	foreach ($aRmsReport as $report) {
	    $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $report['identificador']);
	    $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $report['valor']);
	    $objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $report['fecha_hora']);
	    $i = $i + 1;
	}	
}

$objPHPExcel->getActiveSheet()->setTitle('Simple');
$objPHPExcel->setActiveSheetIndex(0);
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="report_'.$from_datetime.'_to_'.$to_datetime.'.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;


?>