
<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'routes.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'header.php');
require_once($aRoutes['paths']['config'].'bs_model.php');

$radio_id = $_GET['radio_id'];
if(empty($radio_id)){
  header("Location: home.php");
}

$n_radio = $_GET['n_radio'];
if(empty($n_radio)){
  header("Location: home.php");
}

$form = $_POST;
if($form['rms_save_chart_settings'] == 'Save'){
  $oModel = new BSModel();
  $query_chart = "INSERT INTO grafico_rms(valor_minimo, valor_maximo)values(".$form['rms_min_chart'].", ".$form['rms_max_chart'].");";
  $oModel->Select($query_chart);
  $query_event = "INSERT INTO eventos_alarmas(tipo)values(7);";
  $oModel->Select($query_event);
  header("Location: status.php?radio_id=$radio_id&n_radio=$n_radio");  
}

if($form['sd_save_chart_settings'] == 'Save'){
  $oModel = new BSModel();
  $query_chart = "INSERT INTO grafico_sd(valor_minimo, valor_maximo)values(".$form['sd_min_chart'].", ".$form['sd_max_chart'].");";
  $oModel->Select($query_chart);
  $query_event = "INSERT INTO eventos_alarmas(tipo)values(11);";
  $oModel->Select($query_event);
  header("Location: status.php?radio_id=$radio_id&n_radio=$n_radio"); 
}

$oLogin = new BSLogin();
$oLogin->IsLogged("admin","supervisor");
$oModel = new BSModel();
$query_parametro = "SELECT * FROM parametros where radio_id = ".$radio_id.";";
$aParametros = $oModel->Select($query_parametro);
$query_grafico_rms = "SELECT * FROM grafico_rms order by id desc limit 1;";
$aGraficoRms = $oModel->Select($query_grafico_rms);
$query_grafico_sd = "SELECT * FROM grafico_sd order by id desc limit 1;";
$aGraficoSD = $oModel->Select($query_grafico_sd);
$query_radio = "SELECT * from radios where id = ".$radio_id.";";
$aRadio = $oModel->Select($query_radio);

if(empty($aGraficoRms)){
  $aGraficoRms= array();
  $aGraficoRms[0]['valor_minimo'] = 0;
  $aGraficoRms[0]['valor_maximo'] = 1023;
}

if(empty($aGraficoSD)){
  $aGraficoSD= array();
  $aGraficoSD[0]['valor_minimo'] = 0;
  $aGraficoSD[0]['valor_maximo'] = 10;
}

$rms = $aParametros[0]['rms_normal'];
$rms_semi_ropping= ($rms)*(1+$aParametros[0]['rms_max_normal_porcentaje']/100);
$rms_ropping = ($rms)*(1+$aParametros[0]['rms_ropping_porcentaje']/100);

$sd = $aParametros[0]['sd_normal'];
$sd_semi_ropping = ($sd)*(1+$aParametros[0]['sd_max_normal_porcentaje']/100);
$sd_ropping = ($sd)*(1+$aParametros[0]['sd_ropping_porcentaje']/100);

?>

