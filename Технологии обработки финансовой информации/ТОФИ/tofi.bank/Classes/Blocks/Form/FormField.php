<?php
/**
 * Класс элемента формы, описывающий валидацию этого эллемента
 */
class FormField 
{
	const TYPE_STRING = 'string';
	const TYPE_TEXT = 'text';
	const TYPE_HTML = 'html';
	const TYPE_DATE = 'date';
	const TYPE_BOOL = 'bool';
	const TYPE_FILE = 'file';
	const TYPE_NUMBER = 'number';
	const TYPE_EMAIL = 'email';
	const TYPE_URL = 'url';
	const TYPE_SYSTEM = 'system';
	
	/**
	 * Локализатор
	 *
	 * @var Localizer
	 */
	public $Localizer;
	
	/**
	 * Глобальный массив переменных;
	 *
	 * @var array
	 */
	public $tplVars;
	
	/**
	 * Имя поля
	 *
	 * @var string
	 */
	public $name;
	
	/**
	 * Название поля
	 *
	 * @var string
	 */
	public $displayName;
	
	/**
	 * Тип поля
	 *
	 * @var string
	 */
	public $type;
	
	/**
	 * Значение по умолчанию
	 *
	 * @var string
	 */
	public $default = '';
	
	/**
	 * Опции поля
	 *
	 * @var string
	 */
	public $option = null;
	
	/**
	 * Поле обязательное к заполнению
	 *
	 * @var bool
	 */
	public $isRequired = false;
	
	/**
	 * Поле содержит числовые данные
	 *
	 * @var bool
	 */
	public $isNumber = false;
	
	/**
	 * Поле содержит Email
	 *
	 * @var bool
	 */
	public $isEmail = false;
	
	/**
	 * Поле содержит дату
	 *
	 * @var bool
	 */
	public $isDate = false;
	
	/**
	 * Поле содержит System
	 *
	 * @var bool
	 */
	public $isSystem = false;
	
	/**
	 * Поле содержит Url
	 *
	 * @var bool
	 */
	public $isUrl = false;
	
	/**
	 * Минимальная длинна поля
	 *
	 * @var integer
	 */
	public $minLength = null;
	
	/**
	 * Максимальная длинна поля
	 *
	 * @var integer
	 */
	public $maxLength = null;
	
	/**
	 * Минимальная и максимальная длинна поля
	 *
	 * @var array
	 */
	public $rangeLength = null;
	
	/**
	 * Минимальное значение поля
	 *
	 * @var integer
	 */
	public $minValue = null;
	
	/**
	 * Максимальное значение поля
	 *
	 * @var integer
	 */
	public $maxValue = null;
	
	/**
	 * Минимальное и максимальное значение поля
	 *
	 * @var array
	 */
	public $rangeValue = null;
	
	/**
	 * Должно быть равно другому полю, например array(fieldID, fieldName)
	 *
	 * @var string
	 */
	public $equalTo = null;
	
	/**
	 * Разрешённые типы файлов по расширению, например array('bmp', 'gif', 'p?jpe?g', 'png', 'tiff')
	 *
	 * @var array
	 */
	public $accept = null;
	
	/**
	 * Разрешённые типы файлов по Mime, например array('image/x-xbitmap', 'image/bmp', 'image/gif', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/x-png', 'image/tiff', 'image/x-icon')
	 *
	 * @var array
	 */
	public $acceptMime = null;
	
	/**
	 * Данные для валидации по PDOStatement, например array(PDOStatement, fieldName, fieldForSelect)
	 *
	 * @var array
	 */
	public $enumeration = null;
	
	/**
	 * Данные для валидации по списку значений по ключам
	 *
	 * @var array
	 */
	public $keyEnumeration = null;
	
	/**
	 * Разрешённые типы валидации поля
	 *
	 * @var array
	 */
	public $allowedRules = array('default', 'option', 'isRequired', 'minLength', 'maxLength', 'rangeLength', 'minValue', 'maxValue', 'rangeValue', 'isNumber', 'isEmail', 'isDate', 'isUrl', 'isSystem', 'equalTo', 'accept', 'acceptMime', 'enumeration', 'keyEnumeration');
	
	/**
	 * Не JS типы валидации поля
	 *
	 * @var array
	 */
	public $noJsRules = array('default', 'option', 'acceptMime', 'enumeration', 'keyEnumeration');
	
	/**
	 * Название правил в JS
	 *
	 * @var array
	 */
	protected $jsRules = array('isRequired' => 'required', 'minLength' => 'minlength', 'maxLength' => 'maxlength', 'rangeLength' => 'rangelength', 'minValue' => 'min', 'maxValue' => 'max', 'rangeValue' => 'range', 'isNumber' => 'number', 'isEmail' => 'email', 'isDate' => 'dateISO', 'isUrl' => 'url', 'isSystem' => 'isSystem', 'equalTo' => 'equalTo', 'accept' => 'accept');
	
