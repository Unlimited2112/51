<?php
Loader::loadORM('Handler', 'CMS/Handlers');

class AdminStructureHandler extends Handler
{	
	/**
	 * @var AdminStructure
	 */
	public $AdminStructure = null;
	
	/**
	 * @var string
	 */
	public $simpleManagement = false;

	protected function __construct() 
	{
		parent::__construct();

		$this->AdminStructure = $this->Core->getModel('AdminStructure');
		$this->actionPages	= $this->AdminStructure->getActions();
		
		$this->rootPath = Config::$rootPath . 'admin/';
	}
	
	/**
	 * @return AdminStructureHandler
	 */
	public static function getInstance()
	{
		if (self::$instance == null)
		{
			self::$instance = new AdminStructureHandler();
		}
		
		return self::$instance;
	}
	
	protected function openView() 
	{
		$this->Core->tplVars['current_page_lang_str'] = $this->Core->currentLangStr;
		list ($template, $this->perms, $this->page_parent) = $this->AdminStructure->getPageSettings(trim($this->DataBase->quote($this->page_url), "'"));
		
		if ($template) 
		{
			$this->page_template = $template;
		} 
		else 
		{
			$this->page_template = 'MainPage';
			$this->page_url = '/';
		}

		$res = $this->AdminStructure->getOne(array('uri' => $this->page_url));
		if($res)
		{
			$this->Core->tplVars['current_page_title'] = $res['title'];
			$this->Core->tplVars['current_page_url']  = 'admin/'.preg_replace('/^\//','',$this->page_url);
		}
	}
		
	protected function includeTemplate()
	{
		Loader::loadPage('AdminPage', 'Admin');
		Loader::loadPage('ModelTemplate', 'Admin');
		
		if(!Loader::loadPage($this->page_template, 'Admin'))
		{
			$this->simpleManagement = true;
		}    
	}
	
	protected function processView()
	{
		if(!$this->simpleManagement)
		{
			$content = $this->page_template;
			$content = new $content();
		}
		else
		{
			$content = new ModelTemplate(str_replace('Page','',$this->page_template));
		}
		$content->parseViewData();
		$content->outputView();
	}
}