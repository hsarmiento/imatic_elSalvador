<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'routes.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'header.php');
require_once($aRoutes['paths']['config'].'bs_model.php');
$oLogin = new BSLogin();
$oLogin->IsLogged("admin");
$is_save = false;

$form = $_POST;
if(!empty($form['save_radio'])){
	if(!empty($form['mac1']) && !empty($form['mac2']) && !empty($form['mac3']) && !empty($form['mac4'])){
		$MAC = $form['mac1'].$form['mac2'].$form['mac3'].$form['mac4'];
		$oRadio = new BSModel();
		if($form['insert_update'] == 'update'){
			$query_new_radio = "UPDATE radios set estado = 1, identificador = '".$form['identifier']."', grupo = '".$form['group']."' where mac = '".$MAC."';";
			$oRadio->Select($query_new_radio);
		}else{
			$query_new_radio = "INSERT INTO radios(mac,identificador,estado,grupo)values('".$MAC."', '".$form['identifier']."',0, '".$form['group']."');";
			$oRadio->Select($query_new_radio);	
		}
		$query_radio = "SELECT * from radios where mac = '".$MAC."';";
		$aRadio = $oRadio->Select($query_radio);
		$oModel = new BSModel();
		$query_event = "INSERT INTO eventos_alarmas(radio_id,tipo)values(".$aRadio[0]['id'].",8);";
	    $oModel->Select($query_event);
		header("Location: radios.php?save_radio=true");
	}			
}

$oRadio = new BSModel();
$query_empty_radios = "SELECT * from radios where estado = -1;";
$aRadiosEmpty = $oRadio->Select($query_empty_radios);
$query_radios = "SELECT count(*) as count from radios where estado = 1 or estado = 0;";
$aRadios = $oRadio->Select($query_radios);


?>

<div class="container container-body">
	<?php if(count($aRadiosEmpty) > 0 && $aRadios[0]['count'] < 12){ ?>
		<div id="" class="alert alert-warning" style="text-align:center">
			<span style="font-size:18px;"><strong><?=count($aRadiosEmpty)?> new radios detected</strong></span>
					</br></br>
			<?php foreach ($aRadiosEmpty as $radio) { ?>
				<ul>
					<li>MAC: <strong><?=substr($radio['mac'], 0,4).' : '.substr($radio['mac'], 4,4).' : '.substr($radio['mac'], 8,4).' : '.substr($radio['mac'], 12,4)?></strong></li>
				</ul>
		<?php } ?>
		</div>
	<?php } ?>
	<h2>Add radio</h2>
	<?php if($aRadios[0]['count'] < 12){ ?>
		<div class="calibration-radio span7 offset2">
			<div class="span6 form-div-radio">
				<form class="form-inline" id="add_radio_form" method="post" action="add_radio.php" enctype="multipart/form-data">
				  <?php if(count($aRadiosEmpty) > 0){ ?>
				  	<input type="hidden" name="insert_update" value="update">
				  <?php } ?>
				  <p>
				  	  <label for="mac1"><strong>Mac address</strong></label>
					  <input type="text" class="input-mini span1 required" id="mac1" name="mac1" maxlength="4"><b>:</b>
					  <input type="text" class="input-mini span1 required" id="mac2" name="mac2" maxlength="4"><b>:</b>
					  <input type="text" class="input-mini span1 required" id="mac3" name="mac3" maxlength="4"><b>:</b>
					  <input type="text" class="input-mini span1 required" id="mac4" name="mac4" maxlength="4">
				  </p>
				  <p>
				  	  <label for="identifier"><strong>Identifier</strong></label>	
				  	  <input type="text" class="span2 required" id="identifier" name="identifier" title="Use letters or numbers">
				  </p>
				  <p>
				  	  <label for="group"><strong>Group</strong></label>	
				  	  <input type="text" class="span2 required" id="group" name="group" title="Use letters or numbers">
				  </p>
				  
				  <input type="submit" class="btn btn-primary" id="save-radio" name="save_radio" value="Save">
				  
				</form>
			</div>
		</div>	
	<?php }else{ ?>
		<div id="limit_radios" class="alert alert-warning" >
			You have 12 radios, to add a new radio. Please contact your provider.
		</div>
	<?php } ?>
</div>

<script type="text/javascript">
  $(function() {
	    $("#identifier").tooltip({
	      track: true
	    });
  });

  $("#mac1, #mac2, #mac3, #mac4, #identifier").blur(function(){
		$(this).valid();
	});

  $('#add_radio_form').validate({
  		invalidHandler: function(form){
				alert('Red inputs are empty'); // for demo
            	return false; // for demo
			},
	        highlight: function(element, errorClass, validClass) {
			    $(element).addClass(errorClass).removeClass(validClass);
			  },
			 unhighlight: function(element, errorClass, validClass) {
			    $(element).removeClass(errorClass).addClass(validClass);
			  },
			  errorPlacement: function(error, element) {      
        	}
  });
</script>


<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'footer.php');

?>