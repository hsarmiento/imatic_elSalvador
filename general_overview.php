<?php 
	require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'routes.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'header.php');
	require_once($aRoutes['paths']['config'].'bs_model.php');
	$oModel = new BSModel();
	
	$query_radios = "SELECT id,mac,identificador from radios where estado = 1 order by id asc;";
	$aRadios = $oModel->Select($query_radios);
	$aRms = array();
	$aParametros = array();
	foreach ($aRadios as $radios) {
		$query_rms = "SELECT valor from rms where radio_id = ".$radios['id']." order by id asc;";
		$rms = $oModel->Select($query_rms);
		$aRms[] = array('radio_id' => $radios['id'],
						'valor' => $rms[0]['valor'],
						'mac' => $radios['mac'],
						'identificador' => $radios['identificador']
					);
		$query_parametros = "SELECT * from parametros where radio_id = ".$radios['id'].";";
		$par = $oModel->Select($query_parametros);
		$aParametros[] = array('rms_max_normal' => $par[0]['rms_normal'],
							   'rms_semi_ropping' => ($par[0]['rms_normal'])*(1+$par[0]['rms_max_normal_porcentaje']/100),
							   'rms_ropping' => ($par[0]['rms_normal'])*(1+$par[0]['rms_ropping_porcentaje']/100)
							);
	}
	$count_radios = count($aRms);
	$query_limite_gauge = "SELECT * from grafico_rms order by id desc limit 1;";
	$aLimiteGauge = $oModel->Select($query_limite_gauge);
	if(empty($aLimiteGauge)){
	  $aLimiteGauge= array();
	  $aLimiteGauge[0]['valor_minimo'] = 0;
	  $aLimiteGauge[0]['valor_maximo'] = 1023;
	}
	$gauge_minimo = $aLimiteGauge[0]['valor_minimo'];
	$gauge_maximo = $aLimiteGauge[0]['valor_maximo'];

	$query_group = "SELECT * from radios group by grupo;";
	$aGroup= $oModel->Select($query_group);
?>

<div class="container container-body contenedor">
	<h2>Overview</h2>
	<?php if(count($aRms) == 0){ ?>
		<div id="overview_empty" class="alert alert-warning">
	    	There is not radios connected
	  	</div>
	<?php } ?>
	<?php if(count($aGroup) > 0){ ?>
		<div class="overview-group-left">
			<div class="title-group">
				<?=$aGroup[0]['grupo']?>
			</div>
			<div class="general-status" id="general-status1"></div>
			<div class="img_pila"><a href="overview.php?group=<?=$aGroup[0]['grupo']?>"><img src="assets/img/Pila_3_rev.png"></a></div>
			<div class="link-group">
				<a href="overview.php?group=<?=$aGroup[0]['grupo']?>">Group overview</a>
			</div>
		</div>
	<?php } ?>
  <div id="barra_vertical"></div>
	
	<?php if(count($aGroup) > 1){ ?>
		<div class="overview-group-right">
			<div class="title-group">
				<?=$aGroup[1]['grupo']?>
			</div>
			<div class="general-status" id="general-status2"></div>
			<div class="img_pila"><a href="overview.php?group=<?=$aGroup[1]['grupo']?>"><img src="assets/img/Pila_3_rev.png"></a></div>
			<div class="link-group">
				<a href="overview.php?group=<?=$aGroup[1]['grupo']?>">Group overview</a>

			</div>
		</div>
	<?php } ?>
  <div id="barra_horizontal"></div>
	<?php if(count($aGroup) > 2){ ?>
		<div class="overview-group-left">
			<div class="title-group">
				<?=$aGroup[2]['grupo']?>
			</div>
			<div class="general-status" id="general-status3"></div>
			<div class="img_pila"><a href="overview.php?group=<?=$aGroup[2]['grupo']?>"><img src="assets/img/Pila_3_rev.png"></a></div>
			<div class="link-group">
				<a href="overview.php?group=<?=$aGroup[2]['grupo']?>">Group overview</a>
			</div>
		</div>
	<?php } ?>

	<?php if(count($aGroup) > 3){ ?>
		<div class="overview-group-right">
			<div class="title-group">
				<?=$aGroup[3]['grupo']?>
			</div>
			<div class="general-status" id="general-status4"></div>
			<div class="img_pila"><a href="overview.php?group=<?=$aGroup[3]['grupo']?>"><img src="assets/img/Pila_3_rev.png"></a></div>
			<div class="link-group">
				<a href="overview.php?group=<?=$aGroup[3]['grupo']?>">Group overview</a>
			</div>
		</div>
	<?php } ?>