	/**
	 * Конструктор класса
	 *
	 * @param string $fieldName
	 * @param string $fieldParams
	 */
	function __construct($fieldName, $fieldParams = array(), $type = 'text')
	{
		$this->Localizer = Core::getInstance()->Localizer;
		$this->tplVars = &Core::getInstance()->tplVars;
		
		$this->name = $fieldName;
		$this->type = $type;
		$this->displayName = $this->Localizer->getString($fieldName);
		
		foreach ($fieldParams as $param => $value) 
		{
			if(in_array($param, $this->allowedRules))
			{
				$this->{$param} = $value;
			}
		}
	}
	
	/**
	 * Возвращает правила для JS валидации
	 *
	 * @return string
	 */
	public function getFieldRules()
	{
		$rules = '';
		$add = PHP_EOL;
		foreach ($this->allowedRules as $rule) 
		{
			if(in_array($rule,$this->noJsRules))
			{
				continue;
			}
			
			$ruleValue = $this->{$rule};
			if(($ruleValue != null) and ($ruleValue != false))
			{
				$rules .= $add . $this->jsRules[$rule] . ': ';
				if(is_array($ruleValue))
				{
					if ($rule == 'accept')
					{
						$rules .= '\'' . implode('|', $ruleValue) . '\'';
					}
					elseif ($rule == 'equalTo')
					{
						$rules .= '\'#' . $ruleValue[0] . '\'';
					}
					else 
					{
						$rules .= '['.implode(', ', $ruleValue).']';
					}
				}
				elseif (is_bool($ruleValue))
				{
					$rules .= 'true';
				}
				elseif (is_numeric($ruleValue)) 
				{
					$rules .= $ruleValue;
				}
				else 
				{
					$rules .= '\'' . $ruleValue . '\'';
				}
				$add = ','.PHP_EOL;
			}
		}
		if(!empty($rules))
		{
			$rules = $this->name . ': {' . $rules . PHP_EOL .'}';
		}
		
		return $rules;
	}
	
	/**
	 * Возвращает сообщения для JS валидации
	 *
	 * @return string
	 */
	public function getFieldMessages()
	{
		Loader::loadLib('JS', 'Reformers');
		
		$messages = '';
		$add = PHP_EOL;
		foreach ($this->allowedRules as $rule) 
		{
			if(in_array($rule,$this->noJsRules))
			{
				continue;
			}
			
			$ruleValue = $this->{$rule};
			if(($ruleValue != null) and ($ruleValue != false))
			{
				$messages .= $add . $this->jsRules[$rule] . ': \'';
				if(is_array($ruleValue))
				{
					if ($rule == 'accept')
					{
						$messages .= JS::escape($this->Localizer->getString('validator_'.strtolower($rule), $this->displayName, implode('|', $ruleValue)));
					}
					elseif ($rule == 'equalTo')
					{
						$messages .= JS::escape($this->Localizer->getString('validator_'.strtolower($rule), $this->displayName, $this->Localizer->getString($ruleValue[0])));
					}
					else 
					{
						$messages .= JS::escape($this->Localizer->getString('validator_'.strtolower($rule), $this->displayName, $ruleValue[0], $ruleValue[1]));
					}
				}
				elseif (is_bool($ruleValue))
				{
					$messages .= JS::escape($this->Localizer->getString('validator_'.strtolower($rule), $this->displayName));
				}
				else 
				{
						$messages .= JS::escape($this->Localizer->getString('validator_'.strtolower($rule), $this->displayName, $ruleValue));
				}
				$add = '\','.PHP_EOL;
			}
		}
		if(!empty($messages))
		{
			$messages = $this->name . ': {' . $messages . '\''.PHP_EOL.'}';
		}
		
		return $messages;
	}
	
	/**
	 * Возвращает значение поля из входных параметров
	 *
	 * @param string $name имя поля
	 * @return string
	 */
	public function getFieldValue($name = null)
	{
		if($name == null)
		{
			$name = $this->name;
		}
		if(isset(Core::getInstance()->tplVars[$name]))
		{
			$value = Core::getInstance()->tplVars[$name];
		}
		else 
		{
			$value = InPostGet($name, false);
			if($value === false)
			{
				$value = null;
			}
		}
		
		return $value;
	}
}

class FormFieldRequired extends FormField 
{
	/**
	 * Конструктор класса
	 *
	 * @param string $fieldName
	 * @param string $fieldParams
	 */
	function __construct($fieldName, $fieldParams = array(), $type = 'text')
	{
		parent::__construct($fieldName, $fieldParams, $type);
		$this->isRequired = true;
	}
}