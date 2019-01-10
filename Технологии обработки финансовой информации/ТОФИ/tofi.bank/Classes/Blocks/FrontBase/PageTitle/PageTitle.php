<?php
class PageTitle extends FrontBlock
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
		
		$this->tplVars['pt_item_title'] 		= array();
		$this->tplVars['pt_current'] 			= array();
		$this->tplVars['pt_menu_length'] 		= 0;
	}

	function initialize($params=array()) 
	{
		$mode = isset($params['mode']) ? $params['mode'] : '_level_1';
		$mnuid = isset($params['current_page_id']) ? $params['current_page_id'] : 0;
		$default_page_title = isset($params['page_title']) ? $params['page_title'] : '';

		$arr = array();

		preg_match('/^_level_(\d+)(_(\d+))?$/',$mode,$arr);

		$level_s = (sizeof($arr)>=2)?intval($arr[1]):0;
		$level_f = (sizeof($arr)>=4)?intval($arr[3]):0;

		list($arr,) = $this->Structure->getTree(0);

		$levels_act = array();

		$fl = false;

		$this->tplVars['img_title'] = '';

		if (!$default_page_title) 
		{
			if(is_array($arr))
			{
				foreach($arr as $v)
				{
					if( $level_f and $v['level'] > $level_f )continue;
					if ($mnuid==$v['id'] or $this->Structure->isDescendant($mnuid,$v['id'],$arr)) 
					{
						$this->tplVars['pt_current'][] = ($v['id']==$mnuid);
						$this->tplVars['pt_item_title'][] 	= $v['title'];
						$this->tplVars['pt_menu_length']++;
					}
				}
			}
		} 
		else 
		{
			$this->tplVars['pt_current'][] 	= true;
			$this->tplVars['pt_item_title'][] = $default_page_title;
			$this->tplVars['pt_menu_length']++;
		}
		
		foreach($this->AdditionalItems as $v)
		{
			$this->tplVars['pt_current'][$this->tplVars['pt_menu_length']-1] = false;
					
			$this->tplVars['pt_current'][] 	= true;
			$this->tplVars['pt_item_title'][] = $v['title'];
			
			$this->tplVars['pt_menu_length']++;
		}	
	}
	
	function AddItem($item)
	{
		if(is_array($item) and isset($item['title']))
		{
			$this->AdditionalItems[] = $item;
		}
	}
}