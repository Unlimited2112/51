<?php
class Settings extends Model
{
	function __construct()
	{
		parent::__construct(
		 'Settings',
		 'wf_settings',
		 'CREATE TABLE wf_settings (
			id						int AUTO_INCREMENT NOT NULL,
			title					varchar(250),
			system					varchar(250),
			validator				varchar(250),
			value					text,
			comment					varchar(250),
			hidden					int(1),
			primary key				(id),
			index 					(title)
		 ) ENGINE='.DB_ENGINE.' DEFAULT CHARSET='.DB_CHARSET.';');
	}		

	function getSetting( $system )
	{
		if ( $system == '' ) 
		{
			$this->lastError = $this->Core->Localizer->getString('invalid_input_data');
			return false;
		}

		$rs = $this->DataBase->selectCustomSql('
			SELECT * FROM '.$this->dbName.'
			WHERE (system = \''.$system.'\')
		');

		if (!$rs->rowCount())
		{
			$this->lastError = $this->Core->Localizer->getString('database_error');
			return false;
		}
		
		$row = $rs->fetch();
		$this->lastError = '';
		return $row['value'];
	}
	
	function setSetting( $system, $arr)
	{
		$res = $this->DataBase->updateSql($this->dbName, $arr, array('system' => $system));
		return $res;
	}
}