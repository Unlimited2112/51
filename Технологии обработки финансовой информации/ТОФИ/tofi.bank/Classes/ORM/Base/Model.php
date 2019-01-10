<?php
/**
 * 
 * @name		Model
 * @author	EG
 * @version	1.0.0.0 created (EvGo)
 */
class Model
{
	/**
	 * Page Core Object
	 *
	 * @var Core
	 */
	protected $Core;

	/**
	 * Localizer Object
	 *
	 * @var Localizer
	 */
	protected $Localizer;
	
	/**
	 * DataBase Object
	 *
	 * @var DataBase
	 */
	protected $DataBase;
	
	/**
	 * @var ModelOptions
	 */		
	public $Options;

	/**
	 * Global Template Vars
	 *
	 * @var array
	 */
	protected $tplVars;

	/**
	 * Last Error
	 *
	 * @var string
	 */
	protected $lastError = '';
	
	//Management Params

	/**
	 * Is MultiLanguage Enabled
	 *
	 * @var bool
	 */
	public $multilanguage = false;
		
	/**
	 * Is URI Enabled
	 *
	 * @var bool
	 */
	public $uri = false;
	
	/**
	 * @var bool
	 */
	public $sequence = false;

	/**
	 * @var bool
	 */
	public $savetime = false;
	
	/**
	 * URI Field
	 *
	 * @var string
	 */
	public $uriField = 'uri';
	
	/**
	 * SORT Field
	 *
	 * @var string
	 */
	public $sortField = 'sort_id';
	
	/**
	 * URI From Field
	 *
	 * @var string
	 */
	public $uriFromField = 'title';
	
	/**
	 * @var bool
	 */
	public $uriConvertForSystem = false;
	
	/**
	 * URI Check Array
	 *
	 * @var array
	 */
	public $uriCheckArray = array();
	
	/**
	 * @var URIGenerator
	 */
	public $URIGenerator;
	
	/**
	 * @var SequenceController
	 */
	public $SequenceController;
	
	/**
	 * DB SQL Statment
	 *
	 * @var string
	 */	
	protected $dbSql = '';

	/**
	 * DB Name
	 *
	 * @var string
	 */		
	public $dbName = 'management';

	/**
	 * ID Field
	 *
	 * @var string
	 */		
	public $dbId = 'id';

	/**
	 * Management Name
	 *
	 * @var string
	 */		
	public $modelName = 'Management';
	
	public $modelFolder = 'Models';
	
	/**
	 * 
	 * @param string $modelName
	 * @param string $dbName
	 * @param string $dbSql
	 * @return Model
	 */
	function __construct($modelName, $dbName, $dbSql=NULL) 
	{
		$this->modelName		= $modelName;
		$this->Core				= Core::getInstance();
		$this->tplVars		= &$this->Core->tplVars;
		$this->DataBase			= $this->Core->DataBase;
		$this->Localizer		= $this->Core->Localizer;
		
		$this->setDataBaseSql($dbName, $dbSql);
	}
	
	/**
	 * Return Management Options
	 *
	 * @return ModelOptions
	 */
	public function getOptions()
	{
		if(!is_object($this->Options))
		{
			if (file_exists(ORM . $this->modelFolder . '/' . $this->modelName.'/'.$this->modelName.'Options.php'))
			{
				require_once(ORM . $this->modelFolder . '/' . $this->modelName.'/'.$this->modelName.'Options.php');
				$options = ($this->modelName.'Options');
				$this->Options = new $options($this->Core, $this->multilanguage);
			}
		}
		if($this->uri and !is_object($this->URIGenerator))
		{
			Loader::loadLib('URIGenerator', 'Reformers');
			$this->URIGenerator = new URIGenerator(true, $this->DataBase, $this->dbName, $this->uriField, $this->uriCheckArray);
		}
		if($this->sequence and !is_object($this->SequenceController))
		{
			Loader::loadLib('SequenceController', 'Reformers');
			$this->SequenceController = new SequenceController($this->DataBase, $this->dbName, $this->sortField);
		}
		
		return $this->Options;
	}
	
	/**
	 * Return Last Error
	 *
	 * @return string
	 */	
	public function getLastError()
	{
		return $this->lastError;
	}
	
