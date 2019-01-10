<?php
class UUrl 
{
	/**
	 * Переменные шаблонов страницы.
	 *
	 * @var array
	 */
	static public $tplVars = array();
	
	/**
	 * Параметры страницы.
	 *
	 * @var array
	 */
	static public $pageParams = array();
	
	/**
	 * Ядро системы
	 *
	 * @var Core
	 */
	static public $Core;
	
	/**
	 * Получения параметра страницы.
	 *
	 * @param integer $paramNum Номер параметра
	 * @param string $default Значение по-умолчанию
	 * @return string
	 */
	static public function getParram($paramNum, $default = null)
	{
		return arr_val(self::$pageParams, $paramNum, $default);
	}
	
	/**
	 * Формирование текущего URL с определённым колличеством сохрынённых параметров
	 *
	 * @param integer $saveParams Количество сохраняемых параметров (-1 - все)
	 * @return string
	 */
	public static function getCuttedURL($saveParams = -1)
	{
		if ($saveParams != -1)
		{
			$params = array_slice(self::$pageParams, 0, $saveParams);
		}
		else 
		{
			$params = self::$pageParams;
		}
		
		$params = implode('/', $params);
		if(!empty($params))
		{
			$params .= '/';
		}
		
		return  self::$tplVars['HTTPl'] . self::$tplVars['current_page_url'] . $params;
	}
	
	static public function initialize()
	{
		self::$Core = Core::getInstance();
		self::$tplVars = &self::$Core->tplVars;
		self::$pageParams = self::$Core->currentView->page_params;
	}
	
}