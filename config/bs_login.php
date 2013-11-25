<?php

require_once('bs_model.php');
require_once('bs_crypt.php');
require_once('bs_functions_generals.php');

class BSLogin
{
	// public $UserMail;
	private $sUserName;
	private $sUserPass;
	private $iUserId;
	// private $iUserType;
	private $aPermisos;

	public function __construct()
	{
		// $this->sUserMail = "";
		$this->sUserName = "";
		$this->sUserPass = "";
		$this->iUserId = "";
		// $this->iUserTipo = "";
		$this->aPermisos = array('admin' => -1, 'supervisor' => -1);
	}

	//Funcion que logea a un usuario por username y password enviado por post
	public function Login()
	{
		if (isset($_POST['session_action']) && !strcmp($_POST['session_action'], "login"))
		{
			session_start();
			$aPost = post_request();			
			if ($this->Authenticate($aPost['username'], $aPost['password']))
			{
				$count = 0;
				$oCount = new BSModel();
				$query_count = "SELECT COUNT(*) as count from usuarios where permisos = 0 and estado_online = 1;";
				$aCount = $oCount->Select($query_count);
				$count = $aCount[0]['count'];

				$oModel = new BSModel();		
				$aUser = $oModel->Get('usuarios', array('username' => $aPost['username']));			
				if($aUser[0]['permisos'] == 0 && $count >= 2){
					return -2;
				}

				$query_update = "UPDATE usuarios set ultimo_acceso = '".date('Y-m-d H:i:s')."' where id = ".$aUser[0]['id']." and permisos = 0;";
				$update = $oModel->Select($query_update);
				$_SESSION['username'] = $aUser[0]['username'];
				$_SESSION['user_nombres'] = $aUser[0]['nombres'];
				$_SESSION['user_apellidos'] = $aUser[0]['apellidos'];
				$_SESSION['user_password'] = $aUser[0]['password'];
				$_SESSION['user_id'] = $aUser[0]['id'];
				$_SESSION['usertype'] = $aUser[0]['permisos'];

				// require_once($_SERVER['DOCUMENT_ROOT'].'/imatic_elSalvador/'.'log_entry_website.php');
				header('Location: home.php');
			}
			else
			{
				return -1;
			}
		}
	}

	//funcion que deslogea, desetea variables y destruye las sessiones
	public function Logout()
	{
		session_unset();
    	session_destroy();  
	}

	//funcion verifica si existe algun usuario logeado, sino redirecciona al index
	public function IsLogged($insAdmin = "", $insSuper = "")
	{
		if (!empty($insAdmin))
		{
			$this->aPermisos['admin'] = 1;
		}
		if (!empty($insSuper))
		{
			$this->aPermisos['supervisor'] = 0;
		}
		if (isset($_SESSION['usertype']))
		{
			if ($_SESSION['usertype'] != $this->aPermisos['admin'])
			{
				if ($_SESSION['usertype'] != $this->aPermisos['supervisor'])
				{
					// $this->Logout();
					header('Location: home.php');
				}
				
			}
		}
	}

	//funcion que hace el match entre el username y el password con el guardado en la bd
	//returna true en caso de xito y false en caso contrario
	public function Authenticate($insUserName, $insUserPass)
	{
		$oModel = new BSModel();		
		$aUser = $oModel->Get('usuarios', array('username' => $insUserName));
		$oCrypt = new BSCrypt($aUser[0]['password']);
		if (!strcmp($aUser[0]['username'], $insUserName) && $oCrypt->IsEqual($insUserPass))		
		{			
			return true;
		}
		else
		{			
			return false;
		}
	}

	//funcion que verifica si existe alguna session activa
	//retorna true en caso de exito y false en caso contrario
	public function ExistAnySession()
	{
		session_start();		
		if (isset($_SESSION['username']) && isset($_SESSION['user_password']) && isset($_SESSION['user_id']))
		{
			// $this->sUserMail = $_SESSION['usermail'];
			$this->sUserName = $_SESSION['username'];
			$this->sUserPass = $_SESSION['user_password'];
			$this->iUserId = $_SESSION['user_id'];
			// $this->iUserTipo = $_SESSION['usertype'];			
		}
		else
		{
			$this->Logout();
			header('Location: index.php');
		}
	}
}

?>