<?php 
	require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'routes.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'header.php');
	require_once($aRoutes['paths']['config'].'bs_model.php');
	$oModel = new BSModel();
	$group = $_GET['group'];
	$form = $_POST;
	if($form['rms_save_chart_settings'] == 'Save'){
	    $oModel = new BSModel();
	    $query_chart = "INSERT INTO grafico_rms(valor_minimo, valor_maximo)values(".$form['rms_min_chart'].", ".$form['rms_max_chart'].");";
	    $oModel->Select($query_chart);
	    $query_event = "INSERT INTO eventos_alarmas(tipo)values(7);";
	    $oModel->Select($query_event);
	    // header("Location: overview.php");  
	}
	
	$query_radios = "SELECT id,mac,identificador from radios where grupo = '".$group."' and estado = 1 order by identificador asc;";
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

?>

<div class="container container-body contenedor">
	<h2>Overview <?=$group?></h2>
	<?php if(count($aRms) == 0){ ?>
		<div id="overview_empty" class="alert alert-warning">
	    	There is not radios connected
	  	</div>
	<?php } ?>
	<div class="pila-overview"><img height="400" width="400" src="assets/img/Pila_3.png"></div>

	<div id="gauge1-overview" class="gauge"></div>
	<div id="msg1" class="overview-msg calibrate">Calibrando</div>
	<div id="link-status1" class="link-status">
		<a href="status.php?radio_id=<?=$aRms[0]['radio_id']?>&n_radio=1">Show live status</a>
	</div>
	<div id="gauge2-overview" class="gauge"></div>
	<div id="msg2" class="overview-msg calibrate">Calibrando</div>
	<div id="link-status2" class="link-status">
    	<a href="status.php?radio_id=<?=$aRms[1]['radio_id']?>&n_radio=2">Show live status</a>
    </div>
    <div id="gauge3-overview" class="gauge"></div>
	<div id="msg3" class="overview-msg calibrate">Calibrando</div>
	<div id="link-status3" class="link-status">
    	<a href="status.php?radio_id=<?=$aRms[2]['radio_id']?>&n_radio=3">Show live status</a>
    </div>	


    <div id="help-overview" class="help">
		<br /> <br /> 
		<strong>
			S<br /> C<br />A<br />L<br />E
		</strong>	    
	</div>
	<div id="wrapper-help-overview">
		<div id="container-help-overview">
			<div class="span3 form-chart">
	            <h4>Gauge configuration</h4>
	            <div id="value_gauge_conf">Enter a value between 0 to 200</div>
	            <form  id="rms_set_chart" method="post" name="rms_set_chart" action="overview.php?group=<?=$group?>" enctype="multipart/form-data">
	              <div class="controls controls-row">
	                <label class="span1" for="rms_min_chart">Min value</label>
	                <label class="span1 offset2" for="rms_max_chart">Max value</label>
	              </div>
	              <div class="controls controls-row">
	                <input type="text" class="span1" name="rms_min_chart" id="rms_min_chart" value="<?=$aFormGauge[0]['valor_minimo']?>">
	                <input type="text" class="span1 offset2" name="rms_max_chart" id="rms_max_chart" value="<?=$aFormGauge[0]['valor_maximo']?>">
	              </div> 
	              <input type="submit" value="Save" name="rms_save_chart_settings" class="btn btn-primary save_chart_settings" id="rms_save_gauge_settings">
	            </form>
          	</div> 
		</div>	
	</div>
</div>

<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'footer.php');

?>

<script type="text/javascript" src="<? echo $aRoutes['paths']['js']?>highcharts.js"></script>
<script type="text/javascript" src="<? echo $aRoutes['paths']['js']?>/modules/exporting.js"></script>
<script type="text/javascript" src="<? echo $aRoutes['paths']['js']?>highcharts-more.js"></script>

