<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'routes.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'header.php');
require_once($aRoutes['paths']['config'].'bs_model.php');
$oLogin = new BSLogin();
$oLogin->IsLogged("admin","supervisor");
$form = $_POST;

if(!empty($form['submit'])){
	$oUser = new BSModel();
	$query_rut = "SELECT rut, password from usuarios where id = ".$form['user_id'].";";
	$aUser = $oUser->Select($query_rut);
	$crypt = new BSCrypt();
	$current_pass = $crypt->GetCryptPassword($form['current_password'], $aUser[0]['rut']);
	if($current_pass == $aUser[0]['password']){
		$new_password = $crypt->GetCryptPassword($form['new_password'], $aUser[0]['rut']);
		$repeat_password = $crypt->GetCryptPassword($form['repeat_new_password'], $aUser[0]['rut']);
		if($new_password == $repeat_password){
			$query_update = "UPDATE usuarios set password = '".$new_password."' where id = ".$form['user_id'].";";
			$update = $oUser->Select($query_update);
			header("Location: home.php?save_password=true");
		}
	}else{
		header("Location: change_password.php?user_id=".$form['user_id']."&error=true");
	}
}

$user_id = $_GET['user_id'];
$error = $_GET['error'];
if(empty($error)){
	$error = 'false';
}


if((empty($user_id) || $user_id != $_SESSION['user_id']) && ($error = 'false')){
	header("Location: home.php");
}

?>

<div class="container container-body">
	<?php if($error === 'true') { ?>
		<div class="alert alert-error msg-action" style="text-align:center;">
	    	Incorrect current password
	  	</div>
	<?php } ?>
	<h2>Change password</h2>
	<hr>
	<form class="form-horizontal" id="change_password_form" method="post" action="change_password.php?user_id=<?=$user_id?>" enctype="multipart/form-data">
	  <div class="control-group offset3">
	    <label class="control-label" for="current_password">Current password</label>
	    <div class="controls">
	      <input type="password" id="current_password" name="current_password">
	    </div>
	  </div>
	  <div class="control-group offset3">
	    <label class="control-label" for="new_password">New password</label>
	    <div class="controls">
	      <input type="password" id="new_password" name="new_password">
	    </div>
	  </div>
	  <div class="control-group offset3">
	    <label class="control-label" for="repeat_new_password">Repeat new password</label>
	    <div class="controls">
	      <input type="password" id="repeat_new_password" name="repeat_new_password">
	    </div>
	  </div>
	  <input type="hidden" value="<?=$user_id?>" name="user_id">
	  <div class="control-group offset3">
		<div class="controls">
			<input type="submit" class="btn btn-primary " name="submit" value="Save password" id="save_password">
		</div>
	  </div>

	</form>
</div>

<script type="text/javascript">
	$("#current_password, #new_password, #repeat_new_password").blur(function(){
		$(this).valid();
	});	

	$('#new_password').tooltip({'trigger':'hover', 'title': 'Min 5 characters', 'placement':'right'});

	$('#change_password_form').validate({
			rules:{
				current_password:{
					required:true
				},
				new_password:{
					required:true,
					minlength: 5
				},
				repeat_new_password:{
					required: true,
					equalTo: "#new_password"
				}
			},
  		errorElement: "div",
        wrapper: "div",  // a wrapper around the error message
        errorPlacement: function(error, element) {
            offset = element.offset();
            error.insertBefore(element)
            error.addClass('error_wrapper');  // add a class to the wrapper
            error.css('position', 'absolute');
            error.css('left', offset.left + element.outerWidth());
            error.css('top', offset.top);
        }

	});


</script>
