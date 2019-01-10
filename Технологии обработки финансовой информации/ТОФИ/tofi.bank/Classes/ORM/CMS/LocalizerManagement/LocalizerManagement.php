<?php
class LocalizerManagement extends Model
{
	function __construct(){
		parent::__construct(
			'LocalizerManagement',
			'wf_loc_strings',
			array(
				'loc_strings' => 'CREATE TABLE wf_loc_strings (
						id_string INTEGER NOT NULL auto_increment,
						id_lang INTEGER not null,
						auto_created INTEGER not null,
						name VARCHAR(100) NOT NULL,
						value VARCHAR(150) NOT NULL,
						description VARCHAR(255) NOT NULL,
						primary key(id_string)
					) ENGINE='.DB_ENGINE.' DEFAULT CHARSET='.DB_CHARSET.';',
				'loc_lang' => 'CREATE TABLE wf_loc_lang (
						id_lang INTEGER NOT NULL auto_increment primary key,
						name VARCHAR(60),
						uri VARCHAR(60),
						locale VARCHAR(60)
					) ENGINE='.DB_ENGINE.' DEFAULT CHARSET='.DB_CHARSET.';'
			));
		$this->dbId = 'id_string';
		$this->multilanguage =true;
	}
	
	/**
	 * Custom After Add Validation
	 *
	 * @param array $arr
	 * @return bool
	 */	
	public function afterAdd($item_id, &$arr) 
	{ 
		FileCache::deleteCache('LocalizerStringsLang'.$arr['id_lang']);
		return true;
	}
	
	/**
	 * Custom After Update Validation
	 *
	 * @param int $item_id
	 * @param array $arr
	 * @return bool
	 */	
	public function afterUpdate($item_id, &$arr) 
	{ 
		FileCache::deleteCache('LocalizerStringsLang'.$this->Core->currentLang);
		return true;
	}
	
	public function addLang($arr)
	{
		$this->DataBase->insertSql('wf_loc_lang', $arr);
	}
}