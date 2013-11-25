
<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/demo_cavex/'.'routes.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/demo_cavex/'.'header.php');
// require_once($aRoutes['paths']['config'].'st_functions_generals.php');
require_once($aRoutes['paths']['config'].'bs_model.php');
$oLogin = new BSLogin();
$oLogin->IsLogged("admin","supervisor");
$oModel = new BSModel();
$query = "SELECT * FROM parametros order by id desc limit 1;";
$aParametros = $oModel->Select($query);

$rms = $aParametros[0]['rms'];
$limite = ($rms)*(1+$aParametros[0]['porcentaje']/100);

?>

<div class="container container-body">
	<h2>Overview</h2>
	<div class="hidrociclon"><img height="400" width="400" src="assets/img/Overview_2.png"></div>
	<div id="gauge1" class="gauge"></div>
	<div id="gauge2" class="gauge gauge_neutro"></div>
	<div id="gauge3" class="gauge gauge_neutro"></div>
	<div id="gauge4" class="gauge gauge_neutro"></div>
	<div id="gauge5" class="gauge gauge_neutro"></div>
	<div id="gauge6" class="gauge gauge_neutro"></div>
</div>

<script type="text/javascript" src="<? echo $aRoutes['paths']['js']?>highcharts.js"></script>
<script type="text/javascript" src="<? echo $aRoutes['paths']['js']?>/modules/exporting.js"></script>
<script type="text/javascript" src="<? echo $aRoutes['paths']['js']?>highcharts-more.js"></script>

<script type="text/javascript">

	$(function () {
    
      $('#gauge1').highcharts({
    
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
            text: 'Gauge_1'
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
            min: 0,
            max: <?php echo 1024;?>,
            
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
                from: 0,
                to: <?php echo $limite;?>,
                color: '#55BF3B' // green
            }, 
            // {
            //     from: <?php echo $rms;?>,
            //     to: <?php echo $limite?>,
            //     color: '#DDDF0D' // yellow
            // }, 
            {
                from: <?php echo $limite?>,
                to:  1024,
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
               url: 'json_status_rms.php', // make this url point to the data file
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
              
              newVal = inc;
              
              point.update(newVal);
              
          }, 1000);
      }
    });
  });

