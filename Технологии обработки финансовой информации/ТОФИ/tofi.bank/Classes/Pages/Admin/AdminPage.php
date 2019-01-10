<?php
class AdminPage extends View 
{
	function __construct($template = null)
	{
		$this->layoutBody = 'AdminBody.tpl';
		$this->layoutHeader = 'AdminHeader.tpl';
		$this->layoutFooter = 'AdminFooter.tpl';

		$this->page	= AdminStructureHandler::getInstance();
		
		if($template != null)
		{
			parent::__construct ( $template );
		}
		else
		{
			parent::__construct ( PAGES_ADMIN . $this->page->page_template .'/' . $this->page->page_template .'.tpl' );
		}
		
		$this->isSecure = true; 
		$this->IsCheckAllowed = true; 
		$this->UserLevel = $this->page->perms;
		
		$this->tplVars['admin_page'] = true;
	}

	protected function initialize()	
	{
		parent::initialize();
		
		$this->tplVars['info'] = unserialize(InCache('info',serialize(array())));
		SetCacheVar ( 'info', serialize(array()) );
		
		$this->checkLangsForm();
		$this->setLangsForm();
		
		$this->initControls();
		$this->initCustomControls();
		
		$this->process();
		
		header('Content-type: '.$this->contentType.'; charset='.$this->charset);
	}
	
	protected function initControls()
	{
		Loader::loadBlock('AdminBlock', 'AdminBase');
		
		Loader::loadBlock('FormGenerator', 'Form/FormGenerator');
		$formGenerator = new FormGenerator ( 'AdminForm');
		BlocksRegistry::getInstance()->registerBlock('FormGenerator', $formGenerator);
		$formGenerator->defaultSubmitTitle = 'button_save';
		
		Loader::loadBlock('TopMenu', 'Admin');
		BlocksRegistry::getInstance()->registerBlock('TopMenu', new TopMenu());
	}

	protected function initCustomControls()
	{
		
	}
	
	protected function checkLangsForm()
	{
		if (Form::isSubmited('admin_area_lang_form'))
		{
			$this->Core->currentLang = InPost('admin_area_id_lang', 1);
			SetCacheVar('admin_area_id_lang', $this->Core->currentLang);
		}
		else 
		{
			$this->Core->currentLang = InCache('admin_area_id_lang', 1);
		}
	}

	protected function setLangsForm()
	{
		$this->tplVars['admin_area_langs'] = false;
		
		$langs = $this->Core->Localizer->getLangs();
		
		if (sizeof($langs)>1)
		{
			$this->tplVars['admin_area_langs'] = true;
			$this->tplVars['admin_area_id_lang'] = $this->Core->currentLang;
			$this->tplVars['admin_area_id_lang_selected'] = $this->Core->currentLang;
			Input::setSelectData('admin_area_id_lang', $langs);
		}
	}
	
	protected function process() {}
	
	function parseViewData() 
	{
		if ( !parent::parseViewData() )	return false;
	}
}