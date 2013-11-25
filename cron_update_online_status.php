<?php 
// require_once('/var/www'.'/demo_cavex/'.'routes.php');
// require_once('/var/www'.'/demo_cavex/'.'header.php');
// require_once($aRoutes['paths']['config'].'st_functions_generals.php');
require_once('/var/www/imatic_elSalvador/config/bs_model.php');



$oUpdate = new BSModel();
$query_update= "UPDATE usuarios set estado_online = 0 where estado_online = 1 and TIMESTAMPDIFF(SECOND, ultimo_acceso, '".date('Y-m-d H:i:s')."') > 60;";
$oUpdate->Select($query_update);


?>