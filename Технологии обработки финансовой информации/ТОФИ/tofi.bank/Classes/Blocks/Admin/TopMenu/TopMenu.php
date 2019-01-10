<?php     
class TopMenu extends AdminBlock
{
	/**
	 * @var AdminStructure
	 */
	public $AdminStructure;

	function __construct()
	{
		parent::__construct();
		$this->AdminStructure	= $this->Core->getModel('AdminStructure');
	}

	function initialize($params=array())
	{
		$this->tplVars['is_logged'] = false;
		$this->tplVars['is_admin'] = false;
		$this->tplVars['redirect_page_url'] = $this->tplVars['HTTP'].'admin/'.$this->Core->currentLangStr;
		
		if(!isset($this->Core->User->UserData['id_level']))
		{
			$this->Core->User->UserData['id_level'] = 0;	
		}
		if ( ($this->Core->User->isLogged()))
		{
			$this->tplVars['is_logged'] = true;
			if ( $this->Core->User->UserData['id_level']>=USER_LEVEL_ADMIN )
			{
				$this->tplVars['is_admin'] = true;
			}
		}
		
		$this->tplVars['modulecnt'] = 0;
		$pages = $this->AdminStructure->getAll(array(
				'hidden' => 0, 
				'parent' => '', 
				'perms' => array('<=', $this->Core->User->UserData['id_level'])
			)
		);
		
		foreach ($pages as $page) {
			if (!$this->Core->User->isAllowed($page['template']))
			{
				$parent = $page['parent'];
				if (!empty($parent))
				{
					if(!$this->Core->User->isAllowed($parent)) 
					{
						continue;
					}
				}
				else
				{
					continue;
				}
			}	
			$this->tplVars['moduleurl'][] 	= preg_replace(array('/\/\//','/^\//'),array('/',''),$page['uri']);
			$this->tplVars['modulename'][] 	= $page['title'];
			
			if ( $this->page_url == $page['uri'] )
			{
				$this->tplVars['modulesel'][] = true;
			}
			else
			{
				if ($this->page_parent == $page['template'])
					$this->tplVars['modulesel'][] = true;
				else
					$this->tplVars['modulesel'][] = false;
			}
				
			$this->tplVars['draw_right_sel'][] = true;
			$this->tplVars['modulecnt']++;
		}
		
		if ( $this->tplVars['modulecnt']>0 ) 
			$this->tplVars['draw_right_sel'][$this->tplVars['modulecnt']-1] = false;
	}
}