<?php
Loader::loadORM('Attachable', 'Interfaces');

class StructureFiles extends Model implements Attachable
{
	function __construct ()
	{
		parent::__construct('StructureFiles', 'wf_structure_files',
			array(
				'structure_files' => 'CREATE TABLE wf_structure_files (
					id integer not null auto_increment,
					id_attachable integer,
					id_structure integer,
					title varchar(255) not null,
					file varchar(255) not null,
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
			'file' => array(
				'validator' => array('option' => 'Files', 'isRequired' => true),
				'type' => 'file'
			)
		);
	}

	public function getIcon($file)
	{
		$path_parts = pathinfo($file);
		if(file_exists(Config::$filePath.'images/file-'.$path_parts['extension'].'.png'))
		{
			return '/images/file-'.$path_parts['extension'].'.png';
		}
		else
		{
			return '/images/file-other.png';
		}
	}

	public function getSize($file)
	{
		return sizeconvert(filesize(Config::$filePath.substr($file, 1))).' ÐšÐ±';
	}
}