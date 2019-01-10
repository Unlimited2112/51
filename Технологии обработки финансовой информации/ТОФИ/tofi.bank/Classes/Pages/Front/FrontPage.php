<?php
class FrontPage extends View 
{
	/**
	 * @var Structure
	 */
	public $Structure;

	/**
	 * @var PageTitle
	 */
	public $PageTitle;
	
	/**
	 * @var BreadCrumps
	 */
	public $BreadCrumps;

	function __construct()
	{
		$this->page = StructureHandler::getInstance();
		
		parent::__construct ( PAGES_FRONT . $this->page->page_template . '/' . $this->page->page_template .'.tpl' );
		
		$this->Structure = $this->Core->getModel('Structure');
		if($this->page->perms)
		{
			$this->isSecure 	= true; 
			$this->UserLevel	= $this->page->perms; 
		}
	}

	protected function initialize()	
	{
		parent::initialize();
		
		Loader::loadBlock('Thumbnail', 'Helper');
		BlocksRegistry::getInstance()->registerBlock('Thumbnail', new Thumbnail());
		
		$this->tplVars += $this->Structure->getContentValues($this->tplVars['current_page_id']);
		$this->tplVars['site_title'] = $this->Settings->getSetting('site_title');
		$this->tplVars['HTTPl'] = $this->tplVars['HTTP'].$this->tplVars['current_page_lang_str'];
		
		$this->tplVars['info'] = unserialize(InCache('info',serialize(array())));
		SetCacheVar ( 'info', serialize(array()) );
		
		$this->initControls();
		
		$this->process();

		header('Content-type: '.$this->contentType.'; charset='.$this->charset);
	}
	
	protected function initControls()
	{
		Loader::loadBlock('FrontBlock', 'FrontBase');
		
		Loader::loadBlock('FormGenerator', 'Form/FormGenerator');
		$formGenerator = new FormGenerator ('FrontForm');
		$formGenerator->defaultSubmitTitle = 'txt_send';
		BlocksRegistry::getInstance()->registerBlock('FormGenerator', $formGenerator);
		
		Loader::loadBlock('PageTitle', 'FrontBase');
		$this->PageTitle = new PageTitle();
		BlocksRegistry::getInstance()->registerBlock('PageTitle', $this->PageTitle);

		Loader::loadBlock('BreadCrumps', 'FrontBase');
		$this->BreadCrumps = new BreadCrumps();
		BlocksRegistry::getInstance()->registerBlock('BreadCrumps', $this->BreadCrumps);
		
		Loader::loadBlock('Menu', 'FrontBase');
		BlocksRegistry::getInstance()->registerBlock('Menu', new Menu());
		Loader::loadBlock('InnerMenu', 'Front');
		BlocksRegistry::getInstance()->registerBlock('InnerMenu', new InnerMenu());
		
		$copy_year = $this->Settings->getSetting('copy_years');
		if($copy_year < date('Y')) {
			$copy_year .= ' - ' . date('Y');
		}
		$this->tplVars['copy_years'] = $copy_year;
		
		if(isset($_COOKIE['day_type'])) {
			if($_COOKIE['day_type'] == 'day') {
				$day_type = 'white';
			}
			elseif($_COOKIE['day_type'] == 'night') {
				$day_type = '';
			}
		}
		
		if(isset($day_type)) {
			$this->tplVars['day_type'] = $day_type;
		}
		else {
			$day_start = (int) $this->Settings->getSetting('day_start');
			$night_start = (int) $this->Settings->getSetting('night_start');
			$h = date('G');
			if($h < $day_start) {
				$day_type = '';
			}
			elseif ($h >= $night_start) {
				$day_type = '';
			}
			else {
				$day_type = 'white';
			}
			$this->tplVars['day_type'] = $day_type;
		}
	}
	
	protected function process() {}
	
	function addPageItems($add_pages)
	{
		$this->PageTitle->addItem($add_pages);
		$this->BreadCrumps->addItem($add_pages);
	}
	
	function parseViewData() 
	{
		if ( !parent::parseViewData() )
		{
			return false;
		}
	}
}