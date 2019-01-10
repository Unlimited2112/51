<?php
Loader::loadBlock('ItemSubEdit', 'AdminBase');

class StructureContentEdit extends ItemSubEdit 
{
	function __construct()
	{
		parent::__construct();
		$this->model_name = "StructureContent";
		$this->url = "content";
		$this->id_field = "id_template";
	}
};