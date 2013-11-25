<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/demo_cavex/'.'routes.php');
require_once($aRoutes['paths']['config'].'st_functions_generals.php');
require_once($aRoutes['paths']['config'].'st_model.php');

$oModel = new STModel();
$query = "SELECT count(*) as total FROM prueba;";
$aPrueba = $oModel->Select($query);

if ($aPrueba[0]['total'] % 2 == 0){ ?>
	<span style="color:red"><?php echo $aPrueba[0]['total'];?></span>
<?php }else { ?>
	<span style="color:blue"><?php echo $aPrueba[0]['total'];?></span>
<?php 
}

?>