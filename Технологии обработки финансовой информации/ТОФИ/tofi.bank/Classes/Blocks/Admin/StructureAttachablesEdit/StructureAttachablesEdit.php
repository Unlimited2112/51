<?php
Loader::loadBlock('ItemSubEdit', 'AdminBase');

class StructureAttachablesEdit extends ItemSubEdit 
{
	function __construct()
	{
		parent::__construct();
		$this->model_name = "StructureAttachables";
		$this->url = "attachables";
		$this->id_field = "id_template";
	}
};