	/**
	 * Return Item by ID
	 *
	 * @param int $item_id
	 * @param string $condition
	 * @return PDOStatement
	 */
	public function getByID($item_id,  $whereArray = array(), $fields = array())
	{
		if ($item_id < 1) 
		{
			throw new Exception('Invalid input data: Item ID must be greter than zero.');
		}
		else 
		{
			$whereArray += array($this->dbId => $item_id);
			
			return $this->getOne($whereArray, $fields);
		}
	}
	
	/**
	 * Return All Items
	 *
	 * @param array $whereArray
	 * @param array $orderArray
	 * @param array $fields
	 * @param integer/array $limit
	 * @return PDOStatement
	 */	
	public function getAll($whereArray = array(), $orderArray = array(), $fields = array(), $limit = null)
	{
		if ($this->multilanguage)
		{
			$whereArray += array('id_lang' => $this->Core->currentLang);
		}
		
		$res = $this->DataBase->selectSql($this->dbName, $whereArray, $orderArray, $fields, $limit);
		return $res->fetchAll();
	}
		
	/**
	 * Return All Items
	 *
	 * @param array $whereArray
	 * @param array $orderArray
	 * @param array $fields
	 * @param integer/array $limit
	 * @return array
	 */	
	public function getOne($whereArray = array(), $fields = array())
	{
		if ($this->multilanguage)
		{
			$whereArray += array('id_lang' => $this->Core->currentLang);
		}
		
		$res = $this->DataBase->selectSql($this->dbName, $whereArray, array(), $fields, 1);
		if(sizeof($res)) {
			return $res->fetch();
		}
		return false;
	}
	
	/**
	 * Return Count of Items
	 *
	 * @param string $condition
	 * @return int
	 */
	public function getCount($whereArray = array()) 
	{
		$fields = array('count(*) as cnt');
		
		if ($this->multilanguage)
		{
			$whereArray += array('id_lang' => $this->Core->currentLang);
		}
		
		$res = $this->getAll($whereArray, array(), $fields);
		return $res[0]['cnt'];
	}
	
	/**
	 * Check is Item Unique
	 *
	 * @param int $id
	 * @param array $check_array
	 * @param string $condition
	 * @return bool
	 */
	public function checkUnique($id=false, $check_array,  $whereArray = array())
	{
		if(is_array($check_array) and $check_array)
		{
			foreach($check_array as $k =>$v)
			{
				if(! strlen($v)) continue;
				
				if ($this->multilanguage)
				{
					$whereArray += array('id_lang' => $this->Core->currentLang);
				}
				if ($id)
				{
					$whereArray += array($this->dbId => array('<>', $id));
				}
				
				$whereArrayRequest = array($k => $v) + $whereArray;
				
				$count = $this->getCount($whereArrayRequest);
		
				if($count>0)
				{
					$this->lastError = $this->Core->Localizer->getString('unique_field_'. $k. '_error');
				    	return false;
				}
			}
		}
		return true;
	}

	/**
	 * Return array(array($field1 => $field2)) for Select in Example
	 *
	 * @param string $condition
	 * @param string $field1
	 * @param string $field2
	 * @return array
	 */
	public function getList($field1 = 'id', $field2 = 'title', $whereArray = array(), $orderArray = array()) 
	{		
		$result = array();
		
		$fields = array($field1, $field2);
		
		if ($this->multilanguage)
		{
			$whereArray += array('id_lang' => $this->Core->currentLang);
		}
		
		$res = $this->getAll($whereArray, $orderArray, $fields);
		foreach ($res as $row) {
			$result[$row[$field1]] = $row[$field2];
		}
		
		return $result;
	}
	
	/**
	 * @param string $uri
	 * @param string $condition
	 * @return PDOStatement
	 */
	public function getByURI($uri, $whereArray = array(), $fields = array())
	{
		if (empty($uri)) 
		{
			throw new Exception('Invalid input data: URI is empty.');
		}
		else 
		{
			$whereArray += array($this->uriField => $uri);
			
			return $this->getOne($whereArray, $fields);
		}
	}
	
	/**
	 * @param integer $uri
	 * @return string
	 */
	public function getURIByID($item_id)
	{
		if (empty($item_id)) 
		{
			throw new Exception('Invalid input data: ID is empty.');
		}
		else 
		{
			$res = $this->getByID($item_id);
			if($res)
			{
				return $res['uri'];
			}
			else 
			{
				return '';
			}
		}
	}
	
