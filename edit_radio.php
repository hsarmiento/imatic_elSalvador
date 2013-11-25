<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'routes.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'header.php');
// require_once($aRoutes['paths']['config'].'st_functions_generals.php');
require_once($aRoutes['paths']['config'].'bs_model.php');
$oLogin = new BSLogin();
$oLogin->IsLogged("admin");

$n_radio = $_GET['n_radio'];
$radio_id = $_GET['radio_id'];


if(empty($n_radio) || empty($radio_id)){
	header("Location: home.php");
}else{
	$form = $_POST;

	if($form['edit_radio'] == 'Save'){
		if(!empty($form['mac1']) && !empty($form['mac2']) && !empty($form['mac3']) && !empty($form['mac4'])){
			$MAC = $form['mac1'].$form['mac2'].$form['mac3'].$form['mac4'];
			$oRadio = new BSModel();
			$query_update_radio = "UPDATE radios set mac = '".$MAC."', identificador = '".$form['identifier']."' , grupo = '".$form['group']."' where id = ".$radio_id.";";
			$oRadio->Select($query_update_radio);
			header("Location: radios.php?update_radio=true");
		}
	}
	$oModel = new BSModel();
	$query_radio = "SELECT * from radios where id = ".$radio_id.";";
	$aRadio = $oModel->Select($query_radio);
}

?>


<div class="container container-body">
	<h2>Edit radio <?=$n_radio?></h2>
	<div class="calibration-radio span7 offset2">
		<div class="span6 form-div-radio">
			<form class="form-inline" name="edit_radio" id="edit_radio_form" method="post" action="edit_radio.php?n_radio=<?=$n_radio?>&radio_id=<?=$radio_id?>" enctype="multipart/form-data">
			 
				 <?php foreach ($aRadio as $radio) { ?>
				 	<p>	
				 	 	<label for="mac1"><strong>Mac address</strong></label>
					  	<input type="text" class="input-mini span1" id="mac1" name="mac1" maxlength="4" value="<?=substr($radio['mac'], 0,4)?>"><b>:</b>
					  	<input type="text" class="input-mini span1" id="mac2" name="mac2" maxlength="4" value="<?=substr($radio['mac'], 4,4)?>"><b>:</b>
					  	<input type="text" class="input-mini span1" id="mac3" name="mac3" maxlength="4" value="<?=substr($radio['mac'], 8,4)?>"><b>:</b>
					  	<input type="text" class="input-mini span1" id="mac4" name="mac4" maxlength="4" value="<?=substr($radio['mac'], 12,4)?>">
			 	 	</p>
			 	  	<p>
				  	  	<label for="identifier"><strong>Identifier</strong></label>	
				  	  	<input type="text" class="span2 required" id="identifier" name="identifier" title="Use letters or numbers" value="<?=$radio['identificador']?>">
				  	</p>
					<p>
					  	<label for="group"><strong>Group</strong></label>	
					  	<input type="text" class="span2 required" id="group" name="group" title="Use letters or numbers" value="<?=$radio['grupo']?>">
				 	</p> 
				 <?php }?>
			
			
	  			<input type="submit" class="btn btn-primary" id="save-radio" name="edit_radio" value="Save">
		 
			</form>
		</div>
	</div>	
</div>


<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'footer.php');

?>