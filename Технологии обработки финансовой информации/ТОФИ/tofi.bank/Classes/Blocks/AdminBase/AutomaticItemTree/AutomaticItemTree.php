<?php
Loader::loadLib('JS', 'Reformers');

class AutomaticItemTree extends AdminBlock 
{
	/**
	 * @var ModelCategory
	 */
	protected $Item;
	
	function __construct($Item) 
	{
		parent::__construct();
	
		$this->TplFileName 	= 'AutomaticItemTree/AutomaticItemTree.tpl';
		
		if (InPostGet('operation',false) == 'getTree')
		{
			$this->TplFileName 	= 'AutomaticItemTree/getTree.tpl';
		}
		
		$this->controlerPath = BLOCKS_ADMIN_BASE;
		
		$this->Item 	= $this->Core->getModel($Item);
		$this->Item->getOptions();
	}
	
	function initialize($params=array()) 
	{
		Loader::loadBlock('SubMenu', 'AdminBase');
		BlocksRegistry::getInstance()->registerBlock('SubMenu', new SubMenu($this->page));
		
		$id_lang = $this->Lang;
		
		$this->tplVars['v_id_lang'] = $id_lang;
		
		$action = InGetPost('action');
		$id_item = InGetPost('id_item', 0);
		$destination_id = InGetPost('destination_id');
	
		$this->tplVars['v_item_add_url'] = preg_replace(array('/\/\//','/^\//'),array('/',''),$this->page->page_url)."add/";
		$this->tplVars['v_item_edit_url'] = preg_replace(array('/\/\//','/^\//'),array('/',''),$this->page->page_url)."edit/";
		$this->tplVars['v_get_structure_url'] = preg_replace(array('/\/\//','/^\//'),array('/',''),$this->page->page_url)."getTree/".UUrl::getParram(1, false).'/'; 
		$this->tplVars['v_sel_item'] = UUrl::getParram(1, false); 
	
		switch ( $action ) {
			case 'button_up':
				$this->Item->moveUp($id_item);
				break;
	
			case 'button_down':
				$this->Item->moveDown($id_item);
				break;
				
			case 'button_delete':
				$this->Item->deleteItemAndUpdateSequences($id_item);
				break;
		}
	
		list($arr,$r_arr) = $this->Item->getTree(0);
	
		if (sizeof($arr)>0) 
		{
			end($arr);
			$arr[key($arr)]['level_up']++;
		}
	
		if ($arr===false or !is_array($arr)) 
		{
			return false;
		}
	
		$t_arr = array(
			0 => array (			
				$this->Item->dbId => '0',
				'title' => $this->Localizer->getString('nav_title_'.$this->Item->dbName),
				'has_children' => sizeof($arr)>0,
				'undeletable' => 0,
				'level' => '0',
				'last_in_branch' => '1',
				$this->Item->sortField => 1,
				'level_up' => 0,
				'can_add' => ($this->Item->rootAdd)?1:0,
				'can_edit' => 0,
				'levels'	 => array()
			)
		) + $arr;
	
		$i = 0;
		$data = array();
		
		foreach ( $t_arr as $item ) 
		{
			$data[$i] = array(
				'id_item' => $item[$this->Item->dbId],
				'parent' => (array_key_exists($this->Item->dbParentId, $item) ? $item[$this->Item->dbParentId] : ''),
				'children' => ((array_key_exists('children', (array_key_exists($item[$this->Item->dbId], $r_arr) and is_array($r_arr[$item[$this->Item->dbId]])) ? $r_arr[$item[$this->Item->dbId]] : array()) and is_array($r_arr[$item[$this->Item->dbId]]['children'])) ? $r_arr[$item[$this->Item->dbId]]['children'] : array()),
				'title' => (mb_strlen($item['title'], 'UTF-8')>60)?mb_substr($item['title'], 0, 50, 'UTF-8').'...':$item['title'],
				'level' => ''.$item['level'],
				'levels' => ((array_key_exists('levels', $item) and is_array($item['levels'])) ? $item['levels'] : array()) + (($item['level'] > 0) ? array_fill(0, $item['level'], 0) : array()),
				'last' => ($item['last_in_branch'] ? true : false),
				'sequence' => (int)arr_val($item, $this->Item->sortField, 0),
				'level_up' => $item['level_up'],
				'undeletable' => (int)(($this->Item->isCanDeleteSel($item[$this->Item->dbId], $this->tplVars['v_sel_item']))? 2 : ((isset($item['can_edit']) and $item['can_edit']==0)?1:0)),
				'can_add' => $item['can_add']
			);
			
			$i++;
		}
		$this->tplVars['v_js_data'] = JS::array2json($data);
	}
}