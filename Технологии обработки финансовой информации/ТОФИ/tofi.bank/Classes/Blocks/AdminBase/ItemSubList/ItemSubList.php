<?php
abstract class ItemSubList extends AdminBlock 
{
	/**
	 * @var Model
	 */	
	public $Item;
	public $model_name;
	public $list_table;
	public $url;
	public $parent_item;
	public $params_string;
	public $params_level;
	public $id_parent = 'id_parent';
	public $id_item = 'id_item';
	
	function __construct()
	{
		parent::__construct();
		$this->controlerPath = BLOCKS_ADMIN_BASE;
		$this->TplFileName = "ItemSubList/ItemSubList.tpl";
	}
	
	function initialize($params=array()) 
	{
		$this->initControlValues();
		$this->initBaseValues();
		$this->initForm();
		$this->getListData();
	}

	function initControlValues()
	{
		$this->parent_item = UUrl::getParram(1);
		$this->params_string = 'edit/'.$this->parent_item.'/'.$this->url.'/';
		$this->params_level = 3;
		$this->Item  = $this->Core->getModel($this->model_name);
	}
	
	function initBaseValues()
	{
		$this->tplVars['redirect_page_url'] = $this->tplVars['HTTP'].'admin/'.$this->Core->currentLangStr.preg_replace(array('/\/\//','/^\//'),array('/',''),$this->page_url);
		if(($this->params_string != '') and ($this->params_level != 0)) 
		{
			$this->tplVars['redirect_page_url'] .= $this->params_string;
		}
		
		$this->tplVars['subedit_info'] = unserialize(InCache('subedit_info',serialize(array())));
		SetCacheVar ('subedit_info', serialize(array()) );
	}
	
	function initForm()
	{
		if (Form::isSubmited('subedit_form'))
		{
			$res = $this->Item->getAll();
			foreach ($res as $row) {
				if(InPost('list_'.$row['id'], 0) != 0) {
					$this->addCheck($row['id']);
				}
				else {
					$this->removeCheck($row['id']);
				}
			}
			
			$info['subedit_info'] = $this->Localizer->getString('data_updated');
			SetCacheVar('subedit_info',serialize($info));
			
			Core::redirect ( $this->tplVars['redirect_page_url'] );
		}
	}
	
	function getFields()
	{
		return array();
	}

	protected function getWhere()
	{
		return array();
	}
	
	function getListData()
	{
		$this->tplVars['lists'] = 0;
		$this->tplVars['list_id'] = array();
		$this->tplVars['list_title'] = array();
		$this->tplVars['list_checked'] = array();
		foreach ($this->getFields() as $field) 
		{
			$this->tplVars['list_'.$field] = array();
		}
		
		$res = $this->Item->getAll($this->getWhere(), array('title' => 'asc'));
		foreach ($res as $row) {
			$this->tplVars['list_id'][] = $row['id'];
			$this->tplVars['list_title'][] = $row['title'];
			$this->tplVars['list_checked'][] =(int)$this->isChecked($row['id']);
			foreach ($this->getFields() as $field) {
				$this->tplVars['list_'.$field][] = $row[$field];
			}
			
			$this->setListVars($row['id']);
			
			$this->tplVars['lists']++;
		}
	}
	
	function setListVars($id_item)
	{
		$this->tplVars['list_'.$id_item] = (int)$this->isChecked($id_item);
	}
	
	function addCheck($id_item)
	{
		$res = $this->DataBase->selectSql($this->list_table, array($this->id_parent => $this->parent_item, $this->id_item => $id_item));
		if(!$res->rowCount())
		{
			$this->DataBase->insertSql($this->list_table, array($this->id_parent => $this->parent_item, $this->id_item => $id_item));
		}
	}
	
	function removeCheck($id_item)
	{
		$this->DataBase->deleteSql($this->list_table, array($this->id_parent => $this->parent_item, $this->id_item => $id_item));
		
		return true;
	}
	
	function isChecked($id_item)
	{
		$res = $this->DataBase->selectSql($this->list_table, array($this->id_parent => $this->parent_item, $this->id_item => $id_item));
		return (bool) $res->rowCount();
	}
};