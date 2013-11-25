<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'routes.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'header.php');
require_once($aRoutes['paths']['config'].'bs_model.php');
$oLogin = new BSLogin();
$oLogin->IsLogged("admin");

$oModel = new BSModel();
$query = "SELECT id,username,nombres,apellidos,telefono, cargo, estado_online FROM usuarios where permisos = 0 order by nombres asc;";
$aUsers = $oModel->Select($query);

if($_GET['save_user'] == 'true'){
	$is_save = true;
}

if($_GET['update_user'] == 'true'){
	$is_update = true;
}

?>


<div class="container container-body">
	<?php if($is_save === true) { ?>
		<div class="alert alert-success msg-action" >
	    	Saved user
	  	</div>
	<?php } ?>
	<?php if($is_update === true) { ?>
		<div class="alert alert-success msg-action" >
	    	Updated user
	  	</div>
	<?php } ?>
	<h2>Users Management</h2>
	<div class="row">
  		<table class="table table-hover table-bordered span9 center-table">
			<thead>
				<tr>
			      <th>User</th>
			      <th>Name</th>
			      <th>Last name</th>
			      <th>Phone</th>
			      <th>Charge</th>
			      <th>Status</th>
			      <th>Actions</th>
			    </tr>
			</thead>
			<tbody>
				<?php foreach ($aUsers as $value) { ?>
					<tr>
				      <td><?=$value['username']?></td>
				      <td><?=$value['nombres']?></td>
				      <td><?=$value['apellidos']?></td>
				      <td><?=$value['telefono']?></td>
				      <td><?=$value['cargo']?></td>
				      <?php if($value['estado_online'] == 0){ ?>
							<td><span style="color:red;">Offline</span></td>

				      <?php } elseif ($value['estado_online'] == 1) { ?>
				      		<td><span style="color:green">Online</span></td>
				      <?php }?>
				      <td>
				      		<div class="edit_div">
				      			<a href="edit_user.php?user_id=<?=$value['id']?>">
									<img src="assets/img/Text-Edit-icon.png" alt="edit user" width="25" height="25" title="edit user">
								</a>
				      		</div>					
							<a href="delete_user.php?user_id=<?=$value['id']?>"  onclick="return confirm('Are you ABSOLUTELY sure?')"><img src="assets/img/DeleteRed.png" alt="delete user" width="25" height="25" title="delete user"></a>						
			      	  </td>
				    </tr>
				<?php } ?>
		  </tbody>
		</table>
	</div>
</div>

<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'footer.php');

?>
