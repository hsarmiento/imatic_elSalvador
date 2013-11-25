<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'routes.php');
require_once($aRoutes['paths']['config'].'bs_functions_generals.php');
require_once($aRoutes['paths']['config'].'bs_model.php');

$radio_id = $_GET['radio_id'];
$oModel = new BSModel();
$query = "SELECT desviacion_standard.valor, desviacion_standard.fecha_hora FROM desviacion_standard left join radios on desviacion_standard.radio_id = radios.id where desviacion_standard.radio_id = ".$radio_id." and radios.estado = 1 order by desviacion_standard.fecha_hora desc limit 1;";
// echo $query;
$aSD = $oModel->Select($query);
if(empty($aSD)){
	$valor = -1;
	// $query_update = "UPDATE radios set estado = 0 where id = ".$radio_id.";";
	// $oModel->Select($query_update);
}else{
	$query_update = "UPDATE radios set estado = 1 where id = ".$radio_id.";";
	$oModel->Select($query_update);
	foreach ($aSD as $sd) {
		$valor= $sd['valor'];
	}	
}

$arr = array(); 

$arr[] = array('value' => $valor/1);

echo json_encode($arr);



// $oModel = new BSModel();
// $query = "SELECT * FROM desviacion_standard where radio_id = ".$radio_id." order by fecha_hora desc limit 1;";
// $aSD = $oModel->Select($query);
// $valor = 0;

// foreach ($aSD as $sd) {
// 	$valor= $sd['valor'];
// }

// $arr = array(); 

// $arr[] = array('value' => $valor/1.0);

// echo json_encode($arr);

?>