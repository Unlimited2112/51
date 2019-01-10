<?php
class AdminStructure extends Model
{
	function __construct ()
	{
		parent::__construct('AdminStructure', 'wf_admin_structure',
			array(
				'admin_structure' => 'CREATE TABLE wf_admin_structure (
					id integer not null auto_increment,
					title varchar(255) not null,
					template varchar(255) not null, 			
					parent varchar(255) default \'\', 			
					perms integer,
					hidden bool,
					uri varchar(255) not null,
					primary key (id)
				) ENGINE=' . DB_ENGINE . ' DEFAULT CHARSET=' . DB_CHARSET . ';'
			)
		);
	}
			
	/**
	 * @return array
	 */
	function getActions()
	{
		$return = array();
		
		$res = $this->getAll(array('uri' => array('<>', '/')));
		foreach ($res as $row) {
			$return[] = $row['uri'];
		}
		array_multisort($return, SORT_DESC, SORT_STRING);
		
		return $return;
	}
	
	/**
	 * @param string $current_url
	 * @return array(template, perms, parent)
	 */
	function getPageSettings( $current_url ) 
	{
		$res = $this->getOne(array('uri' => $current_url));
		if($res)
		{
			return array( $res['template'], $res['perms'], $res['parent']);
		}
		return array(false,0,'');
	}
}