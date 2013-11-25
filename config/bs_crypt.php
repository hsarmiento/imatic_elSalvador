<?php

class BSCrypt
{
	private $m_sSalt;
	private $m_cAlgo;
	private $m_sRound;
	private $m_sCryptSalt;
	private $m_sHash;

	public function __construct($insHash = "")
	{		
		$this->m_sSalt = '';
		$this->m_cAlgo = '6';
		$this->m_sRound = '9000';
		$this->m_sCryptSalt = '';
		if (!empty($insHash))
		{			
			$this->m_sHash = $insHash;
		}
		else
		{
			$this->m_sHash = '';
		}
	}

	private function SetSalt($insSalt)
	{
		$this->m_sSalt = $insSalt;
	}

	private function MakeCryptSalt() 
	{
		$this->m_sCryptSalt = '$' . $this->m_cAlgo . '$rounds=' . $this->m_sRound . '$' . $this->m_sSalt;
	}

	private function MakeHash($insPassword)
	{
		$this->m_sHash = crypt($insPassword, $this->m_sCryptSalt);
	}

	public function GetCryptPassword($insPassword, $insSalt)
	{
		$this->SetSalt($insSalt);
		$this->MakeCryptSalt();
		$this->MakeHash($insPassword);
		return $this->m_sHash;
	}

	public function IsEqual($insPassword)
	{		
		if (crypt($insPassword,$this->m_sHash) == $this->m_sHash)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}

?>
