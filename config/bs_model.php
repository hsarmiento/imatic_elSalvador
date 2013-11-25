<?php

require_once('bs_connection.php');

class BSModel
{
	private $m_db;
	private $m_sStatement;
	private $m_sQuery;
	private $m_bSuccess;

	public function __construct()
	{
		$this->m_sStatement = "";
		$this->m_sQuery = "";
		$this->m_bSuccess = false;
	}
	//********************************************************************************

	//Metodo generico para realizar inserts en la bd
	//argumentos:
	//$insTable: nombre de la tabla (string)
	//$inaAttr: columnas de la tabla (array), nombre_columna (string) => valor
	//return:
	//true en caso de exito y false en caso contrario
	public function Create($insTable, $inaAttr)
	{
		//se abre la conexion a la bd
		$this->m_db = new BSConnection();

		$aPlaceholders = array_fill(0, count($inaAttr), '?');
		$keys = $values = array();
		foreach ($inaAttr as $key => $value)
		{
			$keys[] = $key;
			if (empty($value))
			{
				if (is_int($value) && $value === 0)
				{
					$values[] = $value;
				}
				else
				{
					$values[] = null;
				}
			}
			else
			{
				$values[] = $value;
			}
		}

		$this->m_sQuery = "INSERT INTO " . $insTable . " (" . implode(',', $keys) . ") VALUES (" . implode(',', $aPlaceholders) . ")";
		$this->m_sStatement = $this->m_db->prepare($this->m_sQuery);
		$this->m_bSuccess = $this->m_sStatement->execute($values);		
		//cierra la conexion a la bd
		$this->m_db = null;

		return $this->m_bSuccess;
	}
	//********************************************************************************

	//Metodo generico para realizar select en la bd
	//argumentos:
	//$insTable: nombre de la tabla (string)
	//$inaAttr: Clausula WHERE
	//columnas de la tabla (array), nombre_columna (string) => valor,
	//tambien se puede pasar como un array multidimensional de la forma
	//nombre => array( 'operador logico' => 'valor')
	//finalmente tambien se le puede pasar el string de la consulta
	//ej: 'id > 1 and name = 'cba'
	//sino se pasa nada se asume select * from table;
	//$insResult: string con los campos de la tabla que se recuperaran,
	//de no pasar nada, se devuelven todos.
	//return:
	//las columnas de la bd en caso de exito y false en caso contrario.
	public function Get($insTable, $inaAttr = "", $insResult = "*", $insOrderLimit = "")
	{
		$this->m_db = new BSConnection();
		
		$sAttr = $aValues = array();

		if (is_array($insResult))
		{
			$sResult = implode(',', $insResult);
		}
		else
		{
			$sResult = $insResult;
		}
		
		if(is_array($inaAttr))
		{
			$this->m_sQuery = "SELECT " . $sResult . " FROM " . $insTable . " WHERE ";
			foreach ($inaAttr as $key => $value)
			{				
				if (is_array($value))
				{
					$sLast = end(array_keys($value));
					foreach ($value as $k => $v)
					{						
						if (!strcmp($sLast, $k))
						{
							$this->m_sQuery .= $key . " " . $k . " ? ";
							if (strcmp(end(array_keys($inaAttr)), $key))
							{
								$this->m_sQuery .= " AND ";
							}
						}
						else
						{
							$this->m_sQuery .= $key . " " . $k . " ? AND ";
						}
						$aValues[] = !empty($v) ? $v : null;
					}
				}
				else
				{					
					if (!strcmp(end(array_keys($inaAttr)), $key))
					{
						$this->m_sQuery .= $key . " = ?";						
					}
					else
					{
						$this->m_sQuery .= $key . " = ? AND ";						
					}
					$aValues[] = !empty($value) ? $value : null;
				}
			}
			$this->m_sQuery .= " " . $insOrderLimit;
			$this->m_sStatement = $this->m_db->prepare($this->m_sQuery);
			$this->m_bSuccess = $this->m_sStatement->execute($aValues);
		}

		if(is_string($inaAttr))
		{
			if(empty($inaAttr))
			{
				$this->m_sQuery = "SELECT " . $sResult . " FROM " . $insTable . " " . $insOrderLimit;
			}
			else
			{
				$this->m_sQuery = "SELECT " . $sResult . " FROM " . $insTable . " WHERE " . $inaAttr  . " " . $insOrderLimit;
			}
			$this->m_sStatement = $this->m_db->prepare($this->m_sQuery);
			$this->m_bSuccess = $this->m_sStatement->execute();
		}
		$this->m_db = null;
		if ($this->m_bSuccess)
		{
			return $this->m_sStatement->fetchAll();
		}
		else
		{
			return $this->m_bSuccess;
		}		
	}
	//********************************************************************************