	/**
	 * Delete Item by ID
	 *
	 * @param int $item_id
	 * @return bool
	 */
	public function deleteItem($item_id) 
	{
		if($this->onDelete($item_id))
		{
			$this->DataBase->deleteSql($this->dbName, array($this->dbId => $item_id));
			$this->afterDelete($item_id);
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * Add Item from array
	 *
	 * @param array $arr
	 * @return int
	 */
	public function addItem($arr) 
	{
		if($this->onAdd($arr))
		{
			if($this->uri and !isset($arr[$this->uriField]))
			{
				if($this->URIGenerator == null)
				{
					Loader::loadLib('URIGenerator', 'Reformers');
					$this->URIGenerator = new URIGenerator(true, $this->DataBase, $this->dbName, $this->uriField, $this->uriCheckArray);
				}
				$arr[$this->uriField] = $this->URIGenerator->GetURIFromTitle($arr[$this->uriFromField], 0, $this->uriConvertForSystem);
			}
			if($this->multilanguage and !isset($arr['id_lang']))
			{
				$arr['id_lang'] = $this->Core->currentLang;
			}
			if($this->savetime)
			{
				$arr['cdate'] = date('Y-m-d H:i:s');
				$arr['udate'] = date('Y-m-d H:i:s');
			}
			
			$item_id = $this->DataBase->insertSql($this->dbName, $arr);
			$this->afterAdd($item_id, $arr);
			return $item_id;
		}
		else return false;
	}

	/**
	 * Udate Item from array by ID
	 *
	 * @param int $item_id
	 * @param array $arr
	 * @return int
	 */
	public function updateItem($item_id, $arr) 
	{
		if($this->onUpdate($item_id, $arr))
		{
			if($this->savetime)
			{
				$arr['udate'] = date('Y-m-d H:i:s');
			}
			$res = $this->DataBase->updateSql($this->dbName, $arr, array($this->dbId => $item_id));
			$this->afterUpdate($item_id, $arr);
			return $res;
		}
		else return false;
	}
	
	/**
	 * Custom On Delete Validation
	 *
	 * @param int $item_id
	 * @return bool
	 */
	public function onDelete($item_id) { return true;}
	
	/**
	 * Custom On Add Validation
	 *
	 * @param array $arr
	 * @return bool
	 */	
	public function onAdd(&$arr) { return true;}
	
	/**
	 * Custom On Update Validation
	 *
	 * @param int $item_id
	 * @param array $arr
	 * @return bool
	 */	
	public function onUpdate($item_id, &$arr) { return true;}
	
	/**
	 * Custom After Add Validation
	 *
	 * @param array $arr
	 * @return bool
	 */	
	public function afterAdd($item_id, &$arr) { return true;}
	
	/**
	 * Custom After Update Validation
	 *
	 * @param int $item_id
	 * @param array $arr
	 * @return bool
	 */	
	public function afterUpdate($item_id, &$arr) { return true;}

	/**
	 * Custom After Update Validation
	 *
	 * @param int $item_id
	 * @return bool
	 */	
	public function afterDelete($item_id) { return true;}

	/**
	 * Set DB values
	 *
	 * @param string $dbName
	 * @param string $dbSql
	 */
	protected function setDataBaseSql($dbName, $dbSql =NULL) 
	{
		if(is_array($dbName))
		{
		            $this->dbName = array_shift(array_keys($dbName));
		            $this->dbSql = $dbName;
		}
		else
		{
		            $this->dbName = $dbName;
		            $this->dbSql = $dbSql;
		}
	}
	
	/**
	* @return bool
	*/
	public function install()
	{
	            $this->DataBase->exec('SET FOREIGN_KEY_CHECKS=0');
	            if(is_array($this->dbSql))
	            {
	                       foreach($this->dbSql as $table =>$create_sql)
	                       {
	                                    $this->DataBase->exec('DROP TABLE IF EXISTS '. $table);
	                                    $this->DataBase->exec($create_sql);
	                       }
	            }
	            else
	            {
	                        $this->DataBase->exec('DROP TABLE IF EXISTS '.$this->dbName);
	                        $this->DataBase->exec($this->dbSql);
	            }
	
	            $this->afterInstall();
	}
	
	public function afterInstall(){}
};