<?php
class StructureContentValues extends Model
{
	function __construct ()
	{
		parent::__construct('StructureContentValues', 'wf_structure_content_values',
			array(
				'structure_content_values' => 'CREATE TABLE wf_structure_content_values (
					id integer not null auto_increment,
					id_content integer,
					id_structure integer,
					value text not null,
					primary key (id),
  					key id_content (id_content),
  					key id_structure (id_structure)
				) ENGINE=' . DB_ENGINE . ' DEFAULT CHARSET=' . DB_CHARSET . ';'
			)
		);
	}
}