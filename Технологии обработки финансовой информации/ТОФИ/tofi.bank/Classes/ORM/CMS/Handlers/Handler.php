<?php
Loader::loadORM('Request', 'CMS/Handlers');

abstract class Handler
{
	/**
	 * Class instance (for Singleton usage)
	 *
	 * @var Handler
	 */
	protected static $instance = null;
		
	/**
	 * Page Core Object
	 *
	 * @var Core
	 */
	public $Core = null;

	/**
	 * DataBase Object
	 *
	 * @var DataBase
	 */
	public $DataBase = null;
	
	/**
	 * UrlRequestParser Object
	 *
	 * @var UrlRequestParser
	 */
	public $UrlRequestParser = null;

	/**
	 * Массив URL активных страниц
	 *
	 * @var array
	 */
	public $actionPages = array();
	
	/**
	 * @var string
	 */
	public $page_params = '';
	
	/**
	 * @var string
	 */
	public $page_params_full = '';

	/**
	 * @var string
	 */
	public $page_url = '';

	/**
	 * @var string
	 */
	public $page_url_full = '';

	/**
	 * @var string
	 */
	public $page_title = '';
	
	/**
	 * @var string
	 */
	public $rootPath = '';

	/**
	 * @var string
	 */
	public $page_template = '';
	
	/**
	 * @var array
	 */
	protected $indexes = array('/index.php');
	
	/**
	 * @return StructureHandler
	 */
	protected function __construct() 
	{
		$this->Core 		= Core::getInstance();
		
		$this->DataBase 	= $this->Core->DataBase;
		
		$this->rootPath	= Config::$rootPath;
	}
	
	/**
	 * Получить экземпляр объекта
	 * 
	 * @return Handler
	 */
	abstract public static function getInstance();
	
	/**
	 * Инициализация
	 */
	public function initialize()
	{
		$this->getPageParrams();
		$this->openView();
		$this->includeTemplate();

		//Core::outputBufferReStart();   
		
		$this->processView();
	}
	
	/**
	 * Получение параметров страницы
	 */
	protected function getPageParrams()
	{
		$this->UrlRequestParser = new UrlRequestParser();
		$this->UrlRequestParser->rootPath = $this->rootPath;
		$this->UrlRequestParser->actionPages = $this->actionPages;
		$this->UrlRequestParser->getPageValues();
		
		$this->page_url = $this->UrlRequestParser->pageUrl;
		$this->page_url_full = $this->UrlRequestParser->pageUrlFull;
		$this->page_params 	= $this->UrlRequestParser->pageParams;
		$this->page_params_full 	= $this->UrlRequestParser->pageParamsFull;
		
		$this->Core->currentLang = $this->UrlRequestParser->pageLang;
		$this->Core->currentLangStr = $this->UrlRequestParser->pageLangStr;
		$this->Core->currentLangDBStr = $this->UrlRequestParser->pageLangDBStr;
	}
	
	/**
	 * Подключение обработчика страницы
	 */
	abstract protected function includeTemplate();
	
	/**
	 * Инициализация обработчика страницы
	 */
	abstract protected function processView();
	
	/**
	 * Процессинг обработчика страницы
	 */
	abstract protected function openView();
}