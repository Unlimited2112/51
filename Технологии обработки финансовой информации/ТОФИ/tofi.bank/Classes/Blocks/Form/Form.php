<?php
Loader::loadBlock('FormField', 'Form');

class Form 
{
	/**
	 * Список доступных форм
	 * 
	 * @var array
	 */
	protected static $forms = array();
	
	/**
	 * Список ошибок валидации форм
	 * 
	 * @var array
	 */
	protected static $formErrors = array();
	
	/**
	 * Текущая обрабатываемая форма
	 * 
	 * @var string
	 */
	public static $currentRunningForm = '';
	
	/**
	 * Добавляет форму и поля в неё
	 *
	 * @param string $formName Имя формы
	 * @param array $formFields Асациативный массив полей
	 */
	public static function addForm($formName, $formFields = array())
	{
		self::$forms[$formName] = $formFields;
		self::$formErrors[$formName] = array();
	}
	
	/**
	 * Добавляет поле в форму
	 *
	 * @param string $formName Имя формы
	 * @param FormField $formField Поле
	 */
	public static function addField($formName, FormField $formField)
	{
		if(isset(self::$forms[$formName]))
		{
			self::$forms[$formName][$formField->name] = $formField;
		}
		else 
		{
			throw new Exception('Форма "'.$formName.'" не существует.');
		}
	}
	
	/**
	 * Добавляет поле в форму
	 *
	 * @param string $formName Имя формы
	 * @param FormField $formField Поле
	 */
	public static function addFieldInPosition($formName, FormField $formField, $position = 1)
	{
		if(isset(self::$forms[$formName]))
		{
			$new_form = array();
			$i=1;
			foreach (self::$forms[$formName] as $key => $value) 
			{
				if($i == $position)
				{
					$new_form[$formField->name] = $formField;
				}
				$new_form[$key] = $value;
				$i++;
			}
			
			self::$forms[$formName] = $new_form;
		}
		else 
		{
			throw new Exception('Форма "'.$formName.'" не существует.');
		}
	}
	
	/**
	 * Возвращает поля формы
	 *
	 * @param string $formName Имя формы
	 * @return array
	 */
	public static function getForm($formName)
	{
		if(isset(self::$forms[$formName]))
		{
			return self::$forms[$formName];
		}
		else 
		{
			throw new Exception('Форма "'.$formName.'" не существует.');
		}
	}
	
	/**
	 * Возвращает JS код валидатора формы
	 *
	 * @param string $formName Имя формы
	 * @return string
	 */
	public static function getFormJSValidator($formName)
	{
		$validator = '';
		
		if(isset(self::$forms[$formName]))
		{
			$validator .= '$(\'#'.$formName.'\').ready('.PHP_EOL
					.'function()'.PHP_EOL
					.'{'.PHP_EOL
					.'$(\'#'.$formName.'\').validate('.PHP_EOL.
					'{'.PHP_EOL;
			
			$rules = 'rules: {'.PHP_EOL;
			$messages = 'messages: {'.PHP_EOL;
			$add = PHP_EOL;
			foreach (self::$forms[$formName] as $fieldName => $field) 
			{
				$temp = $field->getFieldRules();
				if(!empty($temp))
				{
					$rules .= $add.$field->getFieldRules();				
					$messages .= $add.$field->getFieldMessages();		
					$add = ','.PHP_EOL;
				}
			}
			
			$rules .= PHP_EOL.'},';
			$messages .= PHP_EOL.'},'.PHP_EOL;
			
			$validator .=  $rules;
			$validator .=  $messages;
			$validator .=  'errorLabelContainer: $(\'#'.$formName.' div.error\')';
			$validator .= PHP_EOL.'}'.PHP_EOL
					.');'.PHP_EOL
					.'}'.PHP_EOL
					.');';
			
			return $validator;
		}
		else 
		{
			$validator = '';
		}
	}
	
	/**
	 * Возвращает JS код для сохранения визивигов
	 *
	 * @param string $formName Имя формы
	 * @return string
	 */
	public static function getFormJSEditorsSave($formName)
	{
		$validator = '';
		
		if(isset(self::$forms[$formName]))
		{
			
			foreach (self::$forms[$formName] as $fieldName => $field) 
			{
				if($field->type == 'html')
				{
					$validator .= PHP_EOL.'CKEDITOR.instances.'.$field->name.'.updateElement();';
				}
			}
			
			if(!empty($validator))
			{
				$validator = 'if(typeof CKEDITOR != \'undefined\') {'.$validator.PHP_EOL.'}';
			}
		}

		return $validator;
	}
	
	/**
	 * Валидация формы
	 *
	 * @param string $formName Имя формы
	 * @return bool
	 */
	public static function validate($formName)
	{
		if(isset(self::$forms[$formName]))
		{
			self::$formErrors[$formName] = array();
			
			foreach (self::$forms[$formName] as $field) 
			{
				if(!Validator::validateField($field))
				{
					self::$formErrors[$formName] = array_merge(self::$formErrors[$formName], Validator::$lastErrors);
				}
			}
			
			return empty(self::$formErrors[$formName]);
		}
		else 
		{
			throw new Exception('Форма "'.$formName.'" не существует.');
		}
	}
	
	/**
	 * Возвращает ошибки валидации формы
	 *
	 * @param string $formName Имя формы
	 * @return array
	 */
	public static function getFormErrors($formName)
	{
		return self::$formErrors[$formName];
	}
	
	/**
	 * Проверка на отправку формы
	 *
	 * @param string $name Имя формы
	 * @param string $action Кнопка
	 * @param bool $check_action Проверять кнопку
	 * @return bool
	 */
	public static function isSubmited($name, $action = null, $check_action = true) 
	{
		$r = (strcasecmp(InPostGet('send_form_name'), $name)==0);
		if ($check_action)
		{
			if (is_null($action))
			{
				return ( $r && (0==strlen(InPostGet('form_action'))) );
			}
			else
			{
				return ( $r && (strcasecmp(InPostGet('form_action'), $action)==0) );
			}
		}
		else
		{
			return $r;
		}
	}
}