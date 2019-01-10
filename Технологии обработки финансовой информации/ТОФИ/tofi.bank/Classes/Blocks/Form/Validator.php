<?php
/**
 * Класс для проверки значений по условиям
 */
class Validator {

	/**
	 * Список ошибок предыдущей валидации
	 *
	 * @var array
	 */
	public static $lastErrors = array(
	);

	/**
	 * Обрабатывает значение и проверяет переданное поле
	 *
	 * @param FormField $field Обьект поля
	 * @return bool
	 */
	static public function validateField($field) {
		self::$lastErrors = array(
		);
		
		$value = self::cleanValue($field->getFieldValue());
		
		if ($field->isRequired == false and !self::isRequired($value)) {
			$field->tplVars[$field->name] = $field->default;
			return true;
		}
		
		foreach ($field->allowedRules as $rule) {
			if (in_array($rule, $field->noJsRules)) {
				continue;
			}
			if ($rule == 'isRequired' and ($field->keyEnumeration != null or $field->enumeration != null)) {
				continue;
			}
			
			$ruleValue = $field->{$rule};
			
			if (($ruleValue != null) and ($ruleValue != false)) {
				if (is_array($ruleValue)) {
					if ($rule == 'keyEnumeration') {
						if (!self::keyEnumeration($value, $ruleValue)) {
							self::$lastErrors[] = $field->Localizer->getString('validator_enumeration', $field->displayName);
							return false;
						}
					} elseif ($rule == 'enumeration') {
						if (!self::enumeration($value, $ruleValue[0], $ruleValue[1])) {
							self::$lastErrors[] = $field->Localizer->getString('validator_enumeration', $field->displayName);
							return false;
						}
					} elseif ($rule == 'equalTo') {
						if (!self::equalTo($value, $field->getFieldValue($ruleValue[1]))) {
							self::$lastErrors[] = $field->Localizer->getString('validator_' . strtolower($rule), $field->displayName, $field->Localizer->getString($ruleValue[0]));
							return false;
						}
					} elseif ($rule == 'accept') {
						if (!self::accept($value, $ruleValue)) {
							self::$lastErrors[] = $field->Localizer->getString('validator_' . strtolower($rule), $field->displayName);
							return false;
						}
					} else {
						if (!self::$rule($value, $ruleValue[0], $ruleValue[1])) {
							self::$lastErrors[] = $field->Localizer->getString('validator_' . strtolower($rule), $field->displayName, $ruleValue[0], $ruleValue[1]);
							return false;
						}
					}
				} elseif (is_bool($ruleValue)) {
					if (!self::$rule($value)) {
						self::$lastErrors[] = $field->Localizer->getString('validator_' . strtolower($rule), $field->displayName);
						return false;
					}
				} else {
					if (!self::$rule($value, $ruleValue)) {
						self::$lastErrors[] = $field->Localizer->getString('validator_' . strtolower($rule), $field->displayName, $ruleValue);
						return false;
					}
				}
			}
		}
		
		$field->tplVars[$field->name] = $value;
		
		return true;
	}

	/**
	 * Обрабатывает значение и очищает от 'мусора'
	 *
	 * @param string $value Значение
	 * @return string
	 */
	static public function cleanValue($value) {
		if (is_array($value)) {
			foreach ($value as $k => $v) {
				$value[$k] = trim($value[$k]);
			}
		} else {
			$value = trim($value);
		}
		
		return $value;
	}

	/**
	 * Проверяет значение по паттерну
	 *
	 * @param string $pattern Паттерн
	 * @param string $value Значение
	 * @return bool
	 */
	static public function isPattern($pattern, $value) {
		if (!preg_match($pattern, $value)) {
			return false;
		}
		return true;
	}

	/**
	 * Проверяет значение на наличие
	 *
	 * @param string $value Значение
	 * @return bool
	 */
	static public function isRequired($value) {
		if ($value == "") {
			return false;
		}
		return true;
	}

	/**
	 * Проверяет значение на соответствие 'Number'
	 *
	 * @param string $value Значение
	 * @return bool
	 */
	static public function isNumber($value) {
		return self::isPattern('/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/', $value);
	}

	/**
	 * Проверяет значение на соответствие 'Email'
	 *
	 * @param string $value Значение
	 * @return bool
	 */
	static public function isEmail($value) {
		return self::isPattern('/^[\w-+\.]+@([\w-]+\.)+[\w-]{2,}$/i', $value);
	}

	/**
	 * Проверяет значение на соответствие 'ODBCDate'
	 *
	 * @param string $value Значение
	 * @return bool
	 */
	static public function isDate($value) {
		return self::isPattern('/^\d{4}[\/-]\d{1,2}[\/-]\d{1,2}$/', $value);
	}

