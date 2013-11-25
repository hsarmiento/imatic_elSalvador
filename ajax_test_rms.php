<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'routes.php');
require_once($aRoutes['paths']['config'].'bs_functions_generals.php');
require_once($aRoutes['paths']['config'].'bs_model.php');

$radio_id = $_GET['radio_id'];

$oModel = new BSModel();
$query = "SELECT * FROM rms where radio_id = ".$radio_id." order by fecha_hora desc limit 1;";
$aPrueba = $oModel->Select($query);
$valor = 0;

foreach ($aPrueba as $value) {
	$valor = $value['valor'];
}

$arr = array(); 

$arr[] = array('value' => $valor/1);

echo json_encode($arr);


?>