<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/demo_cavex/'.'routes.php');
require_once($aRoutes['paths']['config'].'bs_functions_generals.php');
require_once($aRoutes['paths']['config'].'bs_model.php');

$oModel = new BSModel();
$query = "SELECT * FROM rms order by id desc limit 1;";
$aPrueba = $oModel->Select($query);
$suma = 0;

foreach ($aPrueba as $value) {
	$suma = $value['valor'];
}

$arr = array(); 

$arr[] = array('value' => $suma/1);

echo json_encode($arr);

// $oModel = new STModel();
// $query = "SELECT * FROM rms order by id desc limit 1;";
// $aPrueba = $oModel->Select($query);
// // print_r($aPrueba);

// $suma = 0;
// foreach ($aPrueba as $value) {
// 	$suma = $value['valor'];
// }


// $arr = array(); 

// $arr[] = array('value' => $suma);

// echo json_encode($arr);
?>
