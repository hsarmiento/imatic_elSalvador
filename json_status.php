<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'routes.php');
require_once($aRoutes['paths']['config'].'st_model.php');
$oModel = new STModel();
$query = "SELECT * FROM rms order by id desc limit 1;";
$aPrueba = $oModel->Select($query);


$arr = array(); 

$arr[] = array('value' => $aPrueba[0]['valor']/1);

echo json_encode($arr);

// $arr = array(); 

// $arr[] = array('value' => 4);


// echo json_encode($arr);


?>