<?php
Loader::loadORM('Attachable', 'Interfaces');

class HomepageAnnonces extends Model implements Attachable
{
	function __construct ()
	{
		parent::__construct('HomepageAnnonces', 'wf_homepage_annonces',
			array(
				'homepage_annonces' => 'CREATE TABLE wf_homepage_annonces (
					id integer not null auto_increment,
					id_attachable integer,
					id_structure integer,
					title varchar(255) not null,
					description text null,
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
				'validator' => array('minLength' => 1, 'maxLength' => 250, 'isRequired' => true)
			),
			'description' => array(
				'validator' => array('minLength' => 1, 'maxLength' => 50000, 'isRequired' => false)
			),
			'image' => array(
				'validator' => array('option' => 'Images', 'isRequired' => true),
				'type' => 'file'
			)
		);
	}
}