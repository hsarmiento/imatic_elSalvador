<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'routes.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'header.php');
require_once($aRoutes['paths']['config'].'bs_model.php');
$oLogin = new BSLogin();
$oLogin->IsLogged("admin");

$form = $_POST;
if(!empty($form['save_title'])){
	if(!empty($form['new_title'])){
		$oTitle = new BSModel();
		$query_new_title = "INSERT INTO titulo_cavex(texto)values('".$form['new_title']."')";
		$oTitle->Select($query_new_title);
		header("Location: home.php?save_title=true ");
	}			
}

?>

<div class="container container-body">
	<h2>Change title</h2>
	<div class="calibration-radio span7 offset2">
			<div class="span6 form-div-radio">
				<form class="form-inline" id="change_title_form" method="post" action="change_title.php" enctype="multipart/form-data">
					<label for="new_title" id="label_change_title"><strong>New title</strong></label>
					<input type="text" class="required" id="new_title" name="new_title">
					<input type="submit" class="btn btn-primary" id="save_title" name="save_title" value="Save">
				</form>

			</div>
	</div>
</div>



<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'footer.php');

?>

<script type="text/javascript">
  $("#new_title").blur(function(){
		$(this).valid();
	});

  $('#change_title_form').validate({
  		invalidHandler: function(form){
				alert('Title are empty'); // for demo
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