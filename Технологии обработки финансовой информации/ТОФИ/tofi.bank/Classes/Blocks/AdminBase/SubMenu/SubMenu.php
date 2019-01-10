<?php     
class SubMenu extends AdminBlock
{
	/**
	 * @var AdminStructure
	 */
	protected $AdminStructure;
	
	function __construct()
	{
		parent::__construct();
		$this->controlerPath = BLOCKS_ADMIN_BASE;
		$this->AdminStructure = $this->Core->getModel('AdminStructure');
	}

	function initialize($params=array()) 
	{
		$this->tplVars['redirect_page_url'] = $this->tplVars['HTTP'].'admin/'.$this->Core->currentLangStr;
		$currentModule = $this->AdminStructure->getOne(array('uri' => $this->page_url));
			
		if($currentModule)
		{
			if($currentModule['parent'] != '')
			{
				$currentModule = $this->AdminStructure->getOne(array('template' => $currentModule['parent']));
				$this->tplVars['sub_menu_selected'][] = false;
			}
			else 
			{
				$this->tplVars['sub_menu_selected'][] = true;
			}
			
			if($currentModule)
			{
				$this->tplVars['sub_menu_url'][] = preg_replace(array('/\/\//','/^\//'),array('/',''),$currentModule['uri']);
				$this->tplVars['sub_menu_name'][] = $currentModule['title'];
				$this->tplVars['sub_menu_cnt'] = 1;
				
				$pages = $this->AdminStructure->getAll(array(
						'perms' => array('<=', $this->Core->User->UserData['id_level']),
						'parent' => $currentModule['template'],
						'hidden' => 0
					)
				);
				
				foreach ($pages as $page) {
					$this->tplVars['sub_menu_url'][] 	= preg_replace(array('/\/\//','/^\//'),array('/',''),$page['uri']);
					$this->tplVars['sub_menu_name'][] = $page['title'];
					
					if ( $this->page_url == $page['uri'] )
					{
						$this->tplVars['sub_menu_selected'][] = true;
					}
					else
					{
						$this->tplVars['sub_menu_selected'][] = false;
					}
						
					$this->tplVars['sub_menu_cnt']++;
				}
			}
		}
	}
}