<div class="container container-body contenedor">		
	<h2><?=$aRadio[0]['identificador']?> Status</h2>
  <div class="alert alert-success" id="normal" style="display:none">
    <?=$aRadio[0]['identificador']?> working in normal conditions
  </div>
  <div class="alert alert-warning" id="semiropping" style="display:none">
    Warning! <?=$aRadio[0]['identificador']?> is semi roping
  </div>
  <div class="alert alert-error" id="ropping" style="display:none">
    Warning! <?=$aRadio[0]['identificador']?> is roping
  </div>  
  <div class="row status-chart">
      <h3>R.M.S</h3>
      <div class="span3">
        <div id="gauge_rms" class="gauge-status"></div>
        <div class="span3 form-chart">
          <h4>RMS chart configuration</h4>
          <div id="value_rms_conf">Enter a value between 0 to 200</div>
          <form  id="rms_set_chart" method="post" name="rms_set_chart" action="status.php?radio_id=<?=$radio_id?>&n_radio=<?=$n_radio?>" enctype="multipart/form-data">
            <div class="controls controls-row">
              <label class="span1" for="rms_min_chart">Min chart value</label>
              <label class="span1 offset2" for="rms_max_chart">Max chart value</label>
            </div>
            <div class="controls controls-row">
              <input type="text" class="span1" name="rms_min_chart" id="rms_min_chart" value="<?=$aGraficoRms[0]['valor_minimo']?>">
              <input type="text" class="span1 offset2" name="rms_max_chart" id="rms_max_chart" value="<?=$aGraficoRms[0]['valor_maximo']?>">
            </div> 
            <input type="submit" value="Save" name="rms_save_chart_settings" class="btn btn-primary save_chart_settings" id="rms_save_chart_settings">
          </form>
        </div> 
      </div>

      <div class="span7"><div id="line_rms" class="offset1 line-status"></div>
      </div>

  </div>
  <div class="row status-chart">
      <h3>Standard Deviation</h3>
      <div class="span3">
        <div id="gauge_sd" class="gauge-status"></div>
          <div class="span3 form-chart">
            <h4>SD chart configuration</h4>
            <div id="value_sd_conf">Enter a value between 0 to 200</div>
            <form  id="sd_set_chart" method="post" name="sd_set_chart" action="status.php?radio_id=<?=$radio_id?>&n_radio=<?=$n_radio?>" enctype="multipart/form-data">
              <div class="controls controls-row">
                <label class="span1" for="sd_min_chart">Min chart value</label>
                <label class="span1 offset2" for="sd_max_chart">Max chart value</label>
              </div>
              <div class="controls controls-row">
                <input type="text" class="span1" name="sd_min_chart" id="sd_min_chart" value="<?=$aGraficoSD[0]['valor_minimo']?>">
                <input type="text" class="span1 offset2" name="sd_max_chart" id="sd_max_chart" value="<?=$aGraficoSD[0]['valor_maximo']?>">
              </div> 
              <input type="submit" value="Save" name="sd_save_chart_settings" class="btn btn-primary save_chart_settings" id="sd_save_chart_settings">
            </form>
          </div> 
        </div>
      <div class="span7"><div class="offset1 line-status" id="line_sd"></div>
      </div>
  </div>
</div>


<script type="text/javascript" src="<? echo $aRoutes['paths']['js']?>highcharts.js"></script>
<script type="text/javascript" src="<? echo $aRoutes['paths']['js']?>/modules/exporting.js"></script>
<script type="text/javascript" src="<? echo $aRoutes['paths']['js']?>highcharts-more.js"></script>


