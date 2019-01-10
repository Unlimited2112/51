<?php
class StructurePage extends ModelTemplate 
{
	function __construct ()
	{
		parent::__construct('Structure');
	}
	
	protected function initTabs()
	{
		$this->MainTabName = $this->Localizer->getString('tab_title_structure');
		$this->addTab($this->Localizer->getString('tab_title_structure_data'), "StructureContentValuesEdit", "content-values");
		if ($this->tplVars['operation'] == 'edit')
		{
			$structure = $this->Model->getByID($this->tplVars['item_id']);
			$attachables = $this->Core->getModel('StructureAttachables')->getAll(array('id_template' => $structure['id_template']), array('sort_id' => 'ASC'));
			foreach($attachables as $row) {
				if(is_file(BLOCKS_ADMIN.$row['system'].'Edit'.'/'.$row['system'].'Edit'.'.php'))
				{
					$this->addTab($row['title'], $row['system'].'Edit', $row['uri'], array('id_attachable' => $row['id'], 'uri' => $row['uri'], 'system' => $row['system']));
				}
				else
				{
					$this->addTab($row['title'], "StructureAttachablesValuesEdit", $row['uri'], array('id_attachable' => $row['id'], 'uri' => $row['uri'], 'system' => $row['system']));
				}
			}
		}
	}
}