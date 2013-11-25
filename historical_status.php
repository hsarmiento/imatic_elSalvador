<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'routes.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'header.php');
require_once($aRoutes['paths']['config'].'bs_model.php');


$radio_id = $_GET['radio_id'];
$datetime = $_GET['datetime'];
$type = $_GET['type'];
$oModel = new BSModel();
$query_radio = "select * from radios where id = ".$radio_id.";";
$aRadio  = $oModel->Select($query_radio);
if($type == '2'){
    $title = 'Chart for radio identifier: '.$aRadio[0]['identificador']." .Status is Ropping";
}elseif($type == '4'){
    $title = 'Chart for radio identifier: '.$aRadio[0]['identificador']." .Status is Semi Ropping";
}


$oModel = new BSModel();
$query_rms = "SELECT valor, fecha_hora from rms where radio_id = ".$radio_id." and date_sub('".$datetime."',interval 5 minute) < fecha_hora and date_add('".$datetime."',interval 5 minute) > fecha_hora order by fecha_hora asc limit 10000;";
$aResult = $oModel->Select($query_rms);


$aRms = array();
$aTime = array();
foreach ($aResult as $value) {
	$aRms[] = $value['valor']/1;
    $aTime[] = "[".(mktime(date("H", strtotime($value['fecha_hora']))-4, date("i", strtotime($value['fecha_hora'])), date("s", strtotime($value['fecha_hora'])), date("m", strtotime($value['fecha_hora'])), date("d", strtotime($value['fecha_hora'])), date("Y", strtotime($value['fecha_hora'])))*1000).",".$value['valor']."]";
}


?>

<div class="container container-body contenedor">   
    <div class="span11">
        <div id="container"></div>
    </div>
</div>


<script type="text/javascript" src="<? echo $aRoutes['paths']['js']?>highcharts.js"></script>
<script type="text/javascript" src="<? echo $aRoutes['paths']['js']?>/modules/exporting.js"></script>
<script type="text/javascript" src="<? echo $aRoutes['paths']['js']?>highcharts-more.js"></script>



<script type="text/javascript">
	
	$(function () {
        $('#container').highcharts({
            title: {
                text: '<?=$title?>',
                x: -20 //center
            },
            subtitle: {
                text: '<?=$datetime?>',
                x: -20
            },
            exporting: {
                  enabled: false
            },
            credits:{
                enabled: false
            },
            xAxis: {
                type: 'datetime',
                dateTimeLabelFormats: { // don't display the dummy year
                    month: '%e. %b',
                    year: '%b'
                }
            },
            yAxis: {

                title: {
                    text: 'RMS value'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: ''
            },
            plotOptions: {
                series: {
                    marker: {
                        enabled: true,
                        symbol: 'circle',
                        radius: 2
                    }
                }
            },
            series: [{
                name: 'rms',
                data: [<?php echo join($aTime, ",");?>]
                
            }]
        });
    });
</script>