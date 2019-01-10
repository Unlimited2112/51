<?php
Loader::loadBlock('ItemSubEdit', 'AdminBase');

abstract class ItemSubEditWithGroups extends ItemSubEdit
{
	/**
	 * @var Model
	 */	
	protected $Group;
	protected $group_name;
	protected $id_group = 'id_group';
	protected $current_group = 0;
	protected $groups_list = array();
	
	function __construct()
	{
		parent::__construct();
		$this->controlerPath = BLOCKS_ADMIN_BASE;
		$this->TplFileName = "ItemSubEditWithGroups/ItemSubEditWithGroups.tpl";
	}
	
	function initialize($params=array()) 
	{
		$this->initControlValues();
		$this->initBaseValues();
		$this->initGroup();
		$this->initUnicValues();
		$this->initForm();
		$this->checkGroup();
		$this->checkDelete();
		$this->InitActiveTable();
		$this->processEditForm();
	}

	protected function initControlValues()
	{
		parent::initControlValues();

		$this->Group  = $this->Core->getModel($this->group_name);
		$this->Group->getOptions();
	}

	protected function initGroup()
	{
		$this->groups_list = $this->Group->getList('id', 'title', array('id_structure' => $this->tplVars['item_id']), array('sort_id' => 'asc'));
		$this->current_group = InCache('current_group', key($this->groups_list), $this->model_name);
		
		Input::setSelectData('groups_list', $this->groups_list);
		$this->tplVars['groups_list'] = $this->current_group;
	}

	protected function initUnicValues()
	{
		parent::initUnicValues();

		$this->unic += array($this->id_group => $this->current_group);
	}
	
	protected function checkGroup()
	{
		if (Form::isSubmited('groups_form'))
		{
			$group = InGetPost('groups_list', $this->current_group);

			if (isset($this->groups_list[$group]))
			{
				SetCacheVar('current_group', $group, $this->model_name);
				Core::redirect($this->tplVars['redirect_page_url']);
			} 
		}		
	}
};