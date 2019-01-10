<?php
class JS 
{
	/**
	 * Обрабатывает строку для использования в JS коде
	 *
	 * @param string $str
	 * @return string
	 */
	static public function escape($str)
	{
		$str = str_replace("\\", "\\\\", strval($str));
		$str = str_replace("'", "\\'", $str);
		$str = str_replace("\r", "\\r", $str);
		$str = str_replace("\n", "\\n", $str);
		$str = str_replace("\n", "\\n", $str);
		$str = str_replace("</script>", "</'+'script>", $str);
		return $str;
	}
	
	/**
	 * Преобразует массив в JSON обьект
	 *
	 * @param array $array
	 * @return string
	 */
	static public function array2json($array) 
	{ 
		$o='';
		$add = '';
		foreach ($array as $k => $v) 
		{ 
			$o .= $add.$k.':';
			if(is_array($v))
			{
				$next_level = false;
				foreach ($v as $key => $value) 
				{
					if(is_array($value))
					{
						$next_level = true;
					}
				}
				if($next_level)
				{
					$o .= self::array2json($v);
				}
				else 
				{
					$o .= '['.self::escape(implode(',', $v)).']';
				}
			}
			elseif(is_int($v))
			{
				$o .= self::escape($v);
			}
			elseif(is_bool($v))
			{
				if($v)
				{
					$o .= 'true';
				}
				else 
				{
					$o .= 'false';
				}
			}
			else
			{
				$o .= '\''.self::escape($v).'\'';
			}
			$add = ','.PHP_EOL;
		}
		
		return '{'.PHP_EOL.$o.PHP_EOL.'}';
	}
}