<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'routes.php');
require_once($aRoutes['paths']['config'].'bs_model.php');

$current_status = $_GET['current_status'];
if($current_status == '1'){
	$query_status = 'insert into estado_lector(estado)values(0);';
	$oModel = new BSModel();
	$oModel->Select($query_status);	

}elseif($current_status == '0') {
	$query_status = 'insert into estado_lector(estado)values(1);';
	$oModel = new BSModel();
	$oModel->Select($query_status);	
	$out =  shell_exec('./prueba /dev/ttyUSB0');
	echo $out;
}

?>