<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'routes.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'header.php');
require_once($aRoutes['paths']['config'].'bs_model.php');
require_once($aRoutes['paths']['config'].'bs_crypt.php');
$oLogin = new BSLogin();
$oLogin->IsLogged("admin");
$is_save = 0;
$form = $_POST;

if(!empty($form)){
	$oUser = new BSModel();
	$crypt = new BSCrypt();
	$pass = $crypt->GetCryptPassword($form['password'], $form['rut']);
	$query_new_user = "INSERT INTO usuarios(username,password,nombres,apellidos,email,telefono,rut,permisos,cargo)VALUES('".$form['username']."','".$pass."','".$form['name']."','".$form['last_name']."', '".$form['email']."', '".$form['phone']."', '".$form['rut']."', 0, '".$form['charge']."');";
	$oUser->Select($query_new_user);
  $query_event = "INSERT INTO eventos_alarmas(tipo)values(5);";
  $oUser->Select($query_event);
	header("Location: users.php?save_user=true");
}

?>

<div class="container container-body">
	<h2>Create user</h2>
	<hr>
	<form class="form-horizontal" id="create_user_form" method="post" action="create_user.php" enctype="multipart/form-data">
	  <div class="control">    
	    <div class="controls controls-row">
	    	<label class="control-label" for="username">Username</label>
      	<input type="text" class="span2" name="username" id="username">
      	<label class="control-label right-label" for="password">Password</label>
      	<input type="password" class="span2" id="password" name="password">
	    </div>
	  </div>
	  <div class="control">   
	    <div class="controls controls-row">    	
	      	<label class="control-label" for="name">Name</label>
	      	<input type="text" class="span2" id="name" name="name">
	      	<label class="control-label right-label" for="last_name">Last name</label>
	      	<input type="text" class="span2 right-label" id="last_name" name="last_name">
	    </div>
	  </div>
	  <div class="control">   
	    <div class="controls controls-row">    	
	      	<label class="control-label" for="email">Email</label>
	      	<input type="text" class="span2" id="email" name="email">
	      	<label class="control-label right-label" for="phone">Phone</label>
	      	<input type="text" id="phone" class="span2" name="phone">
	    </div>
	  </div>
	  <div class="control">   
	    <div class="controls controls-row">    	
	      	<label class="control-label" for="rut">Rut</label>
	      	<input type="text" id="rut" class="span2" name="rut">
	      	<label class="control-label right-label" for="charge">Charge</label>
	      	<input type="text" id="charge" class="span2" name="charge">
	    </div>
	  </div>

	  <div class="save-user">
  		<input type="submit" class="btn btn-primary" value="Save user">	
	  </div>
	  
	</form>

</div>
<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'footer.php');

?>

<script type="text/javascript">
	$("#username, #password, #name, #last_name, #email, #phone, #rut, #charge").blur(function(){
		$(this).valid();
	});

	$('#create_user_form').validate({
  		rules: {
  			username : {
  				required: true
  			},
  			password : {
  				required: true,
  				minlength: 5
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
  			password: {
  				required: "Required field",
  				minlength: "5 characters min"
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