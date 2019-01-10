<?php
class Transliterator 
{
	/**
	 * @param string $title
	 * @param string $standard
	 * @return string
	 */
	static protected function ConvertRussianLetters($title, $standard) 
	{
		$gost = array(
		   'Г'=>'G','Ё'=>'JO','Є'=>'EH','Ы'=>'Y','І'=>'I',
		   'і'=>'i','г'=>'g','ё'=>'jo','№'=>'#','є'=>'eh',
		   'ы'=>'y','А'=>'A','Б'=>'B','В'=>'V','Г'=>'G',
		   'Д'=>'D','Е'=>'E','Ж'=>'ZH','З'=>'Z','И'=>'I',
		   'Й'=>'JJ','К'=>'K','Л'=>'L','М'=>'M','Н'=>'N',
		   'О'=>'O','П'=>'P','Р'=>'R','С'=>'S','Т'=>'T',
		   'У'=>'U','Ф'=>'F','Х'=>'H','Ц'=>'C','Ч'=>'CH',
		   'Ш'=>'SH','Щ'=>'SHH','Ъ'=>'\'','Ы'=>'Y','Ь'=>'',
		   'Э'=>'EH','Ю'=>'JU','Я'=>'JA','а'=>'a','б'=>'b',
		   'в'=>'v','г'=>'g','д'=>'d','е'=>'e','ж'=>'zh',
		   'з'=>'z','и'=>'i','й'=>'jj','к'=>'k','л'=>'l',
		   'м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r',
		   'с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h',
		   'ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shh','ъ'=>'\'',
		   'ы'=>'y','ь'=>'','э'=>'eh','ю'=>'ju','я'=>'ja'
		  );
		  
		$original = array(
		   'Ґ'=>'G','Ё'=>'YO','Є'=>'E','Ї'=>'YI','І'=>'I',
		   'і'=>'i','ґ'=>'g','ё'=>'yo','№'=>'#','є'=>'e',
		   'ї'=>'yi','А'=>'A','Б'=>'B','В'=>'V','Г'=>'G',
		   'Д'=>'D','Е'=>'E','Ж'=>'ZH','З'=>'Z','И'=>'I',
		   'Й'=>'Y','К'=>'K','Л'=>'L','М'=>'M','Н'=>'N',
		   'О'=>'O','П'=>'P','Р'=>'R','С'=>'S','Т'=>'T',
		   'У'=>'U','Ф'=>'F','Х'=>'H','Ц'=>'TS','Ч'=>'CH',
		   'Ш'=>'SH','Щ'=>'SCH','Ъ'=>'\'','Ы'=>'YI','Ь'=>'',
		   'Э'=>'E','Ю'=>'YU','Я'=>'YA','а'=>'a','б'=>'b',
		   'в'=>'v','г'=>'g','д'=>'d','е'=>'e','ж'=>'zh',
		   'з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l',
		   'м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r',
		   'с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h',
		   'ц'=>'ts','ч'=>'ch','ш'=>'sh','щ'=>'sch','ъ'=>'\'',
		   'ы'=>'yi','ь'=>'','э'=>'e','ю'=>'yu','я'=>'ya'
		  );
		  
		switch ($standard) {
			case 'off':
			    return $title;
			case 'gost':
			    return strtr($title, $gost);
			default:
			    return strtr($title, $original);
		}
	}
	
	/**
	 * @param string $utf8_string
	 * @return string
	 */
	static protected function UTF8URIEncode( $utf8_string ) 
	{
		$unicode = '';        
		$values = array();
		$num_octets = 1;
		  
		for ($i = 0; $i < strlen( $utf8_string ); $i++ ) 
		{
			$value = ord( $utf8_string[ $i ] );
			if ( $value < 128 ) 
			{
				$unicode .= chr($value);
			}
			else 
			{
				if ( sizeof( $values ) == 0 ) $num_octets = ( $value < 224 ) ? 2 : 3;
				          
				$values[] = $value;
				if ( sizeof( $values ) == $num_octets ) 
				{
					if ($num_octets == 3) 
					{
						$unicode .= '%' . dechex($values[0]) . '%' . dechex($values[1]) . '%' . dechex($values[2]);
					}
					else 
					{
						$unicode .= '%' . dechex($values[0]) . '%' . dechex($values[1]);
					}
					$values = array();
					$num_octets = 1;
				}
			}
		}
		
		return $unicode;    
	}
		
	/**
	 * @param string $title
	 * @param string $standard
	 * @param bool $convert_for_system
	 * @return string
	 */
	static public function Transliterate($title, $standard = 'original', $convert_for_system = false, $convert_to_lower = true) 
	{
		$title = self::ConvertRussianLetters($title, $standard);
		
		$title = strip_tags($title);
		// Preserve escaped octets.
		$title = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $title);
		// Remove percent signs that are not part of an octet.
		$title = str_replace('%', '', $title);
		// Restore octets.
		$title = preg_replace('|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $title);
	
		if ($convert_to_lower and function_exists('mb_strtolower')) {
			$title = mb_strtolower($title, 'UTF-8');
		}
		
		$title = self::UTF8URIEncode($title);
	
		if($convert_to_lower)
		{
			$title = strtolower($title);
		}
		
		$title = preg_replace('/&.+?;/', '', $title); // kill entities
		$title = preg_replace('/[^%A-Za-z0-9 _-]/', '', $title);
		$title = preg_replace('/\s+/', '-', $title);
		$title = preg_replace('|-+|', '-', $title);
		$title = trim($title, '-');
		
		if ($convert_for_system)
		{
			$title = preg_replace('|-+|', '_', $title);
		}
		
		return $title;
	}	
}