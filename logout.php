<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'routes.php');
require_once($aRoutes['paths']['config'].'bs_login.php');

$oLogin = new BSLogin();
session_start();
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'log_leave_website.php');
$oLogin->Logout();
header('Location: index.php');
?>