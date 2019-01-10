<?php
class BreadCrumps extends FrontBlock
{
	/**
	 * @var Structure
	 */
	public $Structure;
	
	/**
	 * @var array
	 */
	public $AdditionalItems = array();
	
	function __construct()
	{
		parent::__construct();
		
		$this->controlerPath = BLOCKS_FRONT_BASE;
		
		$this->Structure   		= $this->Core->getModel('Structure');
		
		$this->tplVars['bc_item_title'] = array();
		$this->tplVars['bc_item_uri'] = array();
		$this->tplVars['bc_current'] = array();
		$this->tplVars['bc_menu_length'] = 0;
	}

	function initialize($params=array()) 
	{
		$mode = isset($params['mode']) ? $params['mode'] : '_level_1';
		$mnuid = isset($params['current_page_id']) ? $params['current_page_id'] : 0;

		$arr = array();

		preg_match('/^_level_(\d+)(_(\d+))?$/',$mode,$arr);

		$level_s = (sizeof($arr)>=2)?intval($arr[1]):0;
		$level_f = (sizeof($arr)>=4)?intval($arr[3]):0;

		list($arr,) = $this->Structure->getTree(0);

		$levels_act = array();

		$fl = false;

		$this->tplVars['img_title'] = '';

		if(is_array($arr))
		{
			foreach($arr as $v)
			{
				if( $level_f and $v['level'] > $level_f )continue;
				if ($mnuid==$v['id'] or $this->Structure->isDescendant($mnuid,$v['id'],$arr)) 
				{
					$this->tplVars['bc_current'][] = ($v['id']==$mnuid);
					$this->tplVars['bc_item_title'][] 	= $v['title'];
					$this->tplVars['bc_item_uri'][] 	= preg_replace('/^\//','',$v['uri']);
					$this->tplVars['bc_menu_length']++;
				}
			}
		}
		
		foreach($this->AdditionalItems as $v)
		{
			$this->tplVars['bc_current'][$this->tplVars['bc_menu_length']-1] = false;
					
			$this->tplVars['bc_current'][] 	= true;
			$this->tplVars['bc_item_title'][] = $v['title'];
			$this->tplVars['bc_item_uri'][] 	= preg_replace('/^\//','',$v['uri']);
			
			$this->tplVars['bc_menu_length']++;
		}	
	}
	
	function AddItem($item)
	{
		if(is_array($item) and isset($item['title']) and isset($item['uri']))
		{
			$this->AdditionalItems[] = $item;
		}
	}
}