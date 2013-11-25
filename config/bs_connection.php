<?php 

class BSConnection extends PDO
{	
	// private $m_strDNS = "mysql";
	// private $m_strHostname = "localhost";
	// private $m_strDatabase = "demo_cavex";
	// private $m_strUsername = "root";
	// private $m_strPassword = "cba123*";

	private $m_strDNS = "mysql";
	private $m_strHostname = "localhost";
	private $m_strDatabase = "cavex_elsalvador";
	private $m_strUsername = "root";
	private $m_strPassword = "legend";

	// private $m_strDNS = "mysql";
	// private $m_strHostname = "localhost";
	// private $m_strDatabase = "imatic_elSalvador";
	// private $m_strUsername = "imatic";
	// private $m_strPassword = "JAplOt2OUv8R";
		
	public function __construct()
	{
		//genera el string de conexion
		$strConnection = $this->m_strDNS . ":host=" . $this->m_strHostname . ";dbname=" . $this->m_strDatabase;
        
        try {
        	parent::__construct($strConnection, $this->m_strUsername, $this->m_strPassword);
        	// echo "tamos conectados po rey";
        } catch (PDOException $e) {
        	echo 'Connection failed: ' . $e->getMessage();
		}
    }
}

?>
