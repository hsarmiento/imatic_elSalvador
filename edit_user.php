<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'routes.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'header.php');
// require_once($aRoutes['paths']['config'].'st_functions_generals.php');
require_once($aRoutes['paths']['config'].'bs_model.php');
require_once($aRoutes['paths']['config'].'bs_crypt.php');
$oLogin = new BSLogin();
$oLogin->IsLogged("admin");

$is_update = 0;

$form = $_POST;

if(!empty($form)){
  $oModel = new BSModel();
  $password = $form['password'];
  if(empty($password)){
    $query = "SELECT password FROM usuarios where id = ".$form['user_id']." and permisos = 0;";
    $aUserPassword = $oModel->Select($query);
    $password = $aUserPassword[0]['password'];
  }else{
    $crypt = new BSCrypt();
    $password = $crypt->GetCryptPassword($form['password'], $form['rut']);
  }

  $aAttributes = array(
  'username' => $form['username'],
  'password' => $password,
  'nombres' => $form['name'],
  'apellidos' => $form['last_name'],
  'email' => $form['email'],
  'telefono' => $form['phone'],
  'rut' => $form['rut'],
  'permisos' => 0,
  'cargo' => $form['charge']
  );

  $is_update = $oModel->Update('usuarios', $aAttributes, array('id' => $form['user_id']));
  if($is_update){
    header("Location: users.php?update_user=true");
  }
}

$user_id = $_GET['user_id'];
$oModel = new BSModel();
$query = "SELECT id,username,nombres,apellidos,telefono, cargo, email, rut FROM usuarios where permisos = 0 and id = ".$user_id.";";
$aUser = $oModel->Select($query);

?>


<div class="container container-body">
	<h2>Edit user "<?=$aUser[0]['username']?>"</h2>
	<hr>
	<form class="form-horizontal" id="edit_user_form" method="post" action="edit_user.php" enctype="multipart/form-data">
	  <input type="hidden" name="user_id" value="<?=$aUser[0]['id']?>">
	  <div class="control">    
	    <div class="controls controls-row">
	    	<label class="control-label" for="username">Username</label>
	      	<input type="text" class="span2" name="username" id="username" value="<?=$aUser[0]['username']?>">
	      	<label class="control-label right-label" for="password">Password</label>
	      	<input type="password" class="span2" id="password" name="password">
	    </div>
	  </div>
	  <div class="control">   
	    <div class="controls controls-row">    	
	      	<label class="control-label" for="name">Name</label>
	      	<input type="text" class="span2" id="name" name="name" value="<?=$aUser[0]['nombres']?>">
	      	<label class="control-label right-label" for="last_name">Last name</label>
	      	<input type="text" class="span2 right-label" id="last_name" name="last_name" value="<?=$aUser[0]['apellidos']?>">
	    </div>
	  </div>
	  <div class="control">   
	    <div class="controls controls-row">    	
	      	<label class="control-label" for="email">Email</label>
	      	<input type="text" class="span2" id="email" name="email" value="<?=$aUser[0]['email']?>">
	      	<label class="control-label right-label" for="phone">Phone</label>
	      	<input type="text" id="phone" class="span2" name="phone" value="<?=$aUser[0]['telefono']?>">
	    </div>
	  </div>
	  <div class="control">   
	    <div class="controls controls-row">    	
	      	<label class="control-label" for="rut">Rut</label>
	      	<input type="text" id="rut" class="span2" name="rut" value="<?=$aUser[0]['rut']?>">
	      	<label class="control-label right-label" for="charge">Charge</label>
	      	<input type="text" id="charge" class="span2" name="charge" value="<?=$aUser[0]['cargo']?>">
	    </div>
	  </div>

	  <div class="save-user">
  		<input type="submit" class="btn btn-primary" value="Save">	
	  </div>
	  
	</form>

</div>

<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'footer.php');

?>

<script type="text/javascript">
	$("#username, #name, #last_name, #email, #phone, #rut, #charge").blur(function(){
		$(this).valid();
	});

	$('#edit_user_form').submit(function()
	{	
		if(!$("#password").val()){
			if(confirm("If password is empty, the last password will keep. Do you sure?")){
        return true;
      }else{
        return false;
      }
		}
	});

	$('#edit_user_form').validate({
  		rules: {
  			username : {
  				required: true
  			},
  			name: {
  				required :true
  			},
  			last_name : {
  				required: true
  			},
  			email : {
  				required: true,
  				email: true
  			},
  			phone : {
  				required: true
  			},
  			rut: {
  				required: true
  			},
  			charge: {
  				required: true
  			}
  		},
  		messages : {
  			username: {
  				required: "Required field"
  			},
  			name: {
  				required: "Required field"
  			},
  			last_name: {
  				required: "Required field"
  			},
  			email: {
  				required: "Required field",
  				email: "Invalid email"
  			},
  			phone : {
  				required: "Required field"
  			},
  			rut: {
  				required: "Required field"
  			},
  			charge:{
  				required: "Required field"
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