	/**
	 * Проверяет значение на соответствие 'URL'
	 *
	 * @param string $value Значение
	 * @return bool
	 */
	static public function isSystem($value) {
		return self::isPattern('/^[\w-]+$/i', $value);
	}

	/**
	 * Проверяет значение на соответствие 'URL'
	 *
	 * @param string $value Значение
	 * @return bool
	 */
	static public function isUrl($value) {
		return self::isPattern('/^(https?|ftp):\/\/[A-Z0-9](\.?[A-Z0-9Ŝם[A-Z0-9_\-Ŝם*)*(\/([A-Z0-9Ŝם[A-Z0-9_\-\.Ŝם*)?)*(\?([A-Z0-9Ŝם[A-Z0-9_\-\.%\+=&Ŝם*)?)?$/i', $value);
	}

	/**
	 * Проверяет значение на минимальную длинну
	 *
	 * @param string $value Значение
	 * @param integer $length Минимальная длинна
	 * @return bool
	 */
	static public function minLength($value, $length) {
		if (mb_strlen($value, 'UTF-8') < $length) {
			return false;
		}
		return true;
	}

	/**
	 * Проверяет значение на максимальную длинну
	 *
	 * @param string $value Значение
	 * @param integer $length Максимальная длинна
	 * @return bool
	 */
	static public function maxLength($value, $length) {
		if (mb_strlen($value, 'UTF-8') > $length) {
			return false;
		}
		return true;
	}

	/**
	 * Проверяет значение на максимальную и минимальную длинну длинну
	 *
	 * @param string $value Значение
	 * @param integer $minLength Минимальная длинна
	 * @param integer $maxLength Максимальная длинна
	 * @return bool
	 */
	static public function rangeLength($value, $minLength, $maxLength) {
		if ((mb_strlen($value, 'UTF-8') > $maxLength) or (mb_strlen($value, 'UTF-8') < $minLength)) {
			return false;
		}
		return true;
	}

	/**
	 * Проверяет значение на минимальность
	 *
	 * @param string $value Значение
	 * @param integer $length Минимальное значение
	 * @return bool
	 */
	static public function minValue($value, $length) {
		if ($value < $length) {
			return false;
		}
		return true;
	}

	/**
	 * Проверяет значение на максимальность
	 *
	 * @param string $value Значение
	 * @param integer $length Максимальное значение
	 * @return bool
	 */
	static public function maxValue($value, $length) {
		if ($value > $length) {
			return false;
		}
		return true;
	}

	/**
	 * Проверяет значение на максимальность и минимальность
	 *
	 * @param string $value Значение
	 * @param integer $minValue Минимальное значение
	 * @param integer $maxValue Максимальное значение
	 * @return bool
	 */
	static public function rangeValue($value, $minValue, $maxValue) {
		if (($value > $maxValue) or ($value < $minValue)) {
			return false;
		}
		return true;
	}

	/**
	 * Проверяет значение на соответствие другому значению
	 *
	 * @param string $value Значение
	 * @param string $valueTo Значение для сравнения
	 * @return bool
	 */
	static public function equalTo($value, $valueTo) {
		if ($value != $valueTo) {
			return false;
		}
		return true;
	}

	/**
	 * Проверяет закаченый файл на соответствие формату
	 *
	 * @param string $fileName Значение
	 * @param array $acceptTypes Массив форматов
	 * @return bool
	 */
	static public function accept($fileValue, $acceptTypes) {
		if (!in_array($fileValue, $acceptTypes)) {
			return false;
		}
		return true;
	}

	/**
	 * Проверяет значение по PDOStatement
	 *
	 * @param string $value Значение
	 * @param array $iterator Возможные значения
	 * @param string $fieldname Поле для валидации
	 * @return bool
	 */
	static public function enumeration($value, $iterator, $fieldname) {
		foreach($iterator as $row) {
			$checkValues[] = $row[$fieldname];
		}
		$tmp = ((!is_array($value)) ? (array(
											$value
		)) : ($value));
		foreach ($tmp as $v) {
			if (!in_array($v, $checkValues)) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Проверяет значение по списку значений по ключам
	 *
	 * @param string $value Значение
	 * @param array $checkValues Возможные значения
	 * @return bool
	 */
	static public function keyEnumeration($value, $checkValues) {
		$tmp = ((!is_array($value)) ? (array(
											$value
		)) : ($value));
		foreach ($tmp as $v) {
			if (!isset($checkValues[$v])) {
				return false;
			}
		}
		return true;
	}
}