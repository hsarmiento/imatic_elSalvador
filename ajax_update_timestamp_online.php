<?php 
require_once('/var/www/imatic_elSalvador/config/bs_model.php');
$user_id = $_GET['user_id'];

$oLogUsuario = new BSModel();
$query_update = "UPDATE usuarios set ultimo_acceso = '".date('Y-m-d H:i:s')."' where id = ".$user_id.";";
$oLogUsuario->Select($query_update);

?>