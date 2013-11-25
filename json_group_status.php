<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'routes.php');
require_once($aRoutes['paths']['config'].'bs_functions_generals.php');
require_once($aRoutes['paths']['config'].'bs_model.php');

$group = $_GET['group'];
// print_r($_GET);

$oModel = new BSModel();
$query = "SELECT t1.id as radio_id, t2.rms_normal as rms_normal,
t2.rms_max_normal_porcentaje as rms_max_normal_porcentaje, t2.rms_ropping_porcentaje as rms_ropping_porcentaje from radios as t1 left join parametros as t2 on t1.id = t2.radio_id where t1.grupo = '".$group."' and t1.estado = 1 order by t1.identificador;";
// echo $query;
$aRadios = $oModel->Select($query);
// print_r($aRadios);
$status = '';

foreach ($aRadios as $radio) {
	$query_rms = "SELECT valor as valor from rms where radio_id = ".$radio['radio_id']." order by fecha_hora desc limit 1;";
	// echo $query_rms;
	$aRms = $oModel->Select($query_rms);
	$rms_semi_ropping = ($radio['rms_normal'])*(1+$radio['rms_max_normal_porcentaje']/100);
	$rms_ropping = ($radio['rms_normal'])*(1+$radio['rms_ropping_porcentaje']/100);
	// echo 'semi:'.$rms_semi_ropping;
	// echo '<br>';
	// echo 'ropping:'.$rms_ropping;
	// echo '<br>';
	// echo 'valor:'.$aRms[0]['valor'];
	// echo '<br>';
	if(!empty($rms_ropping) && !empty($rms_semi_ropping)){
		if($aRms[0]['valor'] < $rms_semi_ropping && $status == ''){
		$status = 'normal';
		}elseif($rms_semi_ropping <= $aRms[0]['valor'] && $aRms[0]['valor'] < $rms_ropping && ($status == '' || $status == 'normal')){
			$status = 'semiroping';
		}elseif($rms_ropping <= $aRms[0]['valor'] && ($status == '' || $status == 'normal' || $status == 'semiroping')){
			$status = 'roping';
		}
		// echo $status;
		// echo '<br>';
	}	
}


$arr = array(); 

$arr[] = array('status' => $status);

echo json_encode($arr);

?>