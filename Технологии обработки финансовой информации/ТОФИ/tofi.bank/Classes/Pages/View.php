<?php
class View
{	
	/**
	 * Ядро
	 * 
	 * @var Core
	 */
	public $Core;
	
	/**
	 * База данных 
	 *
	 * @var DataBase
	 */
	public $DataBase = null;
	
	/**
	 * Пользователи
	 *
	 * @var User
	 */
	public $User = null;
	
	/**
	 * Локализация
	 *
	 * @var Localizer
	 */
	public $Localizer = null;
	
	/**
	 * Настройки
	 *
	 * @var Settings
	 */
	public $Settings = null;

	/**
	 * Массив переменных шаблонов
	 *
	 * @var array
	 */
	public $tplVars;
		
	/**
	 * ID текущего языка
	 *
	 * @var string
	 */
	public $Lang;
	
	/**
	 * Хэндлер
	 * 
	 * @var Handler
	 */	
	public $page = null;
	
	/**
	 * Входные параметры
	 * 
	 * @var array
	 */
	public $page_params;
	
	/**
	 * URL
	 * 
	 * @var string
	 */
	public $page_url;
	
	/**
	 * Значение пути
	 * 
	 * @var root_path
	 */
	public $root_path;
	
	/**
	 * Родитель страницы
	 * 
	 * @var string
	 */
	public $page_parent;

	/**
	 * Страница проверяется на безопасность
	 *
	 * @var bool
	 */
	protected $isSecure = false;
	
	/**
	 * Уровень доступа
	 *
	 * @var integer
	 */
	protected $UserLevel = USER_LEVEL_USER;

	/**
	 * Тип контента
	 *
	 * @var string
	 */
	protected $contentType = 'text/html';
	
	/**
	 * Кодировка
	 *
	 * @var string
	 */
	protected $charset = 'UTF-8';

	/**
	 * Header layout
	 *
	 * @var string
	 */
	protected $layoutHeader = 'Header.tpl';
	
	/**
	 * Body layout
	 *
	 * @var string
	 */
	protected $layoutBody = 'Body.tpl';
	
	/**
	 * Content layout
	 *
	 * @var string
	 */
	protected $layoutContent = '';
	
	/**
	 * Footer layout
	 *
	 * @var string
	 */
	protected $layoutFooter = 'Footer.tpl';

	/**
	 * Массив контроллеров
	 *
	 * @var string
	 */
	public $ViewControls = array();

	/**
	 * Конструктор
	 *
	 * @param string $content Content layout
	 */
	function __construct($content = '') 
	{
		$this->layoutContent = $content;
		
		$this->Core = Core::getInstance();
		
		$this->Core->currentView = $this;
		
		$this->DataBase = $this->Core->DataBase;
		$this->User = $this->Core->getModel('User');
		$this->Localizer = $this->Core->Localizer;
		$this->Settings = $this->Core->Settings;
		
		if(is_object($this->page))
		{
			$this->page_params = &$this->page->page_params;
			$this->page_url = &$this->page->page_url;
			$this->page_parent = &$this->page->page_parent;
			$this->root_path = &$this->page->rootPath;
		}
		
		$this->Lang	= &$this->Core->currentLang;
		$this->tplVars = &$this->Core->tplVars;

		$this->tplVars['admin_page'] = false;

		$this->setURIValues();
	}

	protected function setURIValues()
	{
		$this->tplVars['JS'] = Config::$jsPath;
		$this->tplVars['CSS'] = Config::$cssPath;
		$this->tplVars['IMAGES'] = Config::$imagesPath;
		$this->tplVars['CONTENT'] = Config::$contentPath;
		$this->tplVars['ROOT'] = Config::$rootPath;

		if (intval(Config::$httpPort) != 80)
			$http_port = ':'.Config::$httpPort;
		else
			$http_port = '';

		$this->tplVars['HTTP'] = Config::$httpName.'://'.Config::$siteUrl.$http_port.Config::$rootPath;
		
		Config::$baseUrl = &$this->tplVars['HTTP'];		
	}
	
	protected function accessDenied()
	{
		Loader::loadBlock('ArrayOutput', 'Helper');
		BlocksRegistry::getInstance()->registerBlock('ArrayOutput', new ArrayOutput());

		$this->tplVars['login_form_errors'][] = $this->Core->Localizer->getString('login_access_denied');

		$this->setLogin();
	}
	
