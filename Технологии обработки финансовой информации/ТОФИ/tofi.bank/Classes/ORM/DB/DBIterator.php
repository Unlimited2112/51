<?php
class DBIteratorItem 
{
	public $Fields;
	
	/**
	 * @param array $arr
	 */
	function __construct($fields) 
	{
		$this->Fields = $fields;
	}
	
	/**
	 * @param string $fieldName
	 * @return string
	 */
	function getField($fieldName) 
	{
		if ( (Config::$debugLevel) && (!$this->haveField($fieldName)) )
		{
			throw new Exception('Поле с именем '.$fieldName.' отсутствует');
		}
		return $this->Fields[$fieldName];
	}
	
	/**
	 * @param string $fieldName
	 * @return bool
	 */
	function haveField($fieldName) 
	{
		return key_exists($fieldName, $this->Fields);
	}
	
	/**
	 * Returns associated templates array
	 *
	 * @param string $prefix
	 * @return array
	 */
	function getItemArray($prefix = 'rs_')
	{
		$return = array();
		
		$return[$prefix.'item_enabled'] = true;
		
		foreach ($this->Fields as $field => $value) 
		{
			$return[$prefix.$field] = $value;
		}
		
		return $return;
	}
}

class DBIterator
{
	/**
	 * @var array of DBIteratorItem
	 */
	public $Items;
	
	/**
	 * @var array
	 */
	public $Fields;
	
	/**
	 * @var integer
	 */
	public $currentItem;

	function __construct()
	{
		$this->init();
	}

	protected function init()
	{
		$this->currentItem = 0;
		$this->Items = array();
		$this->Fields = array();
	}

	function free()
	{
		$this->initialize();
	}

	/**
	 * @return bool
	 */
	function eof()
	{
		return ($this->currentItem >= sizeof($this->Items));
	}

	function first()
	{
		$this->currentItem = 0;
	}

	function next()
	{
		$this->currentItem++;
	}

	function prev()
	{
		if ($this->currentItem > 0)
		{
			$this->currentItem--;
		}
	}

	function last()
	{
		if (sizeof($this->Items))
		{
			$this->currentItem = sizeof($this->Items)-1;
		}
	}

	/**
	 * @param string $key
	 * @param string $value
	 * @return bool
	 */
	function find($key, $value)
	{
		$this->first();
		while (!$this->eof())
		{
			if ($this->getField($key) == $value)
			{
				return true;
			}
			$this->next();
		}
		return false;
	}
	
	/**
	 * @return mixed
	 */	
	function shift()
	{
		$this->currentItem =0;
		if(sizeof($this->Items) >0)
		{
			return array_shift($this->Items);
		}
		return false;
	}

	/**
	 * @return integer
	 */
	function getRecordsCount()
	{
		return (sizeof($this->Items));
	}
	
	/**
	 * @return DBIteratorItem
	 */
	function getItem() // get whole row
	{
		if (!isset($this->Items[$this->currentItem]))
		{
			throw new Exception('getItem: Попытка чтения после окончания коллекции');
		}
		return $this->Items[$this->currentItem];
	}

	/**
	 * @param string $name
	 * @return string
	 */
	function getField($name) // get value of the field in current row
	{
		if (!isset($this->Items[$this->currentItem]))
		{
			throw new Exception('getField: Попытка чтения после окончания коллекции');
		}
		return $this->Items[$this->currentItem]->getField($name);
	}
	
	/**
	 * @param array $assocArr
	 * @param integer $to
	 */
	function addItem($assocArr, $toEnd = true)
	{
		foreach ($this->Fields as $v) if (!isset($assocArr[$v])) $assocArr[$v] = '';
		if (!$toEnd)
			array_unshift($this->Items, new DBIteratorItem($assocArr));
		else
			$this->Items[] = new DBIteratorItem($assocArr);
	}

	/**
	 * @param string $fieldName
	 */
	function addField($fieldName)
	{
		if (!in_array($fieldName, $this->Fields))
		{
			$this->Fields[] = $fieldName;
		}
	}

	function deleteItem()
	{
		array_splice($this->Items, $this->currentItem, 1);
	}	
	
	/**
	 * Returns associated array
	 *
	 * @param string $field
	 * @return array
	 */
	function getItemsFieldArray($field)
	{
		$this->first();
		$return = array();
		
		while(!$this->eof())
		{
			$return[] = $this->getField($field);
			$this->next();
		}
		
		$this->first();
		
		return $return;
	}
	
	/**
	 * Returns associated array
	 *
	 * @param string $key
	 * @param string $value
	 * @return array
	 */
	function getAssociatedArray($key, $value)
	{
		$this->first();
		$return = array();
		
		while(!$this->eof())
		{
			$return[$this->getField($key)] = $this->getField($value);
			$this->next();
		}
		
		$this->first();
		
		return $return;
	}
	
	/**
	 * Returns associated templates array
	 *
	 * @param string $prefix
	 * @return array
	 */
	function getItemArray($prefix = 'rs_')
	{
		return $this->getItem()->getItemArray($prefix);
	}	
	
	/**
	 * Returns associated templates array
	 *
	 * @param array $fields
	 * @param string $prefix
	 * @return array
	 */
	function getItemsArray($prefix = 'rs_')
	{
		$this->first();
		
		$return = array();
		$return[$prefix.'items_count'] = 0;
		$return[$prefix.'counter']= array();
		
		foreach ($this->Fields as $field) 
		{
			$return[$prefix.$field] = array();
		}
		
		while(!$this->eof())
		{
			foreach ($this->Fields as $field) 
			{
				$return[$prefix.$field][] = $this->getField($field);
			}
			
			$return[$prefix.'counter'][] = $return[$prefix.'items_count']+1;
			$return[$prefix.'items_count']++;
			$this->next();
		}
		
		$this->first();
		
		return $return;
	}
	
	/**
	 * Merges DBIterators to one
	 *
	 * @param array $array Array of 
	 * @param bool $orderASC
	 * @return DBIterator
	 */
	public static function merge(&$array, $orderASC=true)
	{
		if (!sizeof($array))
		{
			throw new Exception('Должен быть передан массив эл-тов типа DBIterator');
		}
		
		$result = $orderASC ? array_shift($array) : array_pop($array);

		if (!sizeof($array))
		{
			while (sizeof($array)) 
			{
				$element = $orderASC ? array_shift($array) : array_pop($array);
				
				foreach ($element->Items as $item)
				{
					$result->Items[] = $item;
				}
			}
		}
		return $result;
	}
}