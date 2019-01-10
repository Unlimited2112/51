<?php
function getFormattedMicrotime() 
{
	list($usec, $sec) = explode(' ', microtime());
	return $usec+$sec;
}
$CoreStartTime = getFormattedMicrotime();

class Config
{	
	/**
	 * Название сайта
	 *
	 * @var string
	 */
	public static $siteName = '';
	
	/**
	 * Сформированный URL сайта
	 *
	 * @var string
	 */
	public static $baseUrl = '';
	
	/**
	 * Базовый URL сайта
	 *
	 * @var string
	 */
	public static $siteUrl = '';
	
	/**
	 * HTTP порт сайта
	 *
	 * @var string
	 */
	public static $httpPort = '80';
	
	/**
	 * HTTP протокол сайта
	 *
	 * @var string
	 */
	public static $httpName = 'http';
	
	/**
	 * Базовый путь сайта
	 *
	 * @var string
	 */
	public static $rootPath = '/';
	
	/**
	 * Базовый путь файловой системы
	 *
	 * @var string
	 */
	public static $filePath = '';
	
	/**
	 * Дополнение к пути файловой системы
	 *
	 * @var string
	 */
	public static $filePathAdd = '';
	
	/**
	 * Базовый путь к CSS файлам
	 *
	 * @var string
	 */
	public static $cssPath = '';
	
	/**
	 * Базовый путь к JS файлам
	 *
	 * @var string
	 */
	public static $jsPath = '';
	
	/**
	 * Базовый путь к файлам изображений
	 *
	 * @var string
	 */
	public static $imagesPath = '';
	
	/**
	 * Базовый путь к загруженным файлам
	 *
	 * @var string
	 */
	public static $contentPath = '';
	
	/**
	 * Текущий тип сервера
	 *
	 * @var string
	 */
	public static $serverType = 'Localhost';
	
	/**
	 * Текущий уровень дебага
	 *
	 * @var integer
	 */
	public static $debugLevel = 0;
	
	/**
	 * Разрешение на дебаг
	 *
	 * @var integer
	 */
	public static $debugEnable = 0;
	
	/**
	 * Разрешение на кэширование в файловой системе
	 *
	 * @var integer
	 */
	public static $enableFileCache = 0;
	
	/**
	 * Страницы с передаваемыми параметрами
	 *
	 * @var array
	 */
	public static $frontActionPages = array();
	
	/**
	 * Страницы с передаваемыми параметрами
	 *
	 * @var array
	 */
	public static $adminActionPages = array();
	
	/**
	 * Инициализация
	 */
	public static function initialize() 
	{	
		self::setSiteVars();
		self::defineDataBaseVariables();
		self::definePaths();
		self::setIniValues();
		self::checkDebug();
	}
	
	/**
	 * Установка переменных сайта
	 */
	private static function setSiteVars()
	{
		self::$siteName = 'Tofi.Bank';
		self::$serverType = 'Localhost';
		    
		switch (self::$serverType) 
		{
			case 'Localhost':
					self::$siteUrl = 'tofi.bank';
					self::$debugEnable = 1;
					self::$enableFileCache = 1;
				break;

			case 'Prod':			
					self::$siteUrl = 'tofi.bank';
					self::$debugEnable = 0;
					self::$enableFileCache = 1;
				break;
		}
	}
	
	/**
	 * Установка переменных базы данных
	 */
	private static function defineDataBaseVariables()
	{		
		define('DB_PORT', '3306');
		define('DB_ENGINE', 'InnoDB');
		define('DB_CHARSET', 'UTF8');
		
		switch (self::$serverType) 
		{
			case 'Localhost':
					define('DB_HOST', 'localhost');
					define('DB_USER', 'root');
					define('DB_PASSWORD', 'root');
					define('DB_DATABASE', 'tofi');
				break;

			case 'Prod':			
					define('DB_HOST', 'localhost');
					define('DB_USER', 'root');
					define('DB_PASSWORD', '1');
					define('DB_DATABASE', '1');
				break;
		}
	}
	
