<?php
class ModelTemplate extends AdminPage
{
	/**
	 * @var Model
	 */
	public $Model;
	
	/**
	 * Current Model Name
	 *
	 * @var string
	 */
	public $management_name;
	
	/**
	 * Current Model Tabs
	 *
	 * @var array
	 */
	public $tabs = array();
	
	/**
	 * Main Tab Name
	 *
	 * @var string
	 */
	public $MainTabName = 'Edit Item';
	
	/**
	 * @param AdminStructureHandler $page
	 * @param string $management_name
	 * @return ModelTemplate
	 */
	function __construct($management_name)
	{
		$this->management_name = ucwords($management_name);
		$this->Model = Core::getInstance()->getModel($management_name);
		
		if(is_file(PAGES_ADMIN . $management_name.'/'.$management_name.'.tpl'))
		{
			parent::__construct ();
		}
		elseif (is_subclass_of($this->Model, 'ModelCategory') and InPostGet('operation',false) == 'getTree')
		{
			parent::__construct (PAGES_ADMIN . 'ModelTemplate/jsOutput.tpl');
			
			$this->layoutHeader = '';
			$this->layoutBody = '';
			$this->layoutFooter = '';
			$this->contentType = 'text/javascript';
		}
		elseif (InPostGet('operation',false) == 'getGrid')
		{
			parent::__construct (PAGES_ADMIN . 'ModelTemplate/jsOutput.tpl');
			
			$this->layoutHeader = '';
			$this->layoutBody = '';
			$this->layoutFooter = '';
			$this->contentType = 'text/html';
		}
		else
		{
			parent::__construct (PAGES_ADMIN . 'ModelTemplate/ModelTemplate.tpl');
		}
	}

	/**
	 * Main Model Function
	 */
	protected function initCustomControls()	
	{
		$operation = 'view';
		if ( InPost('operation',false) ) 
		{
			$operation = InGetPost('operation',false);
		}
		elseif ( UUrl::getParram(0, false) !== false ) 
		{
			$operation = UUrl::getParram(0, false);
		}
		
		if ( UUrl::getParram(1, false) !== false ) 
		{
			$item_id = UUrl::getParram(1, false);
		}
		else
		{
			$item_id = 0;
		}
		
		if ( UUrl::getParram(2, false) !== false ) 
		{
			$tab = UUrl::getParram(2, false);
		}
		else
		{
			$tab = '';
		}
		
		$this->tplVars['operation'] = &$operation;
		$this->tplVars['item_id'] = &$item_id;
		$this->tplVars['tab'] = &$tab;

		$this->tplVars['add_url'] = $this->tplVars['HTTP'].'admin/'.$this->Core->currentLangStr.preg_replace(array('/\/\//','/^\//'),array('/',''),$this->page_url).$operation.'/'.(!(empty($item_id))?$item_id.'/':'');

		$this->initTabs();
		
		$this->initFunctionalControls($operation);
		$this->tplVars['show_tabs'] = (!empty($this->tabs) and ($operation == 'edit') );
	}
	
	protected function initTabs()
	{
	}
	
	/**
	 * @param string $name
	 * @param string $control
	 * @param string $operation
	 * @param array $params
	 */
	protected function addTab($name, $control, $operation, $params = array())
	{
		$this->tabs[] = array($name, $control, $operation, $params);
	}
	
	/**
	 * Init Functional Controls
	 */	
	protected function initFunctionalControls($operation)
	{
		$item_view = $this->management_name.'View';
		if(Loader::loadBlock($item_view, 'Admin'))
		{
			$item_view = new $item_view();
		}
		elseif (is_subclass_of($this->Model, 'ModelCategory'))
		{
			Loader::loadBlock('AutomaticItemTree', 'AdminBase');
			$item_view = new AutomaticItemTree($this->management_name);
		}
		else 
		{
			Loader::loadBlock('AutomaticItemView', 'AdminBase');
			$item_view = new AutomaticItemView($this->management_name);
		}
		BlocksRegistry::getInstance()->registerBlock('ItemView', $item_view);
		
		if( (($operation == 'add') or ($operation == 'edit')) and ((empty($this->tplVars['tab']) or empty($this->tabs))))
		{
			$item_edit = $this->management_name.'Edit';
			if(Loader::loadBlock($item_edit, 'Admin'))
			{
				$item_edit = new $item_edit($this->management_name);
			}
			else 
			{
				Loader::loadBlock('AutomaticItemEdit', 'AdminBase');
				$item_edit = new AutomaticItemEdit($this->management_name);
			}
			BlocksRegistry::getInstance()->registerBlock('ItemEdit', $item_edit);
		}
		else 
		{
			foreach ($this->tabs as $k) 
			{
				if($this->tplVars['tab'] == $k[2])
				{
					Loader::loadBlock($k[1], 'Admin');
					$item_tab = new $k[1]($k[3]);
					BlocksRegistry::getInstance()->registerBlock('ItemEdit', $item_tab);
				}
			}
		}
		
		$this->tplVars['tabs'] = 1;
		$this->tplVars['tab_name'] = array();
		$this->tplVars['tab_operation'] = array();
		$this->tplVars['tab_current'] = array();
		
		$this->tplVars['tab_name'][] = $this->MainTabName;
		$this->tplVars['tab_operation'][] = '';
		$this->tplVars['tab_current'][] = ( (($operation == 'add') or ($operation == 'edit')) and  ($this->tplVars['tab'] == ''));
		foreach ($this->tabs as $k) 
		{
			$this->tplVars['tab_name'][] = $k[0];
			$this->tplVars['tab_operation'][] = $k[2].'/';
			$this->tplVars['tab_current'][] =(bool)($this->tplVars['tab'] == $k[2]);
			$this->tplVars['tabs']++;
		}
	}
}