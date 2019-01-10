<?php
Loader::loadORM('Attachable', 'Interfaces');

class StructureImages extends Model implements Attachable
{
	function __construct ()
	{
		parent::__construct('StructureImages', 'wf_structure_images',
			array(
				'structure_images' => 'CREATE TABLE wf_structure_images (
					id integer not null auto_increment,
					id_attachable integer,
					id_structure integer,
					title varchar(255) not null,
					image varchar(255) not null,
					sort_id integer,
					primary key (id)
				) ENGINE=' . DB_ENGINE . ' DEFAULT CHARSET=' . DB_CHARSET . ';'
			)
		);
		
		$this->sequence = true;
	}	
	
	public function getFields()
	{
		return array(
			'title' => array(
				'validator' => array('minLength' => 3, 'maxLength' => 250, 'isRequired' => true)
			),
			'image' => array(
				'validator' => array('option' => 'Images', 'isRequired' => true),
				'type' => 'file'
			)
		);
	}
}