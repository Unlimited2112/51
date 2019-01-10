<?php
Loader::loadORM('Handler', 'CMS/Handlers');

class StructureHandler extends Handler
{
	/**
	 * @var Structure
	 */
	public $Structure = null;
	
	/**
	 * @var StructureTemplates
	 */
	public $StructureTemplates = null;
	
	protected function __construct() 
	{
		parent::__construct();
		
		$this->Structure 	= $this->Core->getModel('Structure');
		$this->StructureTemplates = $this->Core->getModel('StructureTemplates');
		$this->actionPages	= $this->Structure->getActions();
		
		$this->rootPath	= Config::$rootPath;
	}
	
	/**
	 * @return StructureHandler
	 */
	public static function getInstance()
	{
		if (self::$instance == null)
		{
			self::$instance = new StructureHandler();
		}
		
		return self::$instance;
	}
	
	protected function openView() 
	{
		$this->Core->tplVars['current_page_lang_str'] = $this->Core->currentLangStr;
		$this->Core->tplVars['current_page_lang_postfix'] = $this->Core->currentLangDBStr;
		$indexes = array('/index.php');
		if (in_array($this->page_url_full, $indexes)) $this->page_url_full = '/index/';
		list($template,$this->perms,$title,$page_id) = $this->Structure->getPageSettings($this->page_url);

		if ($this->Structure->isPageExist($this->page_url_full)) 
		{
			$this->page_url = $this->page_url_full;
			$this->page_params = $this->page_params_full;
		} 
		elseif (!$this->Structure->isPageExist($this->page_url)) 
		{
			$this->page_url = '/404/';
			$this->page_params =array();
		}
		
		$this->getPageDataByURI();
	}
	
	protected function getPageDataByURI()
	{
		list($template,$this->perms,$title,$page_id) = $this->Structure->getPageSettings($this->page_url);
		$this->page_template = $this->StructureTemplates->getSystemByID($template);
		$this->Core->tplVars['current_page_title'] = $title;
		$this->Core->tplVars['current_page_template'] = $this->page_template;
		$this->Core->tplVars['frontend'] = true;
		$this->Core->tplVars['current_page_id'] = $page_id;		
		$this->Core->tplVars['current_page_url'] = preg_replace('/^\//','',$this->page_url);
	}
		
	protected function includeTemplate()
	{
		Loader::loadPage('FrontPage', 'Front');
		if(!Loader::loadPage($this->page_template, 'Front'))
		{
			throw new Exception('Невозможно найти файл обьекта страницы.');
		}
	}
	
	protected function processView()
	{
		$content = $this->page_template;
		$content = new $content();
		
		$content->parseViewData();
		$content->outputView();
	}
}