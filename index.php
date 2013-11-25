<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'routes.php');

require_once($aRoutes['paths']['config'].'bs_model.php');
require_once($aRoutes['paths']['config'].'bs_login.php');

if(!empty($_SESSION['username'])){
	header("Location: home.php");
}

$oLogin = new BSLogin();
$msg = $oLogin->Login();

$oModel = new BSModel();
$query_title = "SELECT * from titulo_cavex order by fecha_hora desc limit 1;";
$aTitle = $oModel->Select($query_title);
$title_cavex = 'Cavex';
if(!empty($aTitle)){
	$title_cavex = $aTitle[0]['texto'];
}
?>


<!DOCTYPE HTML>
<html lang="es">
	<head>	
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
		<title>Cavex control system</title>
		<link rel="stylesheet" href="<? echo $aRoutes['paths']['css']?>bootstrap.css">
		<link rel="stylesheet" href="<? echo $aRoutes['paths']['css']?>bootstrap_override.css">
		<script type="text/javascript" src="<? echo $aRoutes['paths']['js']?>jquery-1.9.1.js"></script>
		<script type="text/javascript" src="<? echo $aRoutes['paths']['js']?>jquery-ui-1.10.1.custom.min.js"></script>
	</head>
	<body>
		<header>
			<div id="nav-header">
			    <div id="bar-one"></div>
			    <div id="title-nav"><a href="/imatic_elSalvador">Cavex Control System</a></div>
			    <div id="bar-two"></div>
			    <div id="bar-three"></div>
			    <div id="enterprise-nav"><?=$title_cavex?></div>
			    <div id="date-nav"><?=date('F /d/Y')?></div>
			    <div class="logo_weir"><img src="assets/img/WeirMinerals.png"></div>
			</div>
			<div id="sub-nav">
				<div id="middle-sub-nav"></div>
			</div>
			<div class="menu">
			</div>	
		</header>

		<div class="container container-body">
			<?php if($msg == -1) { ?>
				<div class="alert alert-error alert-fixed" id="error">
			    	Invalid username or password
			  	</div>
			<?php } ?>
			<?php if($msg == -2) { ?>
				<div class="alert alert-error alert-fixed" id="error">
			    	System is full. Try later
			  	</div>
			<?php } ?>
			<div class="container-login">				
			  <div class="login">
			    <h1>Login to Cavex Control System</h1>
			    <form method="post" action="">
			      <p><input type="text" name="username" value="" placeholder="Username"></p>
			      <p><input type="password" name="password" value="" placeholder="Password"></p>
			      <input type="hidden" name="session_action" value="login" />
			      <p class="submit">
			      	<input type="submit" class="btn btn-primary" name="commit" value="Login">
			      </p>
			    </form>
			  </div>
			</div>
		</div>

<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'footer.php');

?>