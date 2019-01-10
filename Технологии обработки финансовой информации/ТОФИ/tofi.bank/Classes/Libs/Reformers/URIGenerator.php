<?php
Loader::loadLib('Transliterator', 'Formaters');

/**
 * 
 * @name		URIGenerator
 * @author	EvGo
 * @version	1.0.0.0 created (EvGo)
 */
class URIGenerator 
{
	public $URLCheckUnic;
	public $URLCheckBase;
	public $URLCheckField;
	public $URLCheckArray;
	public $URLCheckIdField;
	public $DataBase;
	
	function URIGenerator($URLCheckUnic = false, $DataBase = null, $URLCheckBase = '', $URLCheckField = '', $URLCheckArray = array(), $URLCheckIdField = 'id') 
	{
		$this->URLCheckUnic = $URLCheckUnic;
		$this->URLCheckBase = $URLCheckBase;
		$this->URLCheckField = $URLCheckField;
		$this->URLCheckArray = $URLCheckArray;
		$this->URLCheckIdField = $URLCheckIdField;
		$this->DataBase = $DataBase;
	}

	/**
	 * @param string $title
	 * @param integer $id
	 * @return string
	 */
	function GetURIFromTitle($title, $id = 0, $convert_for_system = false) 
	{
		$title = Transliterator::Transliterate($title, 'original', $convert_for_system);
		
		if($this->URLCheckUnic)
		{
			$res = $this->DataBase->selectSql($this->URLCheckBase, $this->URLCheckArray + array($this->URLCheckField => $title, $this->URLCheckIdField => array('<>', $id)));
			if($res->rowCount())
			{
				$i=2;
				while ($res->rowCount()) {
					$title_t = $title.(($convert_for_system)?'_':'-').$i;
					$res = $this->DataBase->selectSql($this->URLCheckBase, $this->URLCheckArray + array($this->URLCheckField => $title_t, $this->URLCheckIdField => array('<>', $id)));
					$i++;
				}
				$title = $title_t;
			}
		}
		
		return $title;
	}	
}