	//Metodo generico para borrar registros de la bd
	//argumentos:
	//$insTable: nombre de la tabla (string)
	//$inaAttr: Clausula WHERE
	//columnas de la tabla (array), nombre_columna (string) => valor,
	//tambien se puede pasar como un array multidimensional de la forma
	//nombre => array( 'operador logico' => 'valor')
	//finalmente tambien se le puede pasar el string de la consulta
	//ej: 'id > 1 and name = 'cba'
	//sino se pasa nada se asume delete from table;
	//$insOrderLimit: string con clausula order by y/o limit 
	//return:
	//true en caso de exito y false en caso contrario.
	public function Destroy($insTable, $inaAttr = "", $insOrderLimit = "")
	{
		$this->m_db = new BSConnection();

		$sAttr = $aValues = array();
		$sOrderLimit = "";
		if (is_array($insOrderLimit))
		{
			foreach ($insOrderLimit as $key => $value)
			{
				$sOrderLimit .= $key . " " . $value . " ";
			}			
		}
		else
		{
			$sOrderLimit = $insOrderLimit;
		}

		if(is_array($inaAttr))
		{
			$this->m_sQuery = "DELETE FROM " . $insTable . " WHERE ";
			foreach ($inaAttr as $key => $value)
			{				
				if (is_array($value))
				{					
					foreach ($value as $k => $v)
					{						
						if (!strcmp(end(array_keys($value)), $k))
						{
							$this->m_sQuery .= $key . " " . $k . " ? ";
							if (strcmp(end(array_keys($inaAttr)), $key))
							{
								$this->m_sQuery .= " AND ";
							}
						}
						else
						{
							$this->m_sQuery .= $key . " " . $k . " ? AND ";
						}
						$aValues[] = !empty($v) ? $v : null;
					}
				}
				else
				{					
					if (!strcmp(end(array_keys($inaAttr)), $key))
					{
						$this->m_sQuery .= $key . " = ?";						
					}
					else
					{
						$this->m_sQuery .= $key . " = ? AND ";						
					}
					$aValues[] = !empty($value) ? $value : null;
				}
			}
			$this->m_sQuery .= " " . $sOrderLimit;
			$this->m_sStatement = $this->m_db->prepare($this->m_sQuery);
			$this->m_bSuccess = $this->m_sStatement->execute($aValues);
		}

		if(is_string($inaAttr))
		{
			if(empty($inaAttr))
			{
				$this->m_sQuery = "DELETE FROM " . $insTable . " " . $sOrderLimit;
			}
			else
			{
				$this->m_sQuery = "DELETE FROM " . $insTable . " WHERE " . $inaAttr . " " . $sOrderLimit;
			}			
			$this->m_sStatement = $this->m_db->prepare($this->m_sQuery);
			$this->m_bSuccess = $this->m_sStatement->execute();
		}
		$this->m_db = null;
		return $this->m_bSuccess;
	}
	//********************************************************************************

	//Metodo generico para borrar registros de la bd
	//argumentos:
	//$insTable: nombre de la tabla (string)
	//$inaCampos: Clausula SET
	//columnas de la tabla a modificar (array), nombre_columna (string) => valor,	
	//tambien se puede pasar el string SET
	//ej: 'name = cba'	
	//$inaAttr: array con clausula WHERE
	//ej: 'id' => '1'
	//ej: 'id' => array('>' => '1', '<' => '10')
	//tambien se le puede pasar string 
	//ej: 'id > 1'
	//finalmente si no se le pasa nada se hace update a toda la tabla
	//return:
	//true en caso de exito y false en caso contrario.
	public function Update($insTable, $inaCampos, $inaAttr = "")
	{
		$this->m_db = new BSConnection();

		$aCampos = $aValues = array();
		if (is_array($inaCampos))
		{
			foreach ($inaCampos as $key => $value)
			{
				$aCampos[] = $key;
				$aValues[] = !empty($value) ? $value : null;
			}
			$this->m_sQuery = "UPDATE " . $insTable . " SET " . implode('= ?, ', $aCampos) . " = ? ";
		}
		elseif (is_string($inaCampos))
		{
			$this->m_sQuery = "UPDATE " . $insTable . " SET " . $inaCampos . " ";
		}
		if (is_array($inaAttr))
		{
			$this->m_sQuery .= "WHERE ";			
			foreach ($inaAttr as $key => $value)
			{
				if (is_array($value))
				{
					foreach ($value as $k => $v)
					{
						if (!strcmp(end(array_keys($value)), $k))
						{
							$this->m_sQuery .= $key . " " . $k . " ? ";
							if (strcmp(end(array_keys($inaAttr)), $key))
							{
								$this->m_sQuery .= " AND ";
							}
						}
						else
						{
							$this->m_sQuery .= $key . " " . $k . " ? AND ";
						}
						$aValues[] = !empty($v) ? $v : null;
					}

				}
				else
				{
					if (!strcmp(end(array_keys($inaAttr)), $key))
					{
						$this->m_sQuery .= $key . " = ?";						
					}
					else
					{
						$this->m_sQuery .= $key . " = ? AND ";						
					}
					$aValues[] = !empty($value) ? $value : null;
				}
			}
		}
		elseif (is_string($inaAttr))
		{
			if(!empty($inaAttr))
			{				
				$this->m_sQuery .= "WHERE " . $inaAttr;
			}

		}

		$this->m_sStatement = $this->m_db->prepare($this->m_sQuery);
		$this->m_bSuccess = $this->m_sStatement->execute($aValues);		
		$this->m_db = null;
		return $this->m_bSuccess;
	}
	//********************************************************************************

	public function Select($insquery)
	{
		$this->m_db = new BSConnection();
		$this->m_sStatement = $this->m_db->prepare($insquery);		
		$this->m_bSuccess = $this->m_sStatement->execute();
		$this->m_db = null;
		if ($this->m_bSuccess)
		{
			return $this->m_sStatement->fetchAll();
		}
		else
		{
			return $this->m_bSuccess;
		}
	}
}

?>