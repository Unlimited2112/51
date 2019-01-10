<?php
Loader::loadBlock('AutomaticItemEdit', 'AdminBase');

class UserEdit extends AutomaticItemEdit
{
	/**
	 * @var AdminStructure
	 */
	protected $AdminStructure;
	
	function __construct()	
	{
		parent::__construct('User');
		$this->AdminStructure = $this->Core->getModel('AdminStructure');
	}
	
	protected function AfterInitValidators($item_id, $operation)
	{
		if($item_id == $this->Item->UserData['id'])
		{
			Core::redirect ( $this->tplVars['redirect_page_url'] );
		}	
		if($operation == 'edit')
		{
			$user = $this->Item->GetById($item_id);
			if($user)
			{
				$this->tplVars['edit_admin'] = ($user['id_level'] == USER_LEVEL_ADMIN);
			}
			else 
			{
				$this->tplVars['edit_admin'] = false;
			}
			
			if (Form::isSubmited('item_edit_form'))
			{
				if (Form::validate('item_edit_form'))
				{
					$pages = $this->AdminStructure->getAll(array('hidden' => 0, 'parent' => ''));
					foreach ($pages as $page) {
						if(InPost($page['template'], 0) != 0) {
							$this->Item->addPermission($page['template'], $item_id);
						}
						else {
							$this->Item->removePermission($page['template'], $item_id);
						}
					}
				}
			}
				
			if ($this->tplVars['edit_admin']) 
			{
				$this->tplVars['permissions'] = 0;
				$this->tplVars['permission'] = array();
				$this->tplVars['permission_title'] = array();
				$this->tplVars['permission_sep'] = array();
				$this->tplVars['permission_sep_title'] = array();
				$pages = $this->AdminStructure->getAll(array('hidden' => 0, 'parent' => ''));
				
				foreach ($pages as $page) {
					$this->tplVars['permission_sep'][] = false;
					$this->tplVars['permission_sep_title'][] = '';
					$this->tplVars['permission'][] = $page['template'];
					$this->tplVars['permission_title'][] = $page['title'];
					$this->tplVars[$page['template']] = (int)$this->Item->isAllowed($page['template'], $item_id);
					$this->tplVars['permissions']++;
				}

				$this->tplVars['custom_data'] = $this->getUserPermissions();
			}
		}
	}
	
	protected function getUserPermissions() {
		ob_start();
		extract($this->tplVars, EXTR_SKIP or EXTR_REFS);
		include(BLOCKS_ADMIN . 'UserEdit/UserPermissions.tpl');
		return ob_get_clean();
	}
};