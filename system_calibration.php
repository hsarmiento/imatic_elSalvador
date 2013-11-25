<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'routes.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'header.php');
require_once($aRoutes['paths']['config'].'bs_model.php');
$oLogin = new BSLogin();
$oLogin->IsLogged("admin");
$save = false;
$form = $_POST['radio'];
$group = $_GET['group'];
if(!empty($_POST['save'])){
	$oParametros = new BSModel();
	foreach ($form as $value) {
		$query_save = "INSERT INTO parametros(rms_normal,rms_max_normal_porcentaje,rms_ropping_porcentaje, sd_normal, sd_max_normal_porcentaje, sd_ropping_porcentaje,radio_id)values(".$value['rms_normal'].",".$value['rms_max_normal'].", ".$value['rms_ropping'].", ".$value['sd_normal'].", ".$value['sd_max_normal'].", ".$value['sd_ropping'].", ".$value['radio_id'].") on duplicate key update rms_normal=".$value['rms_normal'].", rms_max_normal_porcentaje=".$value['rms_max_normal'].", rms_ropping_porcentaje=".$value['rms_ropping'].", sd_normal=".$value['sd_normal'].", sd_max_normal_porcentaje=".$value['sd_max_normal'].", sd_ropping_porcentaje=".$value['sd_ropping'].";";
		$oParametros->Select($query_save);
		$query_event = "INSERT INTO eventos_alarmas(tipo)values(6);";
		$oParametros->Select($query_event);
		$save = true;
	}
}

$oRadios = new BSModel();
$query_radios = "select radios.id as radio_id, radios.identificador as identificador, radios.mac as mac, parametros.rms_normal as rms, 
parametros.rms_max_normal_porcentaje as rms_max_normal,
parametros.rms_ropping_porcentaje as rms_ropping, 
parametros.sd_normal as sd_normal, parametros.sd_max_normal_porcentaje as sd_max_normal, 
parametros.sd_ropping_porcentaje as sd_ropping
from radios left join parametros on radios.id = parametros.radio_id where grupo = '".$group."' and radios.estado = 1 order by radios.id asc;";
$aRadios = $oRadios->Select($query_radios);
// $query_radios_conn = "select count(*) as count from radios where estado = 1;";
// $aRadiosConn = $oRadios->Select($query_radios_conn);

?>


