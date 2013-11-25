<?php
	
	function post_request(){
		$postArray = $_POST;
		if(!empty($postArray)){
			return $postArray;
		}else{
			return -1;
		}
	}

	function get_request(){
		$getArray = $_GET;
		if(!empty($getArray)){
			foreach($getArray as $val){
				if($val === "" || !isset($val)){
					return 0;
				}	
			}
			return $getArray;
		}
	}

	function curPageURL() {
		$pageURL = 'http';
		// if ($_SERVER["HTTPS"] == "on")
		if (isset( $_SERVER["HTTPS"] ) && strtolower( $_SERVER["HTTPS"] ) == "on")		
		{
			$pageURL .= "s";
		}
		$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80")
		{
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		}
		else
		{
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}

	//formato: 00/00/0000
	function day_date($fecha = "")
	{ 	
		$fecha= empty($fecha) ? date('d/m/Y') : $fecha;
		$dias = array('domingo','lunes','martes','miércoles','jueves','viernes','sábado');
		$dd   = explode('/',$fecha);
		$ts   = mktime(0,0,0,$dd[1],$dd[0],$dd[2]);
		// return $dias[date('w',$ts)].'/'.date('m',$ts).'/'.date('Y',$ts);
		return $dias[date('w',$ts)];
	}

?>