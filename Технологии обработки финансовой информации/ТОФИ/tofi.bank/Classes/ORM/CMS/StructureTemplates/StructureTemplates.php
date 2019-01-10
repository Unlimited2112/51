<?php
class StructureTemplates extends Model
{
	function __construct ()
	{
		parent::__construct('StructureTemplates', 'wf_structure_templates',
			array(
				'structure_templates' => 'CREATE TABLE wf_structure_templates (
					id integer not null auto_increment,
					title varchar(255) not null,
					system varchar(255) not null,
					action bool,
					hidden bool,
					primary key (id)
				) ENGINE=' . DB_ENGINE . ' DEFAULT CHARSET=' . DB_CHARSET . ';'
			)
		);
	}
			
	public function onDelete($item_id) 
	{ 
		$res =	$this->Core->getModel('StructureAttachables')->getAll(array('id_template' => $item_id));
		foreach($res as $row) {
			$this->Core->getModel('StructureAttachables')->deleteItem($row['id']);
		}
		
		$res =	$this->Core->getModel('StructureContent')->getAll(array('id_template' => $item_id));
		foreach($res as $row) {
			$this->Core->getModel('StructureContent')->deleteItem($row['id']);
		}
		
		$res =	$this->Core->getModel('Structure')->getAll(array('id_template' => $item_id));
		foreach($res as $row) {
			$this->Core->getModel('Structure')->updateItem($row['id'], array('id_template' => 0));
		}
		
		return true;
	}
	
	/**
	 * @param integer $id_template
	 * @return string
	 */
	function getSystemByID($id_template) 
	{
		$res = $this->getByID($id_template);
		if($res)
		{
			return $res['system'];
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * @param integer $id_template
	 * @return bool
	 */
	function isAction($id_template) 
	{
		$cnt = $this->getCount(array('action' => 1, 'id' => $id_template));
		if($cnt > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}