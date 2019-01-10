<?php
class TextFormater 
{
	/**
	 * Возвращает словоформу в зависимости от числа.
	 *
	 * @param integer $int
	 * @param array $expressions
	 * @return string
	 */
	function declension($int, $expressions) 
	{ 
		$result = null;
		
		if (sizeof($expressions) < 3)
		{
			$expressions[2] = $expressions[1];
		}
		
		settype($int, "integer"); 
		$count = $int % 100; 
		
		if ($int == 0 && isset($expressions[3])) 
		{ 
			$result = $expressions[3]; 
		} 
		elseif ($count >= 5 && $count <= 20) 
		{ 
			$result = $expressions[2]; 
		} 
		else 
		{ 
			$count = $count % 10; 
			
			if ($count == 1) 
			{ 
			    $result = $expressions[0]; 
			} 
			elseif ($count >= 2 && $count <= 4) 
			{ 
			    $result = $expressions[1]; 
			} 
			else 
			{ 
			    $result = $expressions[2]; 
			}
		} 
		return $result; 
	}
	
	/**
	 * Генерирует "Lorem ipsum" абзацы
	 *
	 * @param integer $num Количество абзацев
	 * @param string $start Строка начала абзаца
	 * @param string $end Строка конца абзаца
	 * @return string
	 */
	static public function lipsum($num = 1, $start = '<p>', $end = '</p>')
	{
		return str_repeat($start."Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nam pulvinar varius odio. Sed eu elit at wisi porta fermentum. Nulla sit amet purus. Vestibulum sed magna. Suspendisse potenti. Quisque sed metus. Praesent molestie ipsum nec ligula. Phasellus porta scelerisque massa. Donec gravida pulvinar erat. Aliquam ornare, eros vitae mattis suscipit, nisl quam suscipit orci, sed malesuada lorem nunc at ligula.\n".$end, $num);
	}
	
	/**
	 * SubStr по словам
	 *
	 * @param string $text Текст
	 * @param integer $word_num Количество слов
	 * @return string
	 */
	static public function words($text, $word_num)
	{                       
	    if (strlen($text)<1) return '';
	    $arr = str_word_count($text, 2);
	    $i = 1;
	    if (sizeof($arr)>$word_num) 
		    foreach ($arr as $k => $v){
		        if ($i == $word_num) return substr($text, 0, $k);
		        $i++;    
		    }
	    return $text;
	}
}