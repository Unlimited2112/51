<?php
class StructureAttachables extends Model
{
	function __construct ()
	{
		parent::__construct('StructureAttachables', 'wf_structure_attachables',
			array(
				'structure_attachables' => 'CREATE TABLE wf_structure_attachables (
					id integer not null auto_increment,
					id_template integer,
					title varchar(255) not null,
					system varchar(255) not null,
					uri varchar(255) not null,
					sort_id integer,
					primary key (id),
  					key id_template (id_template)
				) ENGINE=' . DB_ENGINE . ' DEFAULT CHARSET=' . DB_CHARSET . ';'
			)
		);
		
		$this->sequence = true;
		$this->uri = true;
	}
	
	public function onDelete($item_id) 
	{ 
		$attachable = $this->getOne($item_id);
		$res = $this->Core->getModel($attachable['system'])->getAll(array('id_attachable' => $item_id));
		foreach($res as $row) {
			$this->Core->getModel($attachable['system'])->deleteItem($row['id']);
		}
		
		return true;
	}

	public function getFields()
	{
		return array(
			'title' => array(
				'validator' => array('minLength' => 3, 'maxLength' => 250),
				'isRequired' => true
			),
			'system' => array(
				'validator' => array('minLength' => 3, 'maxLength' => 250),
				'isRequired' => true
			)
		);
	}
}