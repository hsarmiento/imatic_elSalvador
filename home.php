<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'routes.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'header.php');
// require_once($aRoutes['paths']['config'].'st_functions_generals.php');
require_once($aRoutes['paths']['config'].'bs_model.php');
$oLogin = new BSLogin();
$oLogin->IsLogged("admin","supervisor");

$save_password = $_GET['save_password'];
$save_title = $_GET['save_title'];

// $query_reader = "SELECT * from estado_lector order by fecha_hora desc limit 1;";
// $oModel = new BSModel();
// $aReader= $oModel->Select($query_reader);
// if($aReader[0]['estado'] == 1){
// 	$class = 'btn-success reader-running';
// 	$text = 'Reader running';
// }elseif($aReader[0]['estado'] == 0){
// 	$class = 'btn-info reader-stop';
// 	$text = 'Reader stopped';
// }

?>

<link rel="stylesheet" href="<? echo $aRoutes['paths']['css']?>jquery-ui-1.10.3.custom.css">


<div class="container container-body">
	<?php if($save_password === 'true') { ?>
		<div class="alert alert-success msg-action" style="text-align:center;">
	    	Successful change password
	  	</div>
	<?php } ?>
	<?php if($save_title === 'true') { ?>
		<div class="alert alert-success msg-action" style="text-align:center;">
	    	Successful change title
	  	</div>
	<?php } ?>
	<div id="info-help" class="help">
		<br /> <br /> 
		<strong>
			I<br /> N<br />F<br />O<br />
		</strong>	    
	</div>
	<div class="row">
		<div class="span6"><div class="offset1"><img src="assets/img/bomba.png"></div></div>
		<div class="span6 menu-buttons">
			</br>
			</br>

			<?php if($_SESSION['usertype'] == 1){?>
				<!-- <a class="btn btn-default <?=$class?>" id="status-reader" href="#"><?=$text?></a> -->
				<a class="btn btn-default btn-info" id="status-reader" href="reboot_system.php" onclick="return confirm('Are you ABSOLUTELY sure?')">Reboot system</a>
			<?php } ?>
			<?php if($_SESSION['usertype'] == 1){?>
				<p>
				  <button class="btn btn-L btn-primary" type="button" data-toggle="collapse" data-target="#calibration_collapse">System calibration<b class="caret caret-body"></b></button>
					<div id="calibration_collapse" class="collapse" style="margin-bottom:10px">
						<?php foreach ($aGroup as $group) { ?>
							<button class="btn btn-sub btn-primary" type="button" onclick="window.location.href='/imatic_elSalvador/system_calibration.php?group=<?=$group["grupo"]?>'"><?=$group["grupo"]?></button>
						<?} ?>
				  	</div>	
				  <!-- <button class="btn btn-L btn-primary" type="button" onclick="window.location.href='/imatic_elSalvador/system_calibration.php'">System Calibration</button> -->
				</p>
					<button class="btn btn-L btn-primary" type="button" data-toggle="collapse" data-target="#radio_collapse">Radios<b class="caret caret-body"></b></button>
					<div id="radio_collapse" class="collapse" style="margin-bottom:10px">
					  	<button class="btn btn-sub btn-primary" type="button" onclick="window.location.href='/imatic_elSalvador/add_radio.php'">Add radio</button>
				  		<button class="btn btn-sub btn-primary" type="button" onclick="window.location.href='/imatic_elSalvador/radios.php'">View radios</button>
				  	</div>	
			<?php } ?>
			<p>
			  <button class="btn btn-L btn-primary" type="button" onclick="window.location.href='/imatic_elSalvador/general_overview.php'">General Overview</button>
			</p>
			<p>
			  <button class="btn btn-L btn-primary" type="button" onclick="window.location.href='/imatic_elSalvador/alarms_events.php'">Alarms & Events</button>
			</p>
			<?php if($_SESSION['usertype'] == 1){?>
				<p>
				  <button class="btn btn-L btn-primary" type="button" data-toggle="collapse" data-target="#user_collapse">User accounts<b class="caret caret-body"></b></button>
					<div id="user_collapse" class="collapse">
					  	<button class="btn btn-sub btn-primary" type="button" onclick="window.location.href='/imatic_elSalvador/create_user.php'">Create user</button>
					  	<button class="btn btn-sub btn-primary" type="button" onclick="window.location.href='/imatic_elSalvador/users.php'">View users</button>
				  	</div>
				</p>
			<?php } ?>
			</br>
		</div>
	</div>
</div>

<div id="dialog" title="Cavex info">
  <h4>Application</h4>
  <ul>
  	<li>
  		Name: <?=$title_cavex?>
  	</li>
  	<li>
  		Serial number: CCS10201300
  	</li>
  	<li>
  		Date: <?=date('F Y')?>
  	</li>
  	<li>
  		Date Tested: 2013/10/11
  	</li>
  	<li>
  		License: 2 Transmitter units maximum
  	</li>
  	<li>
  		Sensors: 2 Sensors maximum
  	</li>
  	<li>
  		IP: 192.168.0.3
  	</li>
  </ul>
  <h4>Contact</h4>
  <ul>
  	<li>
  		Name: Javier López
  	</li>
  	<li>
  		Phone: +56982321690
  	</li>
  	<li>
  		Email: jlopez@weirminerals.cl
  	</li>
  </ul>
</div>

<script type="text/javascript">
	$("#status-reader").click(function(){
		if($(this).attr('class') == 'btn btn-default btn-success reader-running'){
			$.ajax({
				url: 'update_reader_status.php?current_status=1',
				success: function(){
					$('#status-reader').removeClass("btn-success").removeClass("reader-running").addClass("btn-info").addClass("reader-stop");
					$('#status-reader').text("Reader stopped");
				}
			});
		}else if($(this).attr('class') == 'btn btn-default btn-info reader-stop'){
			$.ajax({
				url: 'update_reader_status.php?current_status=0',
				success: function(){
					$('#status-reader').removeClass("btn-info").removeClass("reader-stop").addClass("btn-success").addClass("reader-running");	
					$('#status-reader').text("Reader running");
				}
			});
		}
	});

	$("#info-help").click(function(){
		$( "#dialog" ).dialog({
	      width: 500,
	      modal: true,
	      show: {
		        effect: 'fade',
		        duration: 500
		    },
		  hide: {
		        effect: 'fade',
		        duration: 500
		    },
	      buttons: {
	        Close: function() {
	          $( this ).dialog( "close" );
	        }
	      }
	    });
	});
</script>


<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'footer.php');

?>
