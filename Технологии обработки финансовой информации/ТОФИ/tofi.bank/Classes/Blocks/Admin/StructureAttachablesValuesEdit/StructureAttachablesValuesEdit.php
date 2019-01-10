<?php
Loader::loadBlock('ItemSubEdit', 'AdminBase');

class StructureAttachablesValuesEdit extends ItemSubEdit 
{
	protected $id_attachable = 0;
	
	function __construct($params = array())
	{
		parent::__construct();
		$this->model_name = $params['system'];
		$this->url = $params['uri'];
		$this->id_attachable = $params['id_attachable'];
		$this->id_field = "id_structure";
	}	
	
	protected function initUnicValues()
	{
		$this->unic = array($this->id_field => $this->parent_item, 'id_attachable' => $this->id_attachable);
	}
};