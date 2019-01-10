<?php
class UrlRequestParser
{
	/**
	 * Базовый путь в URL
	 *
	 * @var string
	 */
	public $rootPath = '';
	
	/**
	 * Запрашиваемый URL
	 *
	 * @var string
	 */
	public $requestUrl = '';
	
	/**
	 * Массив URL активных страниц
	 *
	 * @var array
	 */
	public $actionPages = array();
	
	/**
	 * Псевдо-URL страницы
	 *
	 * @var string
	 */
	public $pageUrl = '';
	
	/**
	 * Псевдо-URL страницы
	 *
	 * @var string
	 */
	public $pageUrlFull = '';
	
	/**
	 * ID языка сайта из запроса
	 *
	 * @var integer
	 */
	public $pageLang = 1;
	
	/**
	 * URL языка сайта из запроса
	 *
	 * @var string
	 */
	public $pageLangStr = '';
	
	/**
	 * URL языка сайта из запроса
	 *
	 * @var string
	 */
	public $pageLangDBStr = '';
	
	/**
	 * Параметры страницы
	 *
	 * @var array
	 */
	public $pageParams = array();
	
	/**
	 * Параметры страницы
	 *
	 * @var array
	 */
	public $pageParamsFull = array();
	
	/**
	 * Разделители параметров
	 *
	 * @var string
	 */
	public $urlParamsSeparators = 'data|p';
	
	function __construct() 
	{
		$this->requestUrl 	= $_SERVER['REQUEST_URI'];
	}
	
	/**
	 * Инициализация
	 */
	function getPageValues() 
	{
		$this->getRequestUrl();
		$this->parseLangs();
		$this->getPageUrl();
		$this->parseParrams();
		$this->cleanPageUrl();
	}
	
	/**
	 * Первичная обработка URL
	 */
	function getRequestUrl()
	{
		if($this->rootPath != '/')
		{
			$this->requestUrl = preg_replace( '/^'.addcslashes(preg_replace('#^/?(.*?)/?$#','/$1',$this->rootPath),'/').'/i', '', $this->requestUrl);
		}
		$this->requestUrl = preg_replace('#/{2,}#','/',$this->requestUrl);
	}
	
	/**
	 * Обработка URL на наличие локализации
	 */
	function parseLangs()
	{
		$res = Core::getInstance();
		$res = $res->DataBase->selectSql('wf_loc_lang');
		
		foreach($res as $row) {
			if($row['uri'] != '')
			{
				if((string)strpos($this->requestUrl, '/'.$row['uri'].'/') != '' )
				{
					$this->requestUrl = str_replace('/'.$row['uri'].'/', '/', $this->requestUrl);
					$this->pageLang = $row['id_lang'];
					$this->pageLangStr = $row['uri'].'/';
					$this->pageLangDBStr = '_'.$row['uri'];
					@setlocale(LC_ALL, $row['locale']);
				}
			}
		}
	}
	
	/**
	 * Получение псевдо-URL страницы
	 *
	 */
	function getPageUrl()
	{
		if ( $this->requestUrl == '/' )	
		{
			$this->pageUrl = '/index/';
			$this->pageUrlFull = '/index/';
		} 
		else 
		{
			if (strpos($this->requestUrl, '.html') !== false)
			{
				$ending = "";
			}
			else 
			{
				$ending = "/";
			}
			
			if ( $_SERVER['QUERY_STRING'] )	
			{
				$this->pageUrl = preg_replace (array('/^\//', '/\/?\?'.preg_quote($_SERVER['QUERY_STRING'], '/').'$/'), array('',''), $this->requestUrl ).$ending;
				$this->pageUrlFull = preg_replace (array('/^\//', '/\/?\?'.preg_quote($_SERVER['QUERY_STRING'], '/').'$/'), array('',''), $this->requestUrl ).$ending;
			} 
			else 
			{
				$this->pageUrl = preg_replace (array('/^\//', '/\/?\??$/'), array('',''), $this->requestUrl ).$ending;
				$this->pageUrlFull = preg_replace (array('/^\//', '/\/?\??$/'), array('',''), $this->requestUrl ).$ending;
			}
		}
	}
	
	/**
	 * Обработка URL на наличие передаваемых параметров
	 */
	function parseParrams()
	{
		if(strpos($this->pageUrl, '/') != 0)
		{
			$this->pageUrl = '/'.$this->pageUrl;
			$this->pageUrlFull = '/'.$this->pageUrlFull;
		}
		foreach ($this->actionPages as $action) 
		{
			if((strpos($this->pageUrl, $action) !== false) and (strpos($this->pageUrl, $action) === 0))
			{
				$this->pageParams = explode('/',str_replace($action, '',  str_replace('.css', '/', str_replace('.html', '/', $this->pageUrl))));
				foreach ($this->pageParams as $id => $parram) 
				{
					if(empty($parram) and $parram!=='0') unset($this->pageParams[$id]);
				}
				$this->pageUrl = $action;
			}
		}
		if ( preg_match('~^(.*?/)(('.$this->urlParamsSeparators.')/.*?)/?$~', $this->pageUrlFull, $arr) ) 
		{
			$this->pageUrlFull = $arr[1];
			$parrams = explode('/',$arr[2]);
			unset($parrams[0]);
			foreach ($parrams as $parram) 
			{
				$this->pageParamsFull[] = $parram;
			}
		}
	}
	
	/**
	 * 'Чистка' псевдо-URL страницы
	 *
	 */
	function cleanPageUrl()
	{
		if ($this->pageUrl != '/') {
			if(strpos($this->pageUrl, '/') != 0)  $this->pageUrl ='/'.$this->pageUrl;
			$this->pageUrl = preg_replace (array('/.php\/$/','/.htm\/$/'),array('.php','.htm'),$this->pageUrl);
		}
		
		if ($this->pageUrl == '/') 
		{
			$this->pageUrl = '/index/';
		}
	}
}