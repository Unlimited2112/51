<?php
class StructureTemplatesPage extends ModelTemplate 
{
	function __construct ()
	{
		parent::__construct('StructureTemplates');
	}
	
	protected function initTabs()
	{
		$this->MainTabName = $this->Localizer->getString('tab_title_structure_templates');
		$this->addTab($this->Localizer->getString('tab_title_structure_templates_data_fields'), "StructureContentEdit", "content");
		$this->addTab($this->Localizer->getString('tab_title_structure_templates_data_lists'), "StructureAttachablesEdit", "attachables");
	}
}