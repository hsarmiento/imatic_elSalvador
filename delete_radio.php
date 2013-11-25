<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'routes.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'header.php');
// require_once($aRoutes['paths']['config'].'st_functions_generals.php');
require_once($aRoutes['paths']['config'].'bs_model.php');
$oLogin = new BSLogin();
$oLogin->IsLogged("admin");
$id = $_GET['radio_id'];
if(empty($id)){
	header('Location: radios.php');
}

$oModel = new BSModel();
$is_delete = $oModel->Destroy('radios',array('id' => $id));

if($is_delete===true){
	header('Location: radios.php');
	$query_event = "INSERT INTO eventos_alarmas(radio_id,tipo)values(".$id.",10);";
    $oModel->Select($query_event);
}else{
	header('Location: radios.php');
}


?>