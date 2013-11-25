<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/demo_cavex/'.'routes.php');
require_once($aRoutes['paths']['config'].'bs_crypt.php');
$crypt = new BSCrypt();
echo $crypt->GetCryptPassword('password', '1234567-8');
?>