</div>

<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'footer.php');

?>

<script type="text/javascript">
	
	<?php if(count($aGroup) > 0){ ?>
		setInterval(function () {
          var json = $.ajax({
           url: 'json_group_status.php?group=<?=$aGroup[0]["grupo"]?>', // make this url point to the data file
           dataType: 'json',
           async: false
          }).responseText;
          var dataJson = eval(json);
          for (var i in dataJson){
            
             status = dataJson[i].status;                         
          }

      	  if(status == "roping"){
  	  			if($("#general-status1").attr('class') == 'general-status'  || $("#general-status1").attr('class') == 'alert alert-success' || $("#general-status1").attr('class') == 'alert alert-warning'){
      					$("#general-status1").removeClass("general-status").removeClass("alert alert-success").removeClass("alert alert-warning").addClass("alert alert-error");	
      					// $('#msg1').hide();
  						$("#general-status1").text("Roping").show();
  			}
      	  }
      	  if(status == "semiroping"){
  	  			if($("#general-status1").attr('class') == 'general-status'  || $("#general-status1").attr('class') == 'alert alert-success' || $("#general-status1").attr('class') == 'alert alert-error'){
      					$("#general-status1").removeClass("general-status").removeClass("alert alert-success").removeClass("alert alert-error").addClass("alert alert-warning");	
      					// $('#msg1').hide();
  						$("#general-status1").text("Semiroping").show();
  			}
      	  }
      	  if(status == "normal"){
  	  			if($("#general-status1").attr('class') == 'general-status'  || $("#general-status1").attr('class') == 'alert alert-warning' || $("#general-status1").attr('class') == 'alert alert-error'){
      					$("#general-status1").removeClass("general-status").removeClass("alert alert-warning").removeClass("alert alert-error").addClass("alert alert-success");	
      					// $('#msg1').hide();
  						$("#general-status1").text("Normal").show();
  			}
      	  }    
      }, 1000);
	<?php } ?>
	<?php if(count($aGroup) > 1){ ?>
		setInterval(function () {
          var json = $.ajax({
           url: 'json_group_status.php?group=<?=$aGroup[1]["grupo"]?>', // make this url point to the data file
           dataType: 'json',
           async: false
          }).responseText;
          var dataJson = eval(json);
          for (var i in dataJson){
            
             status = dataJson[i].status;                         
          }

      	  if(status == "roping"){
  	  			if($("#general-status2").attr('class') == 'general-status'  || $("#general-status2").attr('class') == 'alert alert-success' || $("#general-status2").attr('class') == 'alert alert-warning'){
      					$("#general-status2").removeClass("general-status").removeClass("alert alert-success").removeClass("alert alert-warning").addClass("alert alert-error");	
      					// $('#msg1').hide();
  						$("#general-status2").text("Roping").show();
  			}
      	  }
      	  if(status == "semiroping"){
  	  			if($("#general-status2").attr('class') == 'general-status'  || $("#general-status2").attr('class') == 'alert alert-success' || $("#general-status2").attr('class') == 'alert alert-error'){
      					$("#general-status2").removeClass("general-status").removeClass("alert alert-success").removeClass("alert alert-error").addClass("alert alert-warning");	
      					// $('#msg1').hide();
  						$("#general-status2").text("Semiroping").show();
  			}
      	  }
      	  if(status == "normal"){
  	  			if($("#general-status2").attr('class') == 'general-status'  || $("#general-status2").attr('class') == 'alert alert-warning' || $("#general-status2").attr('class') == 'alert alert-error'){
      					$("#general-status2").removeClass("general-status").removeClass("alert alert-warning").removeClass("alert alert-error").addClass("alert alert-success");	
      					// $('#msg1').hide();
  						$("#general-status2").text("Normal").show();
  			}
      	  }    
      }, 1000);
	<?php } ?>
	<?php if(count($aGroup) > 2){ ?>
		setInterval(function () {
          var json = $.ajax({
           url: 'json_group_status.php?group=<?=$aGroup[2]["grupo"]?>', // make this url point to the data file
           dataType: 'json',
           async: false
          }).responseText;
          var dataJson = eval(json);
          for (var i in dataJson){
            
             status = dataJson[i].status;                         
          }

      	  if(status == "roping"){
  	  			if($("#general-status3").attr('class') == 'general-status'  || $("#general-status3").attr('class') == 'alert alert-success' || $("#general-status3").attr('class') == 'alert alert-warning'){
      					$("#general-status3").removeClass("general-status").removeClass("alert alert-success").removeClass("alert alert-warning").addClass("alert alert-error");	
      					// $('#msg1').hide();
  						$("#general-status3").text("Roping").show();
  			}
      	  }
      	  if(status == "semiroping"){
  	  			if($("#general-status3").attr('class') == 'general-status'  || $("#general-status3").attr('class') == 'alert alert-success' || $("#general-status3").attr('class') == 'alert alert-error'){
      					$("#general-status3").removeClass("general-status").removeClass("alert alert-success").removeClass("alert alert-error").addClass("alert alert-warning");	
      					// $('#msg1').hide();
  						$("#general-status3").text("Semiroping").show();
  			}
      	  }
      	  if(status == "normal"){
  	  			if($("#general-status3").attr('class') == 'general-status'  || $("#general-status3").attr('class') == 'alert alert-warning' || $("#general-status3").attr('class') == 'alert alert-error'){
      					$("#general-status3").removeClass("general-status").removeClass("alert alert-warning").removeClass("alert alert-error").addClass("alert alert-success");	
      					// $('#msg1').hide();
  						$("#general-status3").text("Normal").show();
  			}
      	  }    
      }, 1000);
	<?php } ?>
	<?php if(count($aGroup) > 3){ ?>
		setInterval(function () {
          var json = $.ajax({
           url: 'json_group_status.php?group=<?=$aGroup[3]["grupo"]?>', // make this url point to the data file
           dataType: 'json',
           async: false
          }).responseText;
          var dataJson = eval(json);
          for (var i in dataJson){
            
             status = dataJson[i].status;                         
          }

      	  if(status == "roping"){
  	  			if($("#general-status4").attr('class') == 'general-status'  || $("#general-status4").attr('class') == 'alert alert-success' || $("#general-status4").attr('class') == 'alert alert-warning'){
      					$("#general-status4").removeClass("general-status").removeClass("alert alert-success").removeClass("alert alert-warning").addClass("alert alert-error");	
      					// $('#msg1').hide();
  						$("#general-status4").text("Roping").show();
  			}
      	  }
      	  if(status == "semiroping"){
  	  			if($("#general-status4").attr('class') == 'general-status'  || $("#general-status4").attr('class') == 'alert alert-success' || $("#general-status4").attr('class') == 'alert alert-error'){
      					$("#general-status4").removeClass("general-status").removeClass("alert alert-success").removeClass("alert alert-error").addClass("alert alert-warning");	
      					// $('#msg1').hide();
  						$("#general-status4").text("Semiroping").show();
  			}
      	  }
      	  if(status == "normal"){
  	  			if($("#general-status4").attr('class') == 'general-status'  || $("#general-status4").attr('class') == 'alert alert-warning' || $("#general-status4").attr('class') == 'alert alert-error'){
      					$("#general-status4").removeClass("general-status").removeClass("alert alert-warning").removeClass("alert alert-error").addClass("alert alert-success");	
      					// $('#msg1').hide();
  						$("#general-status4").text("Normal").show();
  			}
      	  }    
      }, 1000);
	<?php } ?>

	
</script>