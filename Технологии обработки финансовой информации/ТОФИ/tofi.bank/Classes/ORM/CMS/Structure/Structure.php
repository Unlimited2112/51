<?php
Loader::loadORM('Searchable', 'Interfaces');

class Structure extends ModelCategory implements Searchable
{
	function __construct ()
	{
		parent::__construct('Structure', 'wf_structure',
			array(
				'structure' => 'CREATE TABLE wf_structure (
					id integer not null auto_increment,
					id_parent integer default 0,
					title varchar(255) not null,
					system varchar(255) not null,
					uri varchar(255) not null,
					id_template integer,
					perms integer default 0,
					show_in_menu bool,
					status bool,
					can_edit bool,
					can_add bool,
					id_lang integer default 1,
					sort_id integer default 0,
					cdate datetime,
					udate datetime,
					primary key (id)
				) ENGINE=' . DB_ENGINE . ' DEFAULT CHARSET=' . DB_CHARSET . ';'
			)
		);
		
		$this->maxLevel = 100;
		$this->rootAdd = true;
		$this->multilanguage = true;
		$this->savetime = true;
	}

	function onAdd(&$arr)
	{
		if (!isset($arr['can_edit']))
		{
			$arr['can_edit'] = 1;
		}
		if (!isset($arr['can_add']))
		{
			$arr['can_add'] = 1;
		}
		if (isset($arr['system']) and isset($arr['id_parent']))
		{
			return $this->checkUnique(false, array('system' => $arr['system']), array('id_parent' => $arr['id_parent']));
		}
		return true;
	}
	
	function onUpdate($item_id, &$arr)
	{
		if (isset($arr['system']) and isset($arr['id_parent']))
		{
			return $this->checkUnique($item_id, array('system' => $arr['system']), array('id_parent' => $arr['id_parent']));
		}
		return true;
	}
	
	public function onDelete($item_id) 
	{ 
		$structure = $this->getByID($item_id);
		$attachables = $this->Core->getModel('StructureAttachables')->getAll(array('id_template' => $structure['id_template']));
		
		foreach ($attachables as $attachable) {
			$res = $this->Core->getModel($attachable['system'])->getAll(array('id_structure' => $item_id));
			foreach($res as $row) {
				$this->Core->getModel($attachable['system'])->deleteItem($row['id']);
			}
		}
		
		$res =	$this->Core->getModel('StructureContentValues')->getAll(array('id_structure' => $item_id));
		foreach($res as $row) {
			$this->Core->getModel('StructureContentValues')->deleteItem($row['id']);
		}
		
		return true;
	}	
		
	public function afterAdd($item_id, &$arr)
	{ 
		$this->updatePageURI($item_id);
		
		return true;
	}
	
	public function afterUpdate($item_id, &$arr) 
	{
		if (!isset($arr['uri']))
		{
			$this->updatePageURI($item_id);	
		}
		return true;
	}

	protected function updatePageURI($item_id, $stop_parent_id = 0, $stop_uri_add = '/')
	{
		$page = $this->getByID($item_id);
		if($page)
		{
			$uri = $page['system'].'/';
			$parent_id = $page['id_parent'];
			
			$loop = true;	
			
			if ($parent_id == $stop_parent_id)
			{			
				$loop = false;		
			}
				
			while ($loop) 
			{
				$res = $this->getByID($parent_id);
				if($res)
				{
					$uri = $res['system'].'/'.$uri;
					$parent_id = $res['id_parent'];
				
					if ($parent_id == $stop_parent_id)
					{			
						$loop = false;		
					}
				}
				else 
				{
					$loop = false;
				}
			}
			
			$uri = $stop_uri_add.$uri;
			
			$this->updateItem($item_id, array('uri' => $uri));
			
			$res = $this->getAll(array($this->dbParentId => $item_id));
			foreach($res as $row) {
				$this->updatePageURI($row[$this->dbId], $item_id, $uri);
			}
		}
	}
	
	protected function isCanAdd($level, $item)
	{
		if (!$this->Core->getModel('StructureTemplates')->isAction($item['id_template']))
		{
			if($item['id_template'] == 6)
			{
				return ($level>3)?0:1;
			}
			else
			{
				return (int)(($level>$this->maxLevel)?0:(isset($item['can_add'])?$item['can_add']:1));
			}
		}
		else 
		{
			return false;
		}
	}
	
	/**
	 * @param string $current_url
	 * @return bool
	 */
	public function isPageExist($current_url) 
	{
		return is_array($this->getOne(array('uri' => $current_url)));
	}
	
	/**
	 * @param string $current_url
	 * @return array(id_template, perms, title, id)
	 */
	public function getPageSettings($current_url) 
	{
		$res = $this->getOne(array('uri' => $current_url));
		if($res)
		{
			return array( $res['id_template'], $res['perms'], $res['title'], $res['id']);
		}
		return array(0,0,'',0);
	}	
	
	/**
	 * @return array
	 */
	public function getActions()
	{
		$return = array();
		
		$res = $this->getAll(array('uri' => array('<>', '/')));
		foreach($res as $row) {
			if ($this->Core->getModel('StructureTemplates')->isAction($row['id_template']))
			{
				$return[] = $row['uri'];
			}
		}
		array_multisort($return, SORT_DESC, SORT_STRING);
		
		return $return;
	}
	
	/**
	 * @param integer $item_id
	 * @return array
	 */
	public function getContentValues($item_id)
	{
		$result = array();
		
		$content_values = $this->Core->getModel('StructureContentValues')->getAll(array('id_structure' => $item_id));
		foreach($content_values as $row) {
			$content = $this->Core->getModel('StructureContent')->getOne(array('id' => $row['id_content']));
			if($content)
			{
				$result['scv_'.$content['system']] = $row['value'];
			}
		}
		return $result;
	}
	
	/**
	 * @param string $system
	 * @param array $whereArray
	 * @param array $fields
	 * @return PDOStatement
	 */	
	public function getBySystem($system, $whereArray = array(), $fields = array())
	{
		if (empty($system)) 
		{
			throw new Exception('Invalid input data: System must be filled.');
		}
		else 
		{
			$whereArray += array('system' => $system);
			
			return $this->getOne($whereArray, $fields);
		}
	}

	public function getItemUrl($item)
	{
		$item = $this->getByID($item);
		if($item)
		{
			return $item['uri'];
		}
		
		return false;
	}
	
	/**
	 * @param string $template
	 * @return array
	 */
	public function getListByTemplate($template)
	{
		$res = $this->Core->getModel('StructureTemplates')->getOne(array('system' => $template), array('id'));
		$id_template = $res['id'];
		return $this->getList('id', 'title', array('id_template' => $id_template));
	}
	
	/**
	 * @param string $template
	 * @return array
	 */
	public function getListOfParentsByTemplate($template)
	{
		$result = array();
		
		$res = $this->Core->getModel('StructureTemplates')->getOne(array('system' => $template), array('id'));
		$id_template = $res['id'];
		$pages = $this->getAll(array('id_template' => $id_template));
		foreach($pages as $row) {
			$title_res = $this->getByID($row['id_parent']);
			$result[$row['id']] = $title_res['title'];
		}
		
		return $result;
	}
	
	public function getSearchFields()
	{
		return array('wf_structure.title', 'wf_structure_content_values.value');
	}
	
	public function getSearchSQLQuery($whereSQLQuery)
	{
		if (empty($whereSQLQuery))
		{
			$whereSQLQuery = '1=1';
		}
		
		return '
			SELECT 
			  wf_structure.id as item_id,
			  \'Structure\' as type,
			  wf_structure.uri,
			  wf_structure.title,
			  wf_structure_content_values.value as text,
			  wf_structure.udate
			FROM
			  wf_structure
			  INNER JOIN wf_structure_content_values ON (wf_structure.id = wf_structure_content_values.id_structure)
			WHERE
			  wf_structure.show_in_menu = 1 
			  and wf_structure.status = 1 
			  and wf_structure.perms = 0 
			  and ('.$whereSQLQuery.')
			GROUP BY
			  wf_structure.id		
		';
	}
	
	public function getSearchCountSQLQuery($whereSQLQuery)
	{
		if (empty($whereSQLQuery))
		{
			$whereSQLQuery = '1=1';
		}
		
		return '
			SELECT 
			  COUNT(wf_structure.id) as cnt
			FROM
			  wf_structure
			  INNER JOIN wf_structure_content_values ON (wf_structure.id = wf_structure_content_values.id_structure)
			WHERE
			  wf_structure.show_in_menu = 1 
			  and wf_structure.status = 1 
			  and wf_structure.perms = 0 
			  and ('.$whereSQLQuery.')
			GROUP BY
			  wf_structure.id		
		';
	}

	public function getSearchUriProcessor($item_id, $item_uri)
	{
	 return $item_uri;
	}
}