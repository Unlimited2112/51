<?php
/**
 * Базовый класс контроллера ввода
 */
class Input
{
	/**
	 * Тип
	 *
	 * @var string
	 */
	protected $type = '';
	/**
	 * Имя
	 *
	 * @var string
	 */
	protected $name = '';
	/**
	 * Приорететы
	 *
	 * @var string
	 */
	protected $priority = '';
	/**
	 * Цикличность
	 *
	 * @var string
	 */
	protected $loop = '';
	/**
	 * Значение
	 *
	 * @var string
	 */
	protected $value = null;
	/**
	 * Входные данные
	 *
	 * @var array
	 */
	protected $inputVars = null;
	
	/**
	 * Конструктор
	 *
	 * @param string $name Имя
	 * @param string $priority Приорететы
	 * @param string $loop Цикличность
	 * @param string $type Тип
	 * @param array $inputVars Входные данные
	 */
	function __construct($name, $priority, $loop, $type, $inputVars)
	{
		$this->type = $type;
		$this->name = $name;
		$this->priority = $priority;
		$this->loop = $loop;
		$this->inputVars = $inputVars;
	}
	
	/**
	 * Обработка
	 */
	public function process()
	{
		$core = Core::getInstance();

		$this->getInputValue();
		
		if (isset($this->inputVars['index']) and ($this->inputVars['index'] >= 0))
		{
			$this->loop = intval($this->inputVars['index']);
		}

		if ( (isset($core->tplVars[$this->name.':hidden'])) && ($core->tplVars[$this->name.':hidden']) )
		{
			return '';
		}

		if ( (isset($core->tplVars[$this->name.':disabled'])) && ($core->tplVars[$this->name.':disabled']) )
		{
			$this->inputVars['disabled'] = 'disabled';
		}

		if (isset($core->tplVars[$this->name.':value']))
		{
			$this->inputVars['value'] = strtolower($core->tplVars[$this->name.':value']);
		}	
		
		if(!isset($this->inputVars['id']) and isset($this->inputVars['name']))
		{
			$this->inputVars['id'] = $this->inputVars['name'];
		}
		
		return $this->processInput();
	}
	
	/**
	 * Получение значения контроллера исходя из приоретета
	 */
	protected function getInputValue()
	{
		foreach ($this->priority as $v) 
		{
			$v = trim($v);
			if (strcasecmp($v, 'post')==0)
			{
				if (InPost($this->name, false) != false) 
				{
					$this->value = InPost($this->name);
					return;
				}
			}
			if (strcasecmp($v, 'get')==0)
			{
				if (InGet($this->name, false) != false) 
				{
					$this->value = InGet($this->name);
					return;
				}
			}
			if (strcasecmp($v, 'template')==0)
			{
				if (isset(Core::getInstance()->tplVars[$this->name])) 
				{
					$this->value = Core::getInstance()->tplVars[$this->name];			
					return;
				}
			}
		}
	}
	
	/**
	 * Обработка контроллера 
	 * 
	 * @return string
	 */
	protected function processInput()
	{
		return '';
	}
	
	/**
	 * Проверка парамметра на выводимость
	 *
	 * @param string $param
	 * @param array $disallowed
	 * @return bool
	 */
	protected function checkParamAllowed($param, $disallowed = array())
	{
		foreach ($disallowed as $value) 
		{
			if (strcasecmp($param, $value) == 0)
			{
				return false;
			}
		}
		return true;
	}
	
	/**
	 * Перевод массива параметров в строку
	 * 
	 * @param array $array
	 * @return string
	 */
	protected function arrayToString($array)
	{
		$string = '';
		foreach ($array as $key => $value)
		{
			$string .= ' ' . $key . '=\'' . htmlspecialchars($value) . '\'';
		}
		return $string;
	}
	
	/**
	 * Показать
	 */
	static public function show($name)
	{
		Core::getInstance()->tplVars[$name.':hidden'] = false;
	}

	/**
	 * Спрятать
	 */
	static public function hide($name)
	{
		Core::getInstance()->tplVars[$name.':hidden'] = true;
	}

	/**
	 * Разрешить
	 */
	static public function enable($name)
	{
		Core::getInstance()->tplVars[$name.':disabled'] = false;
	}

	/**
	 * Запретить
	 */
	static public function disable($name)
	{
		Core::getInstance()->tplVars[$name.':disabled'] = true;
	}
	
	/**
	 * Установка значения контроллера SELECT
	 * 
	 * @param string $name
	 * @param PDOStatement|array $arr Can be an array
	 * @param string $id
	 * @param string $text
	 * @param string $callBackFunction
	 */
	static public function setSelectData($name, $arr, $id='', $text='', $callBackFunction='') 
	{
		$core = Core::getInstance();

		$core->tplVars[$name.':select'] = array();
		$core->tplVars[$name.':escaped'] = false;

		if (is_array($arr))
		{
			$core->tplVars[$name.':escaped'] = ($id===true);
			foreach ($arr as $k => $v)
			{
				$core->tplVars[$name.':select'][$k] = $v;
			}
		}
		elseif ($arr instanceof PDOStatement)
		{
			$core->tplVars[$name.':escaped'] = false;
			foreach($arr as $row) {
				if ($callBackFunction=='')
				{
					$core->tplVars[$name.':select'][$row[$id]] = $row[$text];
				}
				else
				{
					$core->tplVars[$name.':select'][$row[$id]] = call_user_func($callBackFunction, $row[$text]);
				}
			}
		}
	}

	static public function getArrayForSelect(&$res, $key_field, $val_field) {
		$return = array();
		foreach($res as $row) {
			$return[$row[$key_field]] = $row[$val_field];
		}
		return $return;
	}
}