	protected function setLogin()
	{
		if (isset($this->tplVars['frontend']) and $this->tplVars['frontend'])
		{
			$this->layoutContent = PAGES_LAYOUTS.'Login.tpl';
			$this->initialize();
		}
		else
		{					
			$this->layoutHeader = '';
			$this->layoutBody = '';
			$this->layoutContent = PAGES_LAYOUTS.'AdminLogin.tpl';
			$this->layoutFooter = '';
			
			$this->initialize();
		}
	}

	protected function getHeader()
	{
		$this->drawCacheHeader();

		if ( (file_exists(PAGES_LAYOUTS.$this->layoutHeader)) && (strlen($this->layoutHeader)) ) {
			extract($this->tplVars, EXTR_SKIP or EXTR_REFS);
			include(PAGES_LAYOUTS.$this->layoutHeader);
		}
	}

	protected function getBody()
	{
		if ( (file_exists(PAGES_LAYOUTS.$this->layoutBody)) && (strlen($this->layoutBody)) )
		{
			extract($this->tplVars, EXTR_SKIP or EXTR_REFS);
			include(PAGES_LAYOUTS.$this->layoutBody);
		}

	}

	protected function getContent()
	{
		if((file_exists($this->layoutContent)) && (strlen($this->layoutContent))) 
		{
			extract($this->tplVars, EXTR_SKIP or EXTR_REFS);
			include($this->layoutContent);
		}
		else
		{
			Debugger::addString('Invalid template '.$this->layoutContent);
		}
	}

	protected function getFooter()
	{
		if ( (file_exists(PAGES_LAYOUTS.$this->layoutFooter)) && (strlen($this->layoutFooter)) )
		{
			extract($this->tplVars, EXTR_SKIP or EXTR_REFS);
			include(PAGES_LAYOUTS.$this->layoutFooter);
		}
	}

	protected function drawCacheHeader()
	{
		@header('Cache-control: no-cache');
		@header('Pragma: no-cache');
		@header('Expires: 0');
	}

	protected function checkForms()
	{
		$form = InPostGet('send_form_name', '');
		$action = InPostGet('form_action', '');
		if (strlen($form))
		{
			$method = 'on'.$form.'Submit';
			if (@method_exists($this, $method))
			{
				return call_user_func(array(&$this, $method), $action);
			}
			
			$controls = array_keys($this->ViewControls);
			foreach ($controls as $control)
			{
				if (@method_exists($this->ViewControls[$control], $method))
				{
					return call_user_func(array(&$this->ViewControls[$control], $method), $action);
				}
			}
		}
		return true;
	}

	protected function initialize() 
	{
		Loader::loadBlock('BaseBlock');
		
		Loader::loadBlock('ArrayOutput', 'Helper');
		BlocksRegistry::getInstance()->registerBlock('ArrayOutput', new ArrayOutput());
		
		Loader::loadLib('UUrl', 'Other');
		UUrl::initialize();
	}

	function parseViewData()
	{
		if(!$this->Core->User->loginFormCheck()) $this->setLogin();
		$this->Core->User->logoutFormCheck();

		$this->Core->User->setLoggedVariables($this->tplVars);

		$return_value = true;

		if ($this->isSecure)
		{
			if (!$this->Core->User->isLogged())
			{
				$this->setLogin();
				$return_value = false;
			}
			elseif ($this->Core->User->UserData['id_level'] < $this->UserLevel)
			{
				$this->accessDenied();
				$return_value = false;
			}
			elseif ((!$this->Core->User->isAllowed($this->page->page_template)) and ($this->Core->User->UserData['id_level'] == USER_LEVEL_ADMIN) and ($this->IsCheckAllowed))
			{
				if(!empty($this->page->page_parent))
				{
					if(!$this->Core->User->isAllowed($this->page->page_parent))
					{
						$this->accessDenied();
						$return_value = false;
					}
				}
				else 
				{
					$this->accessDenied();
					$return_value = false;
				}
			}
		}
		
		$this->Core->initialize();
		
		if ($return_value)
		{
			$this->initialize();
			$return_value = $this->checkForms();
		}
		
		return $return_value;
	}
	
	function outputView()
	{
		$this->getHeader();
		$this->getBody();
		$this->getContent();
		$this->getFooter();
		
		if ( (Config::$debugLevel) && ($this->contentType == 'text/html') )
		{
			Debugger::outputLog();
		}

		@ob_end_flush();
	}
}