<div class="container-body container-calibration">
	<?php if($save === true) { ?>
		<div class="alert alert-success" style="text-align:center;">
	    	Saved settings
	  	</div>
	<?php } ?>
  <h2>System Calibration <?=$group?></h2>
  	  <div class="span12 contenedor">
  	  	<?php if(count($aRadios)  == 0) { ?>
			<div id="empty_radios" class="alert alert-warning">
		    	There is not radios connected
		  	</div>
		<?php } ?>
  	  	<?php if(count($aRadios) > 0) {?>
	  	  	<form name="set_parametros" action="system_calibration.php?group=<?=$group?>" id="set_parametros_form" method="post" enctype="multipart/form-data">
		  	  	<?php foreach ($aRadios as $i=>$radio) { ?>
		  	  		<input type="hidden" value="<?=$radio['radio_id']?>" name="radio[<?=$i?>][radio_id]">
		  	  		<div class="span11 offset1 calibration-radio">
				    	<span style="font-size:18px;"><strong>Identifier: <?=$radio['identificador']?></strong></span></br></br>
				    	<div class="span1 data-type"><strong>RMS</strong></div>
				    	<div class="span9 data-container">	
							<div class="controls controls-row">
							    <label class="span2 offset1">Current rms value</label>
							    <label class="span2 offset1" for="radio[<?=$i?>][rms_normal]" >Ideal rms value</label>
							    <label class="span2 offset1" for="radio[<?=$i?>][rms_max_normal]">Max ideal rms value(%)</label>
							     <label class="span2 offset1" for="radio[<?=$i?>][rms_ropping]">Min ropping rms value(%)</label>
							</div>
							<div class="controls controls-row">
							    <div id="rms_calibration<?=$i?>" class="current-value" ></div>
							    <input type="text" id="radio[<?=$i?>][rms_normal]" class="calibration first-input required" name="radio[<?=$i?>][rms_normal]" value="<?=$radio['rms']?>" onkeypress="return isNumber(event)"/>
							    <input type="text" class="calibration second-input required" name="radio[<?=$i?>][rms_max_normal]" value="<?=$radio['rms_max_normal']?>" id="radio[<?=$i?>][rms_max_normal]" onkeypress="return isNumber(event)"/>
							    <input type="text" class="calibration third-input required" name="radio[<?=$i?>][rms_ropping]" id="radio[<?=$i?>][rms_ropping]" value="<?=$radio['rms_ropping']?>" onkeypress="return isNumber(event)"/>
							</div>    		
				    	</div>

				    	<div class="span1 data-type"><strong>Standard Deviation (SD)</strong></div>
				    	<div class="span9 data-container">
							<div class="controls controls-row">
							    <label class="span2 offset1" ><span><strong>Current SD value</strong></span></label>
							    <label class="span2 offset1" for="radio[<?=$i?>][sd_normal]">Ideal SD value</label>
							    <label class="span2 offset1" for="radio[<?=$i?>][sd_max_normal]">Max ideal SD </br>value(%)</label>
							     <label class="span2 offset1" for="radio[<?=$i?>][sd_ropping]">Min ropping SD value(%)</label>
							</div>
							<div class="controls controls-row">
							    <div id="sd_calibration<?=$i?>" class="current-value" ></div>
							    <input type="text" class="calibration first-input required" name="radio[<?=$i?>][sd_normal]" value="<?=$radio['sd_normal']?>" id="radio[<?=$i?>][sd_normal]" onkeypress="return isNumber(event)"/>
							    <input type="text" class="calibration second-input required" name="radio[<?=$i?>][sd_max_normal]" value="<?=$radio['sd_max_normal']?>" id="radio[<?=$i?>][sd_max_normal]" onkeypress="return isNumber(event)"/>
							    <input type="text" class="calibration third-input required" name="radio[<?=$i?>][sd_ropping]" value="<?=$radio['sd_ropping']?>" id="radio[<?=$i?>][sd_ropping]" onkeypress="return isNumber(event)"/>
							</div>						   		
				    	</div>
					</div>	
		  	  	<?php } ?>
		  	  <div class="div-save-calibration">
		  	  	<input type="submit" value="Save" class="btn btn-primary btn-large" name="save" id="save-calibration">
		  	  </div>
		  	  	
		     </form>	 
	    <?php  }?>   	
	</div>
	<div id="help" class="help">
		<br /> <br />
		<strong>
			H <br />E <br />L <br />P	
		</strong>	    
	</div>
	<div id="wrapper-help">
		<div id="container-help">
			<h3>How to configure</h3>
			<div id="chart-help">
				<img src="assets/img/grafico_referencial3.png">
			</div>
			<div class="text-help">
				<p class="p1">1)I<b>deal RMS/SD value</b>: it must be relative equal to "Current RMS/SD value". It will determinate de working spaces of each state (Idea, Semi-roping or roping), e.g.</p>
				<p class="p1"><span class="Apple-tab-span">	</span>A. Current RMS value: 38.4589</p>
				<p class="p1"><span class="Apple-tab-span">	</span>B. Ideal RMS value: <b>38.459</b> (not exactly equal to A)</p>
				<p class="p2"><br></p>
				<p class="p1">2)<b>Max Ideal RMS value(%)</b>: It determinate perceptually max deviation accepted value for "Ideal RMS value", e.g.</p>
				<p class="p1"><span class="Apple-tab-span">	</span>A. Ideal RMS value: <b>38.459</b></p>
				<p class="p1"><span class="Apple-tab-span">	</span>B. Max ideal RMS value (%): <b>2%</b></p>
				<p class="p1"><span class="Apple-tab-span">	</span>C. Then the max accepted value will be "<b>Ideal RMS value</b>" <b>*</b> "<b>Max ideal RMS value(%)</b>"= <b>38.459</b>*<b>1.02</b> = 39.22818</p>
				<p class="p2"><br></p>
				<p class="p1">3)<b>Min roping RMS/SD value</b>: It determinate perceptually min deviation for "roping condition", should be greater than "<b>Max Ideal RMS/SD value</b>", it will determinate the semi-roping zone too, e.g.</p>
				<p class="p1"><span class="Apple-tab-span">	</span>A. Ideal RMS value: <b>38.459</b></p>
				<p class="p1"><span class="Apple-tab-span">	</span>B. Min roping RMS value (%): <b>5%</b></p>
				<p class="p1"><span class="Apple-tab-span">	</span>C. Then de min value for roping condition is: "<b>Ideal RMS value</b>" * "<b>Min roping RMS value(%)</b>"= <b>38.459</b>*<b>1.05</b> = 40.38195</p>
				<p class="p1"><span class="Apple-tab-span">	</span>D. The semi-roping zone will be determinate between "<b>Max ideal RMS value</b>" and "<b>Min roping RMS value</b>" = 39.22818 &lt; S-R &lt;= 40.38195.</p>
			</div>
		</div>	
	</div>
