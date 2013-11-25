<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'routes.php');
require_once($aRoutes['paths']['config'].'bs_functions_generals.php');
require_once($aRoutes['paths']['config'].'bs_model.php');

$radio_id = $_GET['radio_id'];

$oModel = new BSModel();
$query = "SELECT rms.valor, rms.fecha_hora FROM rms left join radios on rms.radio_id = radios.id where rms.radio_id = ".$radio_id." and radios.estado = 1 order by rms.fecha_hora desc limit 1;";
// echo $query;
$aRms = $oModel->Select($query);
if(empty($aRms)){
	$valor = -1;
	// $query_update = "UPDATE radios set estado = 0 where id = ".$radio_id.";";
	// $oModel->Select($query_update);
}else{
	$query_update = "UPDATE radios set estado = 1 where id = ".$radio_id.";";
	$oModel->Select($query_update);
	foreach ($aRms as $rms) {
		$valor= $rms['valor'];
	}	
}

$arr = array(); 

$arr[] = array('value' => $valor/1);

echo json_encode($arr);
// echo date("Y-m-d H:i:s");
?>