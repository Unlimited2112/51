<?php
Loader::loadFunction('functions');

Loader::loadCore('Debugger');

Loader::loadBlock('BlocksRegistry');
Loader::loadBlock('Block');
Loader::loadBlock('Localizer', 'Helper');
Loader::loadLib('FileCache', 'Files');
Loader::loadLib('FileSystem', 'Files');

Loader::loadORM('Model', 'Base');
Loader::loadORM('ModelCategory', 'Base');
Loader::loadORM('ModelOptions', 'Base');

Loader::loadBlock('Validator', 'Form');
Loader::loadBlock('Form', 'Form');
Loader::loadBlock('Input', 'Form/Input');

Loader::loadPage('View');
Loader::loadORM('DataBase', 'DB');

final class Core
{
	/**
	 * Class instance (for Singleton usage)
	 *
	 * @var Core
	 */
	protected static $instance = null;
	
	/**
	 * Список объектов приложения 
	 *
	 * @var array
	 */
	protected $Models = array();

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
	 * Обработчик отображения
	 *
	 * @var View
	 */
	public $currentView = null;

	/**
	 * ID текущего языка
	 *
	 * @var string
	 */
	public $currentLang = 1;

	/**
	 * URL текущего языка
	 *
	 * @var string
	 */
	public $currentLangStr = '';
	
	/**
	 * Постфикс базы данных
	 *
	 * @var string
	 */
	public $currentLangDBStr = '';

	/**
	 * Массив переменных шаблонов
	 *
	 * @var array
	 */
	public $tplVars = array(
	);

	/**
	 * @return Core
	 */
	protected function __construct() {}

	/**
	 * Получить экземпляр объекта
	 * 
	 * @return Core
	 */
	public static function getInstance() {
		if (self::$instance == null) {
			self::$instance = new Core();
		}
		
		return self::$instance;
	}
	
	/**
	 * Получить все объекты приложения
	 *
	 * @param string $mod_name
	 * @return Model
	 */
	public function setModels($Models) 
	{
		$this->Models = $Models;
	}

	/**
	 * Получить все объекты приложения
	 *
	 * @param string $mod_name
	 * @return Model
	 */
	public function getModels() 
	{
		return $this->Models;
	}
	
	/**
	 * Получить экземпляр объекта приложения
	 *
	 * @param string $mod_name
	 * @return Model
	 */
	public function getModel($modelName)
	{
		if (isset($this->Models[$modelName]) and is_object($this->Models[$modelName]))
		{
			return $this->Models[$modelName];
		} 
		elseif (isset($this->Models[$modelName]))
		{
			if (Loader::loadORM($modelName, $this->Models[$modelName]))
			{
				$modelFolder = $this->Models[$modelName];
				$this->Models[$modelName] = new $modelName();
				$this->Models[$modelName]->modelFolder = $modelFolder;
				return $this->Models[$modelName];
			}
		} 
		else 
		{
			if (Loader::loadORM($modelName))
			{
				$this->Models[$modelName] = new $modelName();
				return $this->Models[$modelName];
			}
		}
		
		return null;
	}

	/**
	 * Вызывается при инициализации обработчика страницы
	 */
	public function initialize()
	{
		$this->tplVars['site_name'] = Config::$siteName;
		$this->tplVars['site_shortname'] = '- ' . Config::$siteName;
		
		return true;
	}

	/**
	 * Обработчик критических ошибок приложения 
	 * 
	 * @param CoreExeption $exeption
	 */
	public static function coreDie($exeption, $mailOnProduction = false) 
	{
		if (Config::$debugEnable) 
		{
			self::outputBufferReStart();
			
			@header('Content-type: text/html; charset=utf-8');
			@header('HTTP/1.1 500 Internal server error');
			@header('Status: 500 Internal server error');
			
			$error_data = self::coreExceptionFormattedTrace($exeption);
			
			echo $error_data;
			Debugger::outputLog();
			
			$error_data = PHP_EOL.PHP_EOL.date('d.m.Y H:i:s').PHP_EOL.$error_data;
			FileSystem::writeFile(Config::$filePath.'core_errors.log', $error_data, 'a+');
			
			die();
		}
		else 
		{
			$core = Core::getInstance();
			
			$error_data = self::coreExceptionFormattedTrace($exeption);;
			$error_data = PHP_EOL.PHP_EOL.date('d.m.Y H:i:s').PHP_EOL.$error_data;
			FileSystem::writeFile(Config::$filePath.'core_errors.log', $error_data, 'a+');
			
			Core::redirect(Config::$baseUrl . $core->currentLangStr);
		}
	}

	/**
	 * Возвращает оформленное сообщение о критической ошибке приложения 
	 * 
	 * @param CoreExeption $exeption
	 * @return string
	 */
	public static function coreExceptionFormattedTrace($exeption)
	{
		throw $exeption;
		$db = $exeption->getTrace();
		$errorText = '';
		for ($i = sizeof($db); $i > 0; $i--) {
			$errorText .= '<nobr> on line <b>' . ((isset($db[$i - 1]['line'])) ? ($db[$i - 1]['line']) : ('?')) . '</b> of file <b>' . ((isset($db[$i - 1]['file'])) ? ($db[$i - 1]['file']) : ('?')) . '</b></nobr><br />';
		}
		
		$errorText = '<p align="left" style="padding-top: 30px;">
			<b><font color="red">Системная ошибка, сообщите администратору:</font></b> ' . $exeption->getMessage() . '<br /><br />' . $errorText . '</p>
		';
		
		return $errorText;
	}

	/**
	 * Инициализация буферизации
	 */
	public static function outputBufferStart()
	{
		if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) and strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') and !(strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE') and isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strstr($_SERVER['HTTP_X_REQUESTED_WITH'], 'XMLHttpRequest')))
		{
			@ob_start();
//			@ob_start('ob_gzhandler');
		}
		else
		{
			@ob_start();
		}
	}

	/**
	 * Ре-инициализация буферизации
	 */
	public static function outputBufferReStart()
	{
		$length = ob_get_length();
		if (!empty($length))
		{
			@ob_end_clean();
			self::outputBufferStart();
		}
	}

	/**
	 * Редирект
	 *
	 * @param string $url
	 */
	public static function redirect($url)
	{
		@ob_end_clean();
		
		@header('HTTP/1.1 301 Found');
		@header('Status: 301 Found');
		@header('Location: ' . $url);
		
		echo '<?xml version="1.0" encoding="utf-8"?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html><head><title>Страница автоматической переадресации</title><meta http-equiv="Refresh" content="0;URL=' . $url . '" /></head><body><a href="' . $url . '">Нажмине сюда, если ваш браузер не потдерживает автоматическую переадресацию.</a></body></html>';
		
		exit();
	}
}