<script type="text/javascript">
	$('#help-overview').click(function(){
		if($("#help-overview").attr('class') == 'help'){
			$('#wrapper-help-overview').show();
			$("#container-help-overview").toggle("slide", {direction: "right"}, 500);	
			$(this).removeClass("help").addClass("close-container");
			$(this).html("<br /> <br /><strong> C <br />L <br />O <br />S <br />E </strong>");
		}else if($("#help-overview").attr('class') == 'close-container'){
			$(this).removeClass("close-container").addClass("help");
			$(this).html("<br /> <br /> <strong>S <br />C <br />A<br />L<br />E </strong>");
			$("#container-help-overview").toggle("slide", {direction: "right"}, 100);	
			$('#wrapper-help-overview').fadeOut("fast");
		}			
	});
	$("#rms_set_chart").validate({
		rules:{
			rms_min_chart:{
				required: true,
				min: 0,
				max: 200,
				number: true
			},
			rms_max_chart:{
				required: true,
				min: 0,
				max: 200,
				number: true
			}
		},
		invalidHandler: function(form){
				alert('Enter a value between 0 to 200'); // for demo
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
	<?php if($count_radios > 0){?>
		
		$(function () {
			  $('#link-status1').show();
		      $('#gauge1-overview').highcharts({
		    
		        chart: {
		            type: 'gauge',
		            plotBackgroundColor: null,
		            plotBackgroundImage: null,
		            plotBorderWidth: 0,
		            plotShadow: false
		        },
		        exporting:{
		          enabled: false
		        },
		        credits:{
		          enabled: false
		        },
		        title: {
		            text: '<?=$aRms[0]["identificador"]?>'
		        },
		        
		        pane: {
		            startAngle: -120,
		            endAngle: 120,
		            background: [{
		                backgroundColor: {
		                    linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
		                    stops: [
		                        [0, '#FFF'],
		                        [1, '#333']
		                    ]
		                },
		                borderWidth: 0,
		                outerRadius: '109%'
		            }, {
		                backgroundColor: {
		                    linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
		                    stops: [
		                        [0, '#333'],
		                        [1, '#FFF']
		                    ]
		                },
		                borderWidth: 1,
		                outerRadius: '107%'
		            }, {
		                // default background
		            }, {
		                backgroundColor: '#DDD',
		                borderWidth: 0,
		                outerRadius: '105%',
		                innerRadius: '103%'
		            }]
		        },
		           
		        // the value axis
		        yAxis: {
		        	min: <?=$gauge_minimo?>,
		            max: <?=$gauge_maximo?>,
		            // min: <?=$gauge_minimo?>,
		            // max: <?=$gauge_maximo?>,
		            
		            minorTickInterval: 'auto',
		            minorTickWidth: 1,
		            minorTickLength: 10,
		            minorTickPosition: 'inside',
		            minorTickColor: '#666',
		    
		            tickPixelInterval: 30,
		            tickWidth: 2,
		            tickPosition: 'inside',
		            tickLength: 10,
		            tickColor: '#666',
		            labels: {
		                step: 2,
		                rotation: 'auto'
		            },
		            // title: {
		            //     text: 'km/h'
		            // },
		            plotBands: [{
		                from: <?=$gauge_minimo?>,
		                to: <?=$aParametros[0]['rms_semi_ropping']?>,
		                color: '#55BF3B' // green
		            }, 
		            {
		                from: <?=$aParametros[0]['rms_semi_ropping']?>,
		                to: <?=$aParametros[0]['rms_ropping']?>,
		                color: '#DDDF0D' // yellow
		            }, 
		            {
		                from: <?=$aParametros[0]['rms_ropping']?>,
		                to:  <?=$gauge_maximo?>,
		                color: '#DF5353' // red
		            }]        
		        },
		    
		        series: [{
		            // name: 'Speed',
		            data: [0],
		            // tooltip: {
		            //     valueSuffix: ' km/h'
		            // }
		        }]
		    
		    }, 
		    // Add some life
		    function (chart) {
		      if (!chart.renderer.forExport) {
		          setInterval(function () {
		              var json = $.ajax({
		               url: 'json_rms_value.php?radio_id=<?=$aRms[0]["radio_id"]?>', // make this url point to the data file
		               dataType: 'json',
		               async: false
		              }).responseText;
		              var dataJson = eval(json);
		              for (var i in dataJson){
		                
		                 y_data = dataJson[i].value;                         
		              }

		              if(y_data == -1){
	              		if($("#msg1").attr('class') == 'overview-msg calibrate'  || $("#msg1").attr('class') == 'overview-msg alert-warning' || $("#msg1").attr('class') == 'overview-msg alert-error' || $("#msg1").attr('class') == 'overview-msg alert-success'){
	              			$("#msg1").removeClass("calibrate").removeClass("alert-warning").removeClass("alert-success").removeClass("alert-error").addClass("alert-disconnected");	
	              			$('#msg1').hide();
              				$('#msg1').text("Disconnected").show();
              			}	
		              }else if(y_data >= <?=$gauge_minimo?> && y_data < <?=$aParametros[0]['rms_semi_ropping']?>){
		              	if($("#msg1").attr('class') == 'overview-msg calibrate'  || $("#msg1").attr('class') == 'overview-msg alert-warning' || $("#msg1").attr('class') == 'overview-msg alert-error' || $("#msg1").attr('class') == 'overview-msg alert-disconnected'){
	              			$("#msg1").removeClass("calibrate").removeClass("alert-warning").removeClass("alert-error").removeClass("alert-disconnected").addClass("alert-success");	
	              			$('#msg1').hide();
              				$('#msg1').text("Ideal").show();
              			}
		              }else if(y_data >= <?=$aParametros[0]['rms_semi_ropping']?> && y_data < <?=$aParametros[0]['rms_ropping']?>){
	              		if($("#msg1").attr('class') == 'overview-msg calibrate'  || $("#msg1").attr('class') == 'overview-msg alert-error' || $("#msg1").attr('class') == 'overview-msg alert-success' || $("#msg1").attr('class') == 'overview-msg alert-disconnected'){
	              			$("#msg1").removeClass("calibrate").removeClass("alert-error").removeClass("alert-success").removeClass("alert-disconnected").addClass("alert-warning");	
	              			$('#msg1').hide();
              				$('#msg1').text("Semiropping").show();
              			}     		
		              }else if(y_data >= <?=$aParametros[0]['rms_ropping']?>){
	              		if($("#msg1").attr('class') == 'overview-msg calibrate'  || $("#msg1").attr('class') == 'overview-msg alert-warning' || $("#msg1").attr('class') == 'overview-msg alert-success' || $("#msg1").attr('class') == 'overview-msg alert-disconnected'){
	              			$("#msg1").removeClass("calibrate").removeClass("alert-warning").removeClass("alert-success").removeClass("alert-disconnected").addClass("alert-error");	
	              			$('#msg1').hide();
              				$('#msg1').text("Ropping").show();
              			}
		              }

		              var point = chart.series[0].points[0],
		                  newVal,
		                  inc = y_data;
		              
		              newVal = inc;
		              
		              point.update(newVal);
		              
		          }, 1000);
		      }
		    });
		  });
	<?php } ?>	
	
	<?php if($count_radios > 1){?>
		$(function () {
			  $('#link-status2').show();
		      $('#gauge2-overview').highcharts({
		    
		        chart: {
		            type: 'gauge',
		            plotBackgroundColor: null,
		            plotBackgroundImage: null,
		            plotBorderWidth: 0,
		            plotShadow: false
		        },
		        exporting:{
		          enabled: false
		        },
		        credits:{
		          enabled: false
		        },
		        title: {
		            text: '<?=$aRms[1]["identificador"]?>'
		        },
		        
		        pane: {
		            startAngle: -120,
		            endAngle: 120,
		            background: [{
		                backgroundColor: {
		                    linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
		                    stops: [
		                        [0, '#FFF'],
		                        [1, '#333']
		                    ]
		                },
		                borderWidth: 0,
		                outerRadius: '109%'
		            }, {
		                backgroundColor: {
		                    linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
		                    stops: [
		                        [0, '#333'],
		                        [1, '#FFF']
		                    ]
		                },
		                borderWidth: 1,
		                outerRadius: '107%'
		            }, {
		                // default background
		            }, {
		                backgroundColor: '#DDD',
		                borderWidth: 0,
		                outerRadius: '105%',
		                innerRadius: '103%'
		            }]
		        },
		           
		        // the value axis
		        yAxis: {
		            min: <?=$gauge_minimo?>,
		            max: <?=$gauge_maximo?>,
		            
		            minorTickInterval: 'auto',
		            minorTickWidth: 1,
		            minorTickLength: 10,
		            minorTickPosition: 'inside',
		            minorTickColor: '#666',
		    
		            tickPixelInterval: 30,
		            tickWidth: 2,
		            tickPosition: 'inside',
		            tickLength: 10,
		            tickColor: '#666',
		            labels: {
		                step: 2,
		                rotation: 'auto'
		            },
		            // title: {
		            //     text: 'km/h'
		            // },
		           plotBands: [{
		                from: <?=$gauge_minimo?>,
		                to: <?=$aParametros[1]['rms_semi_ropping']?>,
		                color: '#55BF3B' // green
		            }, 
		            {
		                from: <?=$aParametros[1]['rms_semi_ropping']?>,
		                to: <?=$aParametros[1]['rms_ropping']?>,
		                color: '#DDDF0D' // yellow
		            }, 
		            {
		                from: <?=$aParametros[1]['rms_ropping']?>,
		                to:  <?=$gauge_maximo?>,
		                color: '#DF5353' // red
		            }]        
		        },
		    
		        series: [{
		            // name: 'Speed',
		            data: [0],
		            // tooltip: {
		            //     valueSuffix: ' km/h'
		            // }
		        }]
		    
		    }, 
		    // Add some life
		    function (chart) {
		      if (!chart.renderer.forExport) {
		          setInterval(function () {
		              var json = $.ajax({
		               url: 'json_rms_value.php?radio_id=<?=$aRms[1]["radio_id"]?>', // make this url point to the data file
		               dataType: 'json',
		               async: false
		              }).responseText;

		              var dataJson = eval(json);
		              for (var i in dataJson){
		                
		                 y_data = dataJson[i].value;                         
		              }
		              var point = chart.series[0].points[0],
		                  newVal,
		                  inc = y_data;
		              if(y_data == -1){
	              		if($("#msg2").attr('class') == 'overview-msg calibrate'  || $("#msg2").attr('class') == 'overview-msg alert-warning' || $("#msg2").attr('class') == 'overview-msg alert-error' || $("#msg2").attr('class') == 'overview-msg alert-success'){
	              			$("#msg2").removeClass("calibrate").removeClass("alert-warning").removeClass("alert-success").removeClass("alert-error").addClass("alert-disconnected");	
	              			$('#msg2').hide();
              				$('#msg2').text("Disconnected").show();
              			}	
		              }else if(y_data >= <?=$gauge_minimo?> && y_data < <?=$aParametros[1]['rms_semi_ropping']?>){
		              	if($("#msg2").attr('class') == 'overview-msg calibrate'  || $("#msg2").attr('class') == 'overview-msg alert-warning' || $("#msg2").attr('class') == 'overview-msg alert-error' || $("#msg2").attr('class') == 'overview-msg alert-disconnected'){
	              			$("#msg2").removeClass("calibrate").removeClass("alert-warning").removeClass("alert-error").removeClass("alert-disconnected").addClass("alert-success");	
	              			$('#msg2').hide();
              				$('#msg2').text("Ideal").show();
              			}
		              }else if(y_data >= <?=$aParametros[1]['rms_semi_ropping']?> && y_data < <?=$aParametros[1]['rms_ropping']?>){
	              		if($("#msg2").attr('class') == 'overview-msg calibrate'  || $("#msg2").attr('class') == 'overview-msg alert-error' || $("#msg2").attr('class') == 'overview-msg alert-success' || $("#msg2").attr('class') == 'overview-msg alert-disconnected'){
	              			$("#msg2").removeClass("calibrate").removeClass("alert-error").removeClass("alert-success").removeClass("alert-disconnected").addClass("alert-warning");	
	              			$('#msg2').hide();
              				$('#msg2').text("Semiropping").show();
              			}     		
		              }else if(y_data >= <?=$aParametros[1]['rms_ropping']?>){
	              		if($("#msg2").attr('class') == 'overview-msg calibrate'  || $("#msg2").attr('class') == 'overview-msg alert-warning' || $("#msg2").attr('class') == 'overview-msg alert-success' || $("#msg2").attr('class') == 'overview-msg alert-disconnected'){
	              			$("#msg2").removeClass("calibrate").removeClass("alert-warning").removeClass("alert-success").removeClass("alert-disconnected").addClass("alert-error");	
	              			$('#msg2').hide();
              				$('#msg2').text("Ropping").show();
              			}
		              }
		              newVal = inc;	              
		              point.update(newVal);
		              
		          }, 1000);
		      }
		    });
		  });

	<?php } ?>
	<?php if($count_radios > 2){?>
		$(function () {
			  $('#link-status3').show();
		      $('#gauge3-overview').highcharts({
		    
		        chart: {
		            type: 'gauge',
		            plotBackgroundColor: null,
		            plotBackgroundImage: null,
		            plotBorderWidth: 0,
		            plotShadow: false
		        },
		        exporting:{
		          enabled: false
		        },
		        credits:{
		          enabled: false
		        },
		        title: {
		            text: '<?=$aRms[2]["identificador"]?>'
		        },
		        
		        pane: {
		            startAngle: -120,
		            endAngle: 120,
		            background: [{
		                backgroundColor: {
		                    linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
		                    stops: [
		                        [0, '#FFF'],
		                        [1, '#333']
		                    ]
		                },
		                borderWidth: 0,
		                outerRadius: '109%'
		            }, {
		                backgroundColor: {
		                    linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
		                    stops: [
		                        [0, '#333'],
		                        [1, '#FFF']
		                    ]
		                },
		                borderWidth: 1,
		                outerRadius: '107%'
		            }, {
		                // default background
		            }, {
		                backgroundColor: '#DDD',
		                borderWidth: 0,
		                outerRadius: '105%',
		                innerRadius: '103%'
		            }]
		        },
		           
		        // the value axis
		        yAxis: {
		            min: <?=$gauge_minimo?>,
		            max: <?=$gauge_maximo?>,
		            
		            minorTickInterval: 'auto',
		            minorTickWidth: 1,
		            minorTickLength: 10,
		            minorTickPosition: 'inside',
		            minorTickColor: '#666',
		    
		            tickPixelInterval: 30,
		            tickWidth: 2,
		            tickPosition: 'inside',
		            tickLength: 10,
		            tickColor: '#666',
		            labels: {
		                step: 2,
		                rotation: 'auto'
		            },
		            // title: {
		            //     text: 'km/h'
		            // },
		            plotBands: [{
		                from: <?=$gauge_minimo?>,
		                to: <?=$aParametros[2]['rms_semi_ropping']?>,
		                color: '#55BF3B' // green
		            }, 
		            {
		                from: <?=$aParametros[2]['rms_semi_ropping']?>,
		                to: <?=$aParametros[2]['rms_ropping']?>,
		                color: '#DDDF0D' // yellow
		            }, 
		            {
		                from: <?=$aParametros[2]['rms_ropping']?>,
		                to:  <?=$gauge_maximo?>,
		                color: '#DF5353' // red
		            }]        
		        },
		    
		        series: [{
		            // name: 'Speed',
		            data: [0],
		            // tooltip: {
		            //     valueSuffix: ' km/h'
		            // }
		        }]
		    
		    }, 
		    // Add some life
		    function (chart) {
		      if (!chart.renderer.forExport) {
		          setInterval(function () {
		              var json = $.ajax({
		               url: 'json_rms_value.php?radio_id=<?=$aRms[2]["radio_id"]?>', // make this url point to the data file
		               dataType: 'json',
		               async: false
		              }).responseText;
		              var dataJson = eval(json);
		              for (var i in dataJson){
		                
		                 y_data = dataJson[i].value;                         
		              }

	              	if(y_data == -1){
	              		if($("#msg3").attr('class') == 'overview-msg calibrate'  || $("#msg3").attr('class') == 'overview-msg alert-warning' || $("#msg3").attr('class') == 'overview-msg alert-error' || $("#msg3").attr('class') == 'overview-msg alert-success'){
	              			$("#msg3").removeClass("calibrate").removeClass("alert-warning").removeClass("alert-success").removeClass("alert-error").addClass("alert-disconnected");	
	              			$('#msg3').hide();
              				$('#msg3').text("Disconnected").show();
              			}	
		              }else if(y_data >= <?=$gauge_minimo?> && y_data < <?=$aParametros[2]['rms_semi_ropping']?>){
		              	if($("#msg3").attr('class') == 'overview-msg calibrate'  || $("#msg3").attr('class') == 'overview-msg alert-warning' || $("#msg3").attr('class') == 'overview-msg alert-error' || $("#msg3").attr('class') == 'overview-msg alert-disconnected'){
	              			$("#msg3").removeClass("calibrate").removeClass("alert-warning").removeClass("alert-error").removeClass("alert-disconnected").addClass("alert-success");	
	              			$('#msg3').hide();
              				$('#msg3').text("Ideal").show();
              			}
		              }else if(y_data >= <?=$aParametros[2]['rms_semi_ropping']?> && y_data < <?=$aParametros[2]['rms_ropping']?>){
	              		if($("#msg3").attr('class') == 'overview-msg calibrate'  || $("#msg3").attr('class') == 'overview-msg alert-error' || $("#msg3").attr('class') == 'overview-msg alert-success' || $("#msg3").attr('class') == 'overview-msg alert-disconnected'){
	              			$("#msg3").removeClass("calibrate").removeClass("alert-error").removeClass("alert-success").removeClass("alert-disconnected").addClass("alert-warning");	
	              			$('#msg3').hide();
              				$('#msg3').text("Semiropping").show();
              			}     		
		              }else if(y_data >= <?=$aParametros[2]['rms_ropping']?>){
	              		if($("#msg3").attr('class') == 'overview-msg calibrate'  || $("#msg3").attr('class') == 'overview-msg alert-warning' || $("#msg3").attr('class') == 'overview-msg alert-success' || $("#msg3").attr('class') == 'overview-msg alert-disconnected'){
	              			$("#msg3").removeClass("calibrate").removeClass("alert-warning").removeClass("alert-success").removeClass("alert-disconnected").addClass("alert-error");	
	              			$('#msg3').hide();
              				$('#msg3').text("Ropping").show();
              			}
		              }

		              var point = chart.series[0].points[0],
		                  newVal,
		                  inc = y_data;
		              
		              newVal = inc;
		              
		              point.update(newVal);
		              
		          }, 1000);
		      }
		    });
		  });

	<?php } ?>
</script>