<script type="text/javascript">
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

  $("#sd_set_chart").validate({
    rules:{
      sd_min_chart:{
        required: true,
        min: 0,
        max: 200,
        number: true
      },
      sd_max_chart:{
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

  $(function () {
      $(document).ready(function() {
          Highcharts.setOptions({
              global: {
                  useUTC: false
              }
          });
      
          var chart;
          $('#line_rms').highcharts({
              credits:{
                enabled: false
              },
              chart: {
                  type: 'spline',
                  borderWidth: 1,
                  animation: Highcharts.svg, // don't animate in old IE
                  marginRight: 10,
                  events: {
                      load: function() {
      
                          // set up the updating of the chart each second
                          var series = this.series[0];
                          setInterval(function() {
                              var json = $.ajax({
                               url: 'json_rms_value.php?radio_id=<?=$radio_id?>', // make this url point to the data file
                               dataType: 'json',
                               async: false
                              }).responseText;

                              var dataJson = eval(json);
                              for (var i in dataJson){
                                 y_data = dataJson[i].value;                            
                              } 
                              var x = (new Date()).getTime(), // current time
                                  y = y_data;
                              series.addPoint([x, y], true, true);
                          }, 1000);
                      }
                  }
              },
              title: {
                  text: 'Live data'
              },
              xAxis: {
                  type: 'datetime',
                  tickPixelInterval: 150,
                  
              },
              yAxis: {
                gridLineWidth: 0,
                // minorGridLineWidth: 1,
                  title: {
                      text: 'R.M.S Value'
                  },
                  min: <?=$aGraficoRms[0]['valor_minimo']?>,
                  max: <?=$aGraficoRms[0]['valor_maximo']?>,
                  plotBands: [{ // Light air
                    from: <?php echo $rms_semi_ropping;?>,
                    to: <?php echo $rms_semi_ropping+0.07;?>,
                    color: '#FFFFFF',
                    label: {
                        // text: 'Ideal',
                        align: 'left',
                        // y: <?php echo $rms_semi_ropping;?>,
                        rotation: -90,
                        verticalAlign: 'bottom',
                        style: {
                            color: '#606060'
                        },
                      }
                    }
                  ,{
                    gridLineWidth: 0,  
                    from: <?php echo $rms_semi_ropping+0.15;?>,
                    to: <?php echo $rms_semi_ropping+0.22?>,
                    color: '#FFF300',
                    label: {
                        // text: 'Semiropping',
                        align: 'left',
                        verticalAlign: 'middle',
                        // y: -<?php echo $rms_semi_ropping;?>,
                        rotation: -90,
                        style: {
                            color: '#606060'
                        }
                    }
                  }
                  ,{
                    gridLineWidth: 0,  
                    from: <?php echo $rms_ropping;?>,
                    to: <?php echo $rms_ropping+0.05;?>,
                    color: '#E13100',
                    label: {
                        // text: 'Ropping',
                        align: 'left',
                        verticalAlign: 'top',
                        // y: -40,
                        rotation: -90,
                        style: {
                            color: '#606060'
                        }
                    }
                  }
                ]
                ,
                  plotLines: [{
                      value: 0,
                      width: 1,
                      color: '#808080'
                  }]
              },
              tooltip: {
                  formatter: function() {
                          return '<b>'+ this.series.name +'</b><br/>'+
                          Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) +'<br/>'+
                          Highcharts.numberFormat(this.y, 2);
                  }
              },
              legend: {
                  enabled: false
              },
              exporting: {
                  enabled: false
              },
              series: [{
                  name: 'R.M.S Value',
                  data: (function() {
                      // generate an array of random data
                      var data = [],
                          time = (new Date()).getTime(),
                          i;
                      var json = $.ajax({
                               url: 'json_rms_value.php?radio_id=<?=$radio_id?>', // make this url point to the data file
                               dataType: 'json',
                               async: false
                              }).responseText;

                              var dataJson = eval(json);
                              for (var i in dataJson){
                                 y_data = dataJson[i].value;                            
                              } 
                      for (i = -19; i <= 0; i++) {
                          data.push({
                              x: time + i * 1000,
                              y: y_data
                          });
                      }
                      return data;
                  })()
              }]
          });
      });
      
  });


  $(function () {
    
      $('#gauge_rms').highcharts({
    
        chart: {
            type: 'gauge',
            plotBackgroundColor: null,
            plotBackgroundImage: null,
            plotBorderWidth: 0,
            plotShadow: false,
            backgroundColor:'transparent'
        },
        exporting:{
          enabled: false
        },
        credits:{
          enabled: false
        },
        title: {
            text: 'Gauge'
        },
        
        pane: {
            startAngle: -150,
            endAngle: 150,
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
                backgroundColor: '#f5f5f5',
                borderWidth: 0,
                outerRadius: '105%',
                innerRadius: '103%'
            }]
        },
           
        // the value axis
        yAxis: {
            min: <?=$aGraficoRms[0]['valor_minimo']?>,
            max: <?=$aGraficoRms[0]['valor_maximo']?>,
            
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
                from: <?=$aGraficoRms[0]['valor_minimo']?>,
                to: <?php echo $rms_semi_ropping;?>,
                color: '#55BF3B' // green
            }, 
            {
                from: <?php echo $rms_semi_ropping;?>,
                to: <?php echo $rms_ropping;?>,
                color: '#DDDF0D' // yellow
            }, 
            {
                from: <?php echo $rms_ropping;?>,
                to:  <?=$aGraficoRms[0]['valor_maximo']?>,
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
               url: 'json_rms_value.php?radio_id=<?=$radio_id?>', // make this url point to the data file
               dataType: 'json',
               async: false
              }).responseText;

              var dataJson = eval(json);
              for (var i in dataJson){
                
                 y_data = dataJson[i].value;                         
              }
              if(y_data < <?php echo $rms_semi_ropping;?>){
                  $('#semiropping').hide();
                  $('#ropping').hide();
                  $('#normal').show();
              }else if (y_data >= <?php echo $rms_semi_ropping;?> && y_data < <?php echo $rms_ropping;?>){
                    // $("#status").text('splash').css("color","yellow").show();
                    $('#semiropping').show();
                    $('#ropping').hide();
                    $('#normal').hide();
                }else if(y_data >= <?php echo $rms_ropping;?>){
                  $('#semiropping').hide();
                  $('#ropping').show();
                  $('#normal').hide();
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
</script>

<!-- STATUS DESVIACION -->


<script type="text/javascript">
  $(function () {
      $(document).ready(function() {
          Highcharts.setOptions({
              global: {
                  useUTC: false
              }
          });
      
          var chart;
          $('#line_sd').highcharts({
              credits:{
                enabled: false
              },
              chart: {
                  type: 'spline',
                  borderWidth: 1,
                  animation: Highcharts.svg, // don't animate in old IE
                  marginRight: 10,
                  events: {
                      load: function() {
      
                          // set up the updating of the chart each second
                          var series = this.series[0];
                          setInterval(function() {
                              var json = $.ajax({
                               url: 'json_sd_value.php?radio_id=<?=$radio_id?>', // make this url point to the data file
                               dataType: 'json',
                               async: false
                              }).responseText;

                              var dataJson = eval(json);
                              for (var i in dataJson){
                                 y_data = dataJson[i].value;                            
                              } 
                              var x = (new Date()).getTime(), // current time
                                  y = y_data;
                              series.addPoint([x, y], true, true);
                          }, 1000);
                      }
                  }
              },
              title: {
                  text: 'Live data'
              },
              xAxis: {
                  type: 'datetime',
                  tickPixelInterval: 150
              },
              yAxis: {
                  title: {
                      text: 'SD Value'
                  },
                  min: <?=$aGraficoSD[0]['valor_minimo']?>,
                  max: <?=$aGraficoSD[0]['valor_maximo']?>,
                  plotLines: [{
                      value: 0,
                      width: 1,
                      color: '#808080'
                  }]
              },
              tooltip: {
                  formatter: function() {
                          return '<b>'+ this.series.name +'</b><br/>'+
                          Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) +'<br/>'+
                          Highcharts.numberFormat(this.y, 2);
                  }
              },
              legend: {
                  enabled: false
              },
              exporting: {
                  enabled: false
              },
              series: [{
                  name: 'SD Value',
                  data: (function() {
                      // generate an array of random data
                      var data = [],
                          time = (new Date()).getTime(),
                          i;
                      var json = $.ajax({
                               url: 'json_sd_value.php?radio_id=<?=$radio_id?>', // make this url point to the data file
                               dataType: 'json',
                               async: false
                              }).responseText;

                              var dataJson = eval(json);
                              for (var i in dataJson){
                                 y_data = dataJson[i].value;                            
                              } 
                      for (i = -19; i <= 0; i++) {
                          data.push({
                              x: time + i * 1000,
                              y: y_data
                          });
                      }
                      return data;
                  })()
              }]
          });
      });
      
  });


  $(function () {
    
      $('#gauge_sd').highcharts({
    
        chart: {
            type: 'gauge',
            plotBackgroundColor: null,
            plotBackgroundImage: null,
            plotBorderWidth: 0,
            plotShadow: false,
            backgroundColor:'transparent'
        },
        exporting:{
          enabled: false
        },
        credits:{
          enabled: false
        },
        title: {
            text: 'Gauge'
        },
        
        pane: {
            startAngle: -150,
            endAngle: 150,
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
            min: <?=$aGraficoSD[0]['valor_minimo']?>,
            max: <?=$aGraficoSD[0]['valor_maximo']?>,
            
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
                from: <?=$aGraficoSD[0]['valor_minimo']?>,
                to: <?php echo $sd_semi_ropping;?>,
                color: '#55BF3B' // green
            }, 
            {
                from: <?php echo $sd_semi_ropping;?>,
                to: <?php echo $sd_ropping;?>,
                color: '#DDDF0D' // yellow
            }, 
            {
                from: <?php echo $sd_ropping;?>,
                to:  <?=$aGraficoSD[0]['valor_maximo']?>,
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
               url: 'json_sd_value.php?radio_id=<?=$radio_id?>', // make this url point to the data file
               dataType: 'json',
               async: false
              }).responseText;

              var dataJson = eval(json);
              for (var i in dataJson){             
                 y_data = dataJson[i].value;  
                 console.log(y_data);
                                      
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
</script>


<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'footer.php');

?>