	/**
	 * Установка переменных путей
	 */
	private static function definePaths()
	{
		self::$filePath = $_SERVER['DOCUMENT_ROOT'];
		if (substr(self::$filePath, -1) != '/') 
		{
			self::$filePath .= '/';
		}
		self::$filePath .= self::$filePathAdd;	
		
		self::$cssPath = self::$rootPath . 'css/';
		self::$jsPath = self::$rootPath . 'js/';
		self::$imagesPath = self::$rootPath . 'images/';
		self::$contentPath = self::$rootPath . 'content/';
		
		define('CACHED_DATA', self::$filePath . '_cache/data/'); 
		
		define('CORE', self::$filePath . 'Classes/Core/');
		
		define('BLOCKS', self::$filePath . 'Classes/Blocks/');
		define('BLOCKS_ADMIN', self::$filePath . 'Classes/Blocks/Admin/');
		define('BLOCKS_ADMIN_BASE', self::$filePath . 'Classes/Blocks/AdminBase/');
		define('BLOCKS_FRONT', self::$filePath . 'Classes/Blocks/Front/');
		define('BLOCKS_FRONT_BASE', self::$filePath . 'Classes/Blocks/FrontBase/');
		
		define('LIBS', self::$filePath . 'Classes/Libs/'); 
		
		define('ORM', self::$filePath . 'Classes/ORM/');
		
		define('PAGES', self::$filePath . 'Classes/Pages/'); 
		define('PAGES_ADMIN', self::$filePath . 'Classes/Pages/Admin/');
		define('PAGES_FRONT', self::$filePath . 'Classes/Pages/Front/'); 
		define('PAGES_LAYOUTS', self::$filePath . 'Classes/Pages/Layouts/'); 
		
		define('FUNCTIONS', self::$filePath . 'Classes/Functions/');
		
		define('OPTIONAL', 0);
		define('MANDATORY', 1);
		define('MANDATORY_OPTIONAL', 2);
		
		define('DATE_FORMAT', '%Y-%m-%d');
		define('DATE_FORMAT_FRONT', '%d.%m.%Y');
		define('DATETIME_FORMAT', '%d.%m.%Y в %H:%M');
	}
	
	/**
	 * Установка переменных php.ini
	 */
	private static function setIniValues()
	{
		@ini_set('session.use_only_cookies', '1');
		@ini_set('arg_separator.output', '&amp;');
		@ini_set('magic_quotes_runtime', '0');
		@ini_set('magic_quotes_gpc', '0');
		@ini_set('upload_max_filesize', '48M');
		@ini_set('post_max_size', '64M');
		@ini_set('mbstring.language', 'Russian');
		@ini_set('mbstring.encoding_translation', 'On');
		@ini_set('mbstring.func_overload', 4);
		
		@set_time_limit(0); 
		setlocale(LC_ALL,array('en_EN.UTF8'));
	}
	
	/**
	 * Установка уровня дебага
	 */
	private static function checkDebug()
	{
		if(self::$debugEnable != 0)
		{
			if (isset($_COOKIE['debugging'])) self::$debugLevel = $_COOKIE['debugging'];
			if (isset($_GET['debug'])) 
			{
				self::$debugLevel = (strcasecmp($_GET['debug'], '1')==0)?255:0;
			}
			@setcookie('debugging', self::$debugLevel, time()+60*60*24*30, '/');
		}
		
		if (self::$debugLevel) {
		    @error_reporting(E_ALL & ~E_STRICT);
		    @ini_set('display_errors', '1');
		    @ini_set('display_startup_errors', '1');
		}
		else {
		    @error_reporting(0);
		    @ini_set('display_errors', '0');
		    @ini_set('display_startup_errors', '0');
		}
	}
}

Config::initialize();