<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'routes.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'header.php');
// require_once($aRoutes['paths']['config'].'st_functions_generals.php');
require_once($aRoutes['paths']['config'].'bs_model.php');

$oLogin = new BSLogin();
$oLogin->IsLogged("admin");

$oModel = new BSModel();
$query_radios = "SELECT radios.id as radio_id, radios.mac as mac, radios.estado as estado, bateria_radio.conectada as bateria_conectada, bateria_radio.conectada as estado_bateria, bateria_radio.carga as carga, radios.identificador as identificador, radios.grupo as grupo from radios left join bateria_radio on radios.id = bateria_radio.radio_id order by radios.id asc;";
$aRadios = $oModel->Select($query_radios);

$is_save = $_GET['save_radio'];
$is_update = $_GET['update_radio'];

?>



<div class="container container-body">
	<?php if($is_save == 'true'){ ?>
		<div class="alert alert-success msg-action" id="success">
		    Saved radio
		</div>
	<?php } ?>
	<?php if($is_update == 'true'){ ?>
		<div class="alert alert-success msg-action" id="success">
		    Updated radio
		</div>
	<?php } ?>
	<h2>Radios</h2>
	<div class="row">
  		<table class="table table-hover table-bordered span9 center-table">
			<thead>
				<tr>
				  <th>Identifier</th>
			      <th>MAC address</th>
			      <th>Status</th>
			      <th>Battery</th>
			      <th>Battery Charge</th>
			      <th>Group</th>
			      <th>Actions</th>
			    </tr>
			</thead>
			<tbody>
				<?php $i = 1;?>
				<?php foreach ($aRadios as $radio) { ?>
					<tr>
					  <td><?=$radio['identificador']?></td>
				      <td><?=$radio['mac']?></td>
				      <?php if($radio['estado'] == 0){ ?>
							<td><span style="color:red;">Disconnected</span></td>
				      <?php } elseif ($radio['estado'] == 1) { ?>
				      		<td><span style="color:green">Connected</span></td>
				      <?php }elseif ($radio['estado'] == -1) { ?>	       
				      		<td><span style="color:blue">Please add new radio</span></td>
				      <?php }?>
				      <td>
				      	<?php
				      		if($radio['estado'] == 1){ ?>	
								<?php 
				      			if($radio['bateria_conectada'] == 1){ ?>
		      						<span style="color:green;">Connected</span>
				      			<?php }
				      			elseif($radio['bateria_conectada'] == 0){ ?>
									<span style="color:red;">Disconnected</span>
				      			<?php }
				      		}	 ?>	
			      		</td>
			      		<td>
			      			<?php if($radio['estado'] == 1){ 
				      			if(!empty($radio['bateria_conectada'])){ 
				      					echo $radio['carga'].'%';
			      					 }	
					      		}?>
				      	</td>
				      	<td>
				      		<?=$radio['grupo']?>
				      	</td>	
				      	<td>
				      		<div class="edit_div">
				      			<a href="edit_radio.php?radio_id=<?=$radio['radio_id']?>&n_radio=<?=$i?>">
									<img src="assets/img/Text-Edit-icon.png" alt="edit user" width="25" height="25" title="Edit radio">
								</a>
				      		</div>					
							<a href="delete_radio.php?radio_id=<?=$radio['radio_id']?>" onclick="return confirm('Are you ABSOLUTELY sure?')"><img src="assets/img/DeleteRed.png" alt="delete user" width="25" height="25" title="Delete radio"></a>
			      	  	</td>
				    </tr>
				    <?php $i = $i + 1;?>
				<?php } ?>
		  </tbody>
		</table>
	</div>
</div>