$(function () {
	    $('#gauge2').highcharts({
	  
	      chart: {
	          type: 'gauge',
	          plotBackgroundColor: null,
	          plotBackgroundImage: null,
	          plotBorderWidth: 0,
	          plotShadow: false
	      },
	      credits:{
                enabled: false
              },
	      title: {
	          text: 'Gauge_2'
	      },
	      exporting:{
	          enabled: false
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
	          min: 0,
	          max: 7,
	          
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
	              from: 0,
	              to: 3,
	              color: '#55BF3B' // green
	          }, {
	              from: 3,
	              to: 5,
	              color: '#DDDF0D' // yellow
	          }, {
	              from: 5,
	              to: 7,
	              color: '#DF5353' // red
	          }]        
	      },
	  
	      series: [{
	          // name: 'Speed',
	          data: [0],
	          tooltip: {
	              valueSuffix: ' '
	          }
	      }]
	  
	  }, 
	  // Add some life
	  function (chart) {
	    if (!chart.renderer.forExport) {
	        setInterval(function () {
	            var json = $.ajax({
	             url: 'json_status_rms.php', // make this url point to the data file
	             dataType: 'json',
	             async: false
	            }).responseText;

	            var dataJson = eval(json);
	            for (var i in dataJson){
	              
	               y_data = 0;  
	              //  if (y_data> 5){
	              //   $("#status").text('critico').css("color","red").show();
	              // }else{
	              //   $("#status").hide();
	              // }
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

$(function () {
	    $('#gauge3').highcharts({
	  
	      chart: {
	          type: 'gauge',
	          plotBackgroundColor: null,
	          plotBackgroundImage: null,
	          plotBorderWidth: 0,
	          plotShadow: false
	      },
	      credits:{
                enabled: false
          },
	      title: {
	          text: 'Gauge_3'
	      },
	      exporting:{
	          enabled: false
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
	          min: 0,
	          max: 7,
	          
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
	              from: 0,
	              to: 3,
	              color: '#55BF3B' // green
	          }, {
	              from: 3,
	              to: 5,
	              color: '#DDDF0D' // yellow
	          }, {
	              from: 5,
	              to: 7,
	              color: '#DF5353' // red
	          }]        
	      },
	  
	      series: [{
	          // name: 'Speed',
	          data: [0],
	          tooltip: {
	              valueSuffix: ' '
	          }
	      }]
	  
	  }, 
	  // Add some life
	  function (chart) {
	    if (!chart.renderer.forExport) {
	        setInterval(function () {
	            var json = $.ajax({
	             url: 'json_status_rms.php', // make this url point to the data file
	             dataType: 'json',
	             async: false
	            }).responseText;

	            var dataJson = eval(json);
	            for (var i in dataJson){
	              
	               y_data = 0;  
	              //  if (y_data> 5){
	              //   $("#status").text('critico').css("color","red").show();
	              // }else{
	              //   $("#status").hide();
	              // }
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

	$(function () {
	    $('#gauge4').highcharts({
	  
	      chart: {
	          type: 'gauge',
	          plotBackgroundColor: null,
	          plotBackgroundImage: null,
	          plotBorderWidth: 0,
	          plotShadow: false
	      },
	      credits:{
                enabled: false
          },
	      title: {
	          text: 'Gauge_4'
	      },
	      exporting:{
	          enabled: false
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
	          min: 0,
	          max: 7,
	          
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
	              from: 0,
	              to: 3,
	              color: '#55BF3B' // green
	          }, {
	              from: 3,
	              to: 5,
	              color: '#DDDF0D' // yellow
	          }, {
	              from: 5,
	              to: 7,
	              color: '#DF5353' // red
	          }]        
	      },
	  
	      series: [{
	      	          // name: 'Speed',
	      	          data: [0],
	      	          tooltip: {
	      	              valueSuffix: ' '
	      	          }
  	      }]
	  
	  }, 
	  // Add some life
	  function (chart) {
	    if (!chart.renderer.forExport) {
	        setInterval(function () {
	            var json = $.ajax({
	             url: 'json_status_rms.php', // make this url point to the data file
	             dataType: 'json',
	             async: false
	            }).responseText;

	            var dataJson = eval(json);
	            for (var i in dataJson){
	              
	               y_data = 0;  
	              //  if (y_data> 5){
	              //   $("#status").text('critico').css("color","red").show();
	              // }else{
	              //   $("#status").hide();
	              // }
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

$(function () {
	    $('#gauge5').highcharts({
	  
	      chart: {
	          type: 'gauge',
	          plotBackgroundColor: null,
	          plotBackgroundImage: null,
	          plotBorderWidth: 0,
	          plotShadow: false
	      },
	      credits:{
                enabled: false
          },
	      title: {
	          text: 'Gauge_5'
	      },
	      exporting:{
	          enabled: false
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
	          min: 0,
	          max: 7,
	          
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
	              from: 0,
	              to: 3,
	              color: '#55BF3B' // green
	          }, {
	              from: 3,
	              to: 5,
	              color: '#DDDF0D' // yellow
	          }, {
	              from: 5,
	              to: 7,
	              color: '#DF5353' // red
	          }]        
	      },
	  
	      series: [{
	          // name: 'Speed',
	          data: [0],
	          tooltip: {
	              valueSuffix: ' '
	          }
	      }]
	  
	  }, 
	  // Add some life
	  function (chart) {
	    if (!chart.renderer.forExport) {
	        setInterval(function () {
	            var json = $.ajax({
	             url: 'json_status_rms.php', // make this url point to the data file
	             dataType: 'json',
	             async: false
	            }).responseText;

	            var dataJson = eval(json);
	            for (var i in dataJson){
	              
	               y_data = 0;  
	              //  if (y_data> 5){
	              //   $("#status").text('critico').css("color","red").show();
	              // }else{
	              //   $("#status").hide();
	              // }
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

$(function () {
	    $('#gauge6').highcharts({
	  
	      chart: {
	          type: 'gauge',
	          plotBackgroundColor: null,
	          plotBackgroundImage: null,
	          plotBorderWidth: 0,
	          plotShadow: false
	      },
	      credits:{
                enabled: false
              },
          exporting:{
	          enabled: false
	        },
	      title: {
	          text: 'Gauge_6'
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
	          min: 0,
	          max: 7,
	          
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
	              from: 0,
	              to: 3,
	              color: '#55BF3B' // green
	          }, {
	              from: 3,
	              to: 5,
	              color: '#DDDF0D' // yellow
	          }, {
	              from: 5,
	              to: 7,
	              color: '#DF5353' // red
	          }]        
	      },
	  
	      series: [{
	          // name: 'Speed',
	          data: [0],
	          tooltip: {
	              valueSuffix: ' '
	          }
	      }]
	  
	  }, 
	  // Add some life
	  function (chart) {
	    if (!chart.renderer.forExport) {
	        setInterval(function () {
	            var json = $.ajax({
	             url: 'json_status_rms.php', // make this url point to the data file
	             dataType: 'json',
	             async: false
	            }).responseText;

	            var dataJson = eval(json);
	            for (var i in dataJson){
	              
	               y_data = 0;  
	              //  if (y_data> 5){
	              //   $("#status").text('critico').css("color","red").show();
	              // }else{
	              //   $("#status").hide();
	              // }
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
require_once($_SERVER['DOCUMENT_ROOT'].'/demo_cavex/'.'footer.php');

?>