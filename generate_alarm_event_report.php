<?php
require('inc/fpdf.php');
require_once(dirname(__FILE__).'/config/bs_model.php');
$filter_all = $_GET['filter_all'];
$filter_hyd = $_GET['filter_hyd'];
$filter_rad = $_GET['filter_rad'];
$filter_user = $_GET['filter_user'];
$filter_sys = $_GET['filter_sys'];
$from_date = $_GET['from_date'];
$to_date = $_GET['to_date'];

if($filter_all == 'on' || (empty($filter_all) && empty($filter_hyd) && empty($filter_rad) && empty($filter_user) && empty($filter_sys))){
		if(!empty($from_date) and !empty($to_date)){
			$query = "SELECT radios.id as radio_id, radios.identificador as identificador,radios.mac, eventos_alarmas.tipo as tipo, eventos_alarmas.fecha_hora as fecha_hora from eventos_alarmas left join radios on eventos_alarmas.radio_id = radios.id where fecha_hora >= date_sub('".$from_date."', interval 1 day) and fecha_hora <= date_add('".$to_date."', interval 1 day) order by fecha_hora desc;";
		}else{
			$query = "SELECT radios.id as radio_id, radios.identificador as identificador,radios.mac, eventos_alarmas.tipo as tipo, eventos_alarmas.fecha_hora as fecha_hora from eventos_alarmas left join radios on eventos_alarmas.radio_id = radios.id order by fecha_hora desc;";
		}
		$oModel = new BSModel();
		$aEvents = $oModel->Select($query);
	}else{
		if(!empty($from_date) and !empty($to_date)){
			$query = "SELECT radios.id as radio_id, radios.identificador as identificador,radios.mac, eventos_alarmas.tipo as tipo, eventos_alarmas.fecha_hora as fecha_hora from eventos_alarmas left join radios on eventos_alarmas.radio_id = radios.id where fecha_hora >= date_sub('".$from_date."', interval 1 day) and fecha_hora <= date_add('".$to_date."', interval 1 day) and ( ";
		}else{
			$query = "SELECT radios.id as radio_id, radios.identificador as identificador,radios.mac, eventos_alarmas.tipo as tipo, eventos_alarmas.fecha_hora as fecha_hora from eventos_alarmas left join radios on eventos_alarmas.radio_id = radios.id where ";
		}

		$checked_all = "";
		$len_query = strlen($query);
		if($filter_hyd == 'on' && $len_query == strlen($query)){
			$status_hyd = 'tipo = 2 or tipo = 3 or tipo = 4';
			$query = $query.$status_hyd;
			$checked_hyd = "checked = 'checked'";
		}
		if($filter_rad == 'on'){
			$status_radio = 'tipo = 1 or tipo = 8 or tipo = 9 or tipo = 10';
			if($len_query == strlen($query)){
				$query = $query.$status_radio;
			}else{
				$query = $query.' or '.$status_radio;
			}
			$checked_rad = "checked = 'checked'";		
		}
		if($filter_user == 'on'){
			$status_user = 'tipo = 5';
			if($len_query == strlen($query)){
				$query = $query.$status_user;
			}else{
				$query = $query.' or '.$status_user;
			}
			$checked_user = "checked = 'checked'";
		}
		if($filter_sys == 'on'){
			$status_sys = 'tipo = 6 or tipo = 7 or tipo = 11';
			if($len_query == strlen($query)){
				$query = $query.$status_sys;
			}else{
				$query = $query.' or '.$status_sys;
			}
			$checked_sys = "checked = 'checked'";
		}
		
		if($len_query < strlen($query)){
			if(!empty($from_date) and !empty($to_date)){
				$query = $query.') order by fecha_hora desc;';
			}else{
				$query = $query.' order by fecha_hora desc;';
			}		
			$oModel = new BSModel();
			$aEvents = $oModel->Select($query);
		}
	}

// $oModel = new BSModel();
// $query_rms_report = "SELECT * from rms order by fecha_hora desc limit 10";
// $aRmsReport = $oModel->Select($query_rms_report);

$pdf = new FPDF();

$pdf->SetFont('Arial','B',16);
// Movernos a la derecha
$pdf->AddPage();
$pdf->Cell(80);
// Título
if(empty($to_date) && empty($from_date)){
	$date = 'Historical';
}else{
	$date = str_replace('-','/',$from_date).' - '.str_replace('-','/',$to_date);
}

$pdf->Cell(30,10,'Alarm & Event report:  '.$date,0,0,'C');
// Salto de línea
$pdf->Ln(20);

$pdf->SetFont('Arial','B',14);
$header = array('Events','Datetime');
foreach($header as $col){
	if($col == 'Events'){
		$pdf->Cell(130,7,$col,1);
	}elseif($col == 'Datetime'){
		$pdf->Cell(50,7,$col,1);
	}
	
}
$pdf->Ln();

if(count($aEvents) < 1){
	$pdf->SetFont('Arial','B',14);
	$pdf->Ln();
	$pdf->Cell(130,7,'No data available',0);
}else{
	$pdf->SetFont('Arial','',14);
	foreach ($aEvents as $value) {

  	if($value['tipo'] == 1){
			$text = 'Detected new radio (Identifier: '.$value['identificador'].')';
  	}elseif($value['tipo'] == 2){
			$text = 'Hydrocyclon (Identifier: '.$value['identificador'].') is ropping';
  	}elseif($value['tipo'] == 3){
  		$text = 'Hydrocyclon (Identifier: '.$value['identificador'].') is ideal';
  	}elseif($value['tipo'] == 4){
  		$text = 'Hydrocyclon (Identifier: '.$value['identificador'].') is semiropping';
  	}elseif($value['tipo'] == 5){
  		$text = 'New user added';
  	}elseif($value['tipo'] == 6){
  		$text = 'System calibration saved';
  	}elseif($value['tipo'] == 7){
  		$text = 'RMS chart calibration saved';
  	}elseif($value['tipo'] == 8){
  		if(!empty($value['radio_id'])){
  			$text = 'New radio (Identifier: '.$value['identificador'].') added';
  		}else{
  			$text = 'New radio added';
  		}					  		
  	}elseif($value['tipo'] == 9){
  		$text = 'Radio (Identifier: '.$value['identificador'].') disconnected';
  	}elseif($value['tipo'] == 10){
  		$text = 'Radio removed';
  	}elseif($value['tipo'] == 11){
  		$text = 'SD chart calibration saved';
  	}
						      	
 	$pdf->Cell(130,7,$text,1);
 	$pdf->Cell(50,7,str_replace('-','/',$value['fecha_hora']),1);
 	$pdf->Ln();
}	
}

$pdf->Ln();
$pdf->Output();
?>