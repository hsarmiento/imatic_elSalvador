<?php 
require_once('/var/www/imatic_elSalvador/config/bs_model.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'routes.php');
// require_once($aRoutes['paths']['config'].'bs_model.php');
$user_id = $_SESSION['user_id'];


$oLogUsuario = new BSModel();
$query_log = "INSERT INTO log_usuarios(user_id, estado_online)VALUES(".$user_id.", 1);";
$oLogUsuario->Select($query_log);
$query_update = "UPDATE usuarios set estado_online = 1 where id = ".$user_id.";";
$oLogUsuario->Select($query_update);

?>