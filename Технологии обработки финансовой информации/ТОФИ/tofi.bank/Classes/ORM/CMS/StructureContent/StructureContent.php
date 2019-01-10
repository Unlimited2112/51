<?php
class StructureContent extends Model
{
	function __construct ()
	{
		parent::__construct('StructureContent', 'wf_structure_content',
			array(
				'structure_content' => 'CREATE TABLE wf_structure_content (
					id integer not null auto_increment,
					id_template integer,
					title varchar(255) not null,
					system varchar(255) not null,
					type varchar(255) not null,
					validator varchar(255) not null,
					required integer,
					sort_id integer,
					primary key (id),
  					key id_template (id_template)
				) ENGINE=' . DB_ENGINE . ' DEFAULT CHARSET=' . DB_CHARSET . ';'
			)
		);
		
		$this->sequence = true;
	}
	
	public function onDelete($item_id) 
	{ 
		$res =	$this->Core->getModel('StructureContentValues')->getAll(array('id_content' => $item_id));
		foreach($res as $row) {
			$this->Core->getModel('StructureContentValues')->deleteItem($row['id']);
		}
		
		return true;
	}

	public function getFields()
	{
		return array(
			'title' => array(
				'validator' => array('minLength' => 3, 'maxLength' => 250, 'isRequired' => true)
			),
			'system' => array(
				'validator' => array('minLength' => 3, 'maxLength' => 250, 'isRequired' => true)
			),
			'type' => array(
				'validator' => array('minLength' => 3, 'maxLength' => 250, 'isRequired' => true)
			),
			'validator' => array(
				'validator' => array('minLength' => 3, 'maxLength' => 250, 'isRequired' => true)
			),
			'required' => array(
				'validator' => array('default' => 0),
				'type' => 'checkbox'
			)
		);
	}
}