</div>


<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'footer.php');

?>

<script type="text/javascript">

	function isNumber(evt) {
	        evt = (evt) ? evt : window.event;
	        var charCode = (evt.which) ? evt.which : evt.keyCode;
	        if (charCode > 31 && (charCode < 46 || charCode > 57)) {
	            return false;
	        }
        	return true;
	}

		$('#help').click(function(){
			if($("#help").attr('class') == 'help'){
				$('#wrapper-help').show();
				$("#container-help").toggle("slide", {direction: "right"}, 500);	
				$(this).removeClass("help").addClass("close-container");
				$(this).html("<br /> <br /><strong> C <br />L <br />O <br />S <br />E </strong>");
			}else if($("#help").attr('class') == 'close-container'){
				$(this).removeClass("close-container").addClass("help");
				$(this).html("<br /> <br /> <strong>H <br />E <br />L <br />P </strong>");
				$("#container-help").toggle("slide", {direction: "right"}, 100);	
				$('#wrapper-help').fadeOut("fast");
			}				
		});

	    $('#set_parametros_form').validate({
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

    $(function () {
	      $(document).ready(function() {
	      	var json_radio_id = $.ajax({
		           url: 'json_get_radio_id.php?group=<?=$group?>', // make this url point to the data file
		           dataType: 'json',
		           async: false
		          }).responseText;

            var dataJson_radio = eval(json_radio_id);
            setInterval(function() {          
	          var n = 0;
	          var m = 0;
	          for (var i in dataJson_radio){
	             radio_id = dataJson_radio[i].radio_id;
	             var json_rms = $.ajax({
	               url: 'json_rms_value.php?radio_id='+radio_id, 
	               dataType: 'json',
	               async: false
	              }).responseText;

	             var json_sd = $.ajax({
	               url: 'json_sd_value.php?radio_id='+radio_id, 
	               dataType: 'json',
	               async: false
	              }).responseText;

	              var dataJson_rms = eval(json_rms);
	              for (var i in dataJson_rms){
	                 rms = dataJson_rms[i].value;
	                 $('#rms_calibration'+n.toString()).text(rms); 
	                 n = n + 1;                        
	              }

	              var dataJson_sd = eval(json_sd);
	              for (var i in dataJson_sd){   
	                 sd = dataJson_sd[i].value; 
	                 $('#sd_calibration'+m.toString()).text(sd); 
	                 m = m + 1;                         
	              }
	          }
	      }, 3000);
        });
	});
      	



</script>