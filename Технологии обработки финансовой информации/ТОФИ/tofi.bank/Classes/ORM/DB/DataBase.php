<?php

class DataBase extends PDO
{
	public function setLimit($sql, $limit)
	{
		if (!is_null($limit))
		{
			if (!is_array($limit))
			{
				$sql.= $limit> 0 ? ' LIMIT '.intval($limit):'';
			}
			elseif (isset($limit[1]) && isset($limit[0]) && ($limit[1] > 0) && ($limit[0] >= 0))
			{
				$sql.=' LIMIT ' . intval($limit[0]) . ', ' . intval($limit[1]);
			}
		}
		return $sql;
	}

	public function likeEscape($s)
	{
		$s = str_replace(array('%', '_'), array('\\%', '\\_'), $s);
		return trim($this->quote($s), "'");
	}
	
	/**
	 * @param string $tableName
	 * @param array $whereArray
	 * @param array $orderArray
	 * @param array $fields
	 * @param integer|array $limit
	 * @return string
	 */
	public function getSql($tableName, $whereArray = array(), $orderArray = array(), $fields = array(), $limit = null)
	{
		if (strcasecmp(substr($tableName, 0, 6), 'select') == 0)
		{
			$sql = $tableName;
		}
		else
		{
			$sql = 'select '.((empty($fields))?('*'):(implode(', ',$fields))).' from ' . $tableName;
		}
		
		$whereStatment = '';
		if (!empty($whereArray))
		{
			foreach ($whereArray as $k => $v)
			{
				if (is_array($v))
				{
					$whereStatment .= (($whereStatment=='')?(''):(' and ')) . $k . $v[0] . $this->quote($v[1]);
				}
				elseif ($k == 'custom')
				{
					if(!empty($v))
					{
						$whereStatment .= (($whereStatment=='')?(''):(' and ')) . '('. $v.')';
					}
				}
				else
				{
					$whereStatment .= (($whereStatment=='')?(''):(' and ')) . $k . '='. $this->quote($v);
				}
			}
		}
		if ($whereStatment)
		{
			$sql .= ' WHERE ' . $whereStatment;
		}
		
		$orderStatment = '';
		if (!empty($orderArray))
		{
			foreach ($orderArray as $k => $v)
			{
				$orderStatment .= ( ($orderStatment=='')?(''):(', ') ) . $k . ' ' . $v;
			}
		}
		if ($orderStatment != '')
		{
			$sql .= ' ORDER BY ' . $orderStatment;
		}
		
		if(!is_null($limit))
		{
			$sql = $this->setLimit($sql, $limit);
		}

		return $sql;
	}

	/**
	 * @param string $tableName
	 * @param array $whereArray
	 * @param array $orderArray
	 * @param array $fields
	 * @param integer|array $limit
	 * @return PDOStatement
	 */
	public function selectSql($tableName, $whereArray = array(), $orderArray = array(), $fields = array(), $limit = null)
	{
		$res = $this->selectCustomSql($this->getSql($tableName, $whereArray, $orderArray, $fields), $limit);
		return $res;
	}

	/**
	 * @param string $sql
	 * @param integer|array $limit
	 * @return PDOStatement
	 */
	public function selectCustomSql($sql, $limit = null)
	{
		$sql = $this->setLimit($sql, $limit);
//        echo $sql . '<br />';
		$tableRow = $this->query($sql, PDO::FETCH_ASSOC);
		return $tableRow;
	}

	/**
	 * @param string $tableName
	 * @param array $UpdateFields
	 * @param array $Keys
	 * @return bool
	 */
	public function updateSql($tableName, $UpdateFields, $Keys)
	{
		$sql = 'update ' . $tableName . ' set ';
		
		$separator = '';
		foreach ($UpdateFields as $k => $v)
		{
			if ( is_null( $v ) )
			{
				$sql .= $separator . '`' . $k . '`' . '=null';
			}
			else
			{
				$sql .= $separator . '`' . $k . '`' . '='.$this->quote($v);
			}
			$separator = ', ';
		}

		$sql .= ' where ';
		
		$separator = '';
		foreach ($Keys as $k => $v) 
		{
			list($composer, $v) =(is_array($v))? $v: array('=', $v);
			$sql .= $separator . $k . $composer . $this->quote($v);
			$separator = ' and ';
		}
		
		return $this->query($sql);
	}

	/**
	 * @param string $tableName
	 * @param array $values
	 * @return integer
	 */
	public function insertSql($tableName, $values)
	{
		$sql = 'insert into ' . $tableName . ' (';
		
		$separator = '';
		foreach ($values as $k => $v) 
		{
			$sql .= $separator . $k;
			$separator = ', ';
		}
		
		$sql .= ') values(';
		
		$separator = '';
		foreach ($values as $k => $v)
		{
			if (is_null($v))
			{
				$sql .= $separator . 'null';
			}
			else
			{
				if (is_bool($v))
				{
					$v = (($v)?(1):(0));
				}
				$sql .= $separator . $this->quote($v);
			}
			$separator = ', ';
		}
		
		$sql .= ')';
		
		if (!$this->query($sql))
		{
			return false;
		}
		
		return $this->lastInsertId();
	}

	/**
	 * @param string $tableName
	 * @param string $whereArray
	 * @return bool
	 */
	public function deleteSql($tableName, $whereArray = 0)
	{
		$whereStatment = '';
		if (is_array($whereArray))
		{
			foreach ($whereArray as $k => $v)
			{
				$whereStatment .= ( ($whereStatment=='')?(''):(' and ') ) . $k . '=' . $this->quote($v);
			}
		}

		$sql = 'delete from ' . $tableName;
		
		if ($whereStatment != '')
		{
			$sql .= ' where ' . $whereStatment;
		}
		
		return $this->query($sql);
	}
}