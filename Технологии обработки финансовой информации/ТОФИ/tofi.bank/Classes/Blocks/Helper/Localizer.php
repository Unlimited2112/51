<?php
class Localizer
{	
	/**
	 * @var DataBase
	 */
	public $DataBase;
	
	/**
	 * @var array
	 */
	public $strings;
	
	/**
	 * @var array
	 */
	public $langs;
	
	public function __construct() 
	{
		$this->Core = Core::getInstance();
		$this->DataBase = $this->Core->DataBase;
	}
	
	public function initialize($params=array())
	{
		$this->strings = array();
		$this->langs = array();
	}
	
	/**
	 * @param string $name
	 * @param string $description
	 * @param int $idLang
	 * @param string $value
	 * @return bool
	 */
	public function addString($name, $description, $idLang, $value) 
	{
		if(isset($this->strings[$name]) and $this->strings[$name] == $value) 	return true;
		$name = strtolower($name);
		$res = $this->Core->DataBase->selectSql('wf_loc_strings', array('id_lang'=>$idLang, 'name'=>$name));
		if(!$res->rowCount())
		{
			$this->Core->DataBase->insertSql('wf_loc_strings', array('id_lang'=>$idLang, 'auto_created'=>'0', 'name'=>$name, 'description'=>$description, 'value'=>$value));
			return true;
		}
		else return $this->updateString($name, $idLang, $value);
	}
	
	/**
	 * @param string $name
	 * @param int $idLang
	 * @param string $value
	 * @return bool
	 */
	protected function updateString($name, $idLang, $value) {
		$name = strtolower($name);
		$res = $this->Core->DataBase->selectSql('wf_loc_strings', array('id_lang'=>$idLang, 'name'=>$name, 'value'=>$value));
		if(!$res->rowCount())
		{
			$this->strings[$name] = $value;
			return $this->Core->DataBase->updateSql('wf_loc_strings', array('value'=>$value), array('id_lang'=>$idLang, 'name'=>$name));
		}
		return true;
	}
	
	/**
	 * @return int
	 */
	protected function getLanguageId()
	{
		return (isset($this->Core->currentLang))?($this->Core->currentLang):(1);		
	}
	
	/**
	 * @return bool
	 */
	protected function areStringsLoaded()
	{
		return sizeof($this->strings);
	}
	
	protected function loadStrings()
	{
		$idLang = $this->getLanguageId();
		
		if(!$this->getStringsCache())
		{
			$query = 'select name, value from wf_loc_strings where id_lang = '.$idLang;
			$res = $this->Core->DataBase->selectSql($query);
			foreach($res as $row) {
				$this->strings[$row['name']] = $row['value'];
			}
			$this->setStringsCache();
		}
	}
	
	protected function setStringsCache()
	{
		if(Config::$enableFileCache)
		{
			$idLang = $this->getLanguageId();
			SetCacheVar('strings', serialize($this->strings), 'LocalizerStringsLang'.$idLang);
			FileCache::setCache('LocalizerStringsLang'.$idLang, $this->strings);
		}
	}
	
	/**
	 * @return bool
	 */
	protected function getStringsCache()
	{
		if(Config::$enableFileCache)
		{
			$idLang = $this->getLanguageId();
			if(FileCache::isCached('LocalizerStringsLang'.$idLang))
			{
				$this->strings = unserialize(InCache('strings', serialize(array()), 'LocalizerStringsLang'.$idLang));
				if(!empty($this->strings))
				{
					return true;
				}
				else 
				{
					$this->strings = FileCache::getCache('LocalizerStringsLang'.$idLang, array());
					if(!empty($this->strings))
					{
						return true;
					}
				}
			}
		}
		
		return false;
	}
	
	/**
	 * Is string in loaded strings
	 *
	 * @param string $name
	 * @return bool
	 */
	protected function inLoadedStrings($name)
	{
		return isset($this->strings[$name]);
	}
	
	/**
	 * Get string from loaded strings
	 *
	 * @param string $name
	 * @return string
	 */
	protected function getFromLoadedStrings($name)
	{
		if($this->inLoadedStrings($name))
		{
			return $this->strings[$name];
		}
		else 
		{
			return false;
		}
	}
	
	/**
	 * @param string $name
	 * @param int $idLang
	 * @param string $default
	 * @return array
	 */
	protected function getAutocreatedParams($name, $idLang, $default)
	{
		$value = is_null($default) ? $name : $default;
		
		$params = array(
			'id_lang'=>$idLang, 
			'auto_created'=>'1', 
			'name'=>$name, 
			'description'=>$value,
			'value'=>$value
		);
		
		return $params;
	}
	
	/**
	 * Get localization string
	 *
	 * @param string $name
	 * @return string
	 */
	public function getString($name) 
	{
		if ($name == '')
		{				
			throw new Exception('Empty localizer name');
		}
		
		$value = $this->getStringValue($name);
		
		if (func_num_args() > 1) 
		{
			$args = func_get_args();
			$args[0] = $value;
			$value = call_user_func_array('sprintf', $args);
		}
		
		return $value;
	}
	
	/**
	 * Get string value of set string by default value and then get it if such string was not defined
	 *
	 * @param string $name
	 * @param string $default
	 * @return string
	 */
	public function getStringValue($name, $default=null)
	{
		$name = strtolower($name);
		
		$idLang = $this->getLanguageId();
		
		if (!$this->areStringsLoaded())	
		{
			$this->loadStrings();
		}
		
		$value = '';
		
		if ($this->inLoadedStrings($name))
		{
			$value = $this->getFromLoadedStrings($name);
		}
		else 
		{
			$r = $this->Core->DataBase->selectSql('wf_loc_strings', array('id_lang'=>$idLang, 'name'=>$name));
			if ($r->rowCount()) 
			{
				$row = $r->fetch();
				$value = $row['value'];
			}
			else 
			{
				$newStringParams = $this->getAutocreatedParams($name, $idLang, $default);
				
				$this->Core->DataBase->insertSql('wf_loc_strings', $newStringParams);
				
				$this->loadStrings();
				
				return is_null($default) ? $name : $default;
			}
		}
		
		return $value;	
	}
	
	/**
	 * Get array of localization strings by array of string names
	 *
	 * @param array $strings
	 * @return array
	 */
	public function translateArray($strings=array())
	{
		$translates = array();
		foreach ($strings as $key => $string)
		{
			$translates[$key] = $this->getString($string);
		}
		return $translates;
	}	
	
	/**
	 * @return array
	 */
	public function getLangs()
	{
		if(empty($this->langs))	
		{
			$res = $this->Core->DataBase->selectSql('wf_loc_lang');
			$this->langs = array();
			foreach ($res as $row) {
				$this->langs[$row['id_lang']] = $row['name'];
			}
		}
		return $this->langs;
	}
}