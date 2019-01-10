<?php
Loader::loadBlock('ItemSubValues', 'AdminBase');

class StructureContentValuesEdit extends ItemSubValues 
{
	function __construct()
	{
		parent::__construct();
		
		$this->model_name_values = 'StructureContentValues';
		$this->model_name_fields = 'StructureContent';
		$this->model_name_elements = 'Structure';
		
		$this->id_category_title = 'id_template';
		$this->id_element_title = 'id_structure';
		$this->id_field_title = 'id_content';
		
		$this->url = 'content-values';
		
		$this->fields_prefix = 'scv_';
	}
};