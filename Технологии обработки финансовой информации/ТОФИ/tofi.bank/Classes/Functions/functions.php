<?php 

function strToBool($var){if (in_array(strtolower($var), array('true', 'yes', '1'))) return true;elseif (in_array(strtolower($var), array('false', 'no', '0'))) return false;else return (bool) $var;}

function code2utf($num)
{
	if ($num<128) return chr($num);
	if ($num<2048) return chr(($num>>6)+192).chr(($num&63)+128);
	if ($num<65536) return chr(($num>>12)+224).chr((($num>>6)&63)+128).chr(($num&63)+128);
	if ($num<2097152) return chr(($num>>18)+240).chr((($num>>12)&63)+128).chr((($num>>6)&63)+128).chr(($num&63)+128);
	return '';
}

function utf16parse($t)
{
	$t = preg_replace('/\&\#([0-9]+)\;/me', "((\\1>255)?(utf8_decode(code2utf(\\1))):('&#\\1;'))", $t);
	return $t;
}

require_once(((get_magic_quotes_gpc())?('_in.quote.php'):('_in.php')));

function SetCacheVar($VarName, $Value, $CachId = 'common') 
{
	if (!strlen($CachId)) return;
	if (is_array($Value)) $_SESSION['cache'][$CachId] = array_merge($_SESSION['cache'][$CachId], $Value);
	else $_SESSION['cache'][$CachId][$VarName] = $Value;
}

function arr_val($arr, $key_val, $def_val = '') {
	if (is_array($arr) && isset($arr[$key_val])) return $arr[$key_val];
	else return $def_val;
}

function cut_zeros($float) {
    return $float + 0;
}

function dateconvert($date, $dateFormat = DATE_FORMAT)
{
	if($date == "0000-00-00")
	{
		return "";
	}
	elseif(empty($date))
	{
		return "";
	}
	else 
	{
		return timeconvert(strtotime($date), $dateFormat);
	}
}

function dateconvertfront($date, $dateFormat = DATE_FORMAT_FRONT)
{
	if($date == "0000-00-00")
	{
		return "";
	}
	elseif(empty($date))
	{
		return "";
	}
	else 
	{
		return timeconvert(strtotime($date), $dateFormat);
	}
}

function datetimeconvert($date, $dateFormat = DATETIME_FORMAT)
{
	if($date == "0000-00-00 00:00:00")
	{
		return "";
	}
	elseif(empty($date))
	{
		return "";
	}
	else 
	{
		return timeconvert(strtotime($date), $dateFormat);
	}
}

function timeconvert($time, $dateFormat = DATETIME_FORMAT)
{
	return strftime($dateFormat, $time);
}

function sizeconvert($size)
{
	return number_format($size / 1024, 2);
}

function rn2br($string)
{
	$string = str_replace("\n","",$string);
	$string = str_replace("\r","<br />",$string);
	return $string;
}

function IsResponse($res)
{
	throw new Exception();
	if(is_object($res))
	{
		if(!$res->eof())
		{
			return true;
		}
		else return false;
	}
	else return false;
}

function get_block($name, $params=array(), $id=null) {
	
	$name = strtolower($name);
	
    $block = BlocksRegistry::getInstance()->getBlock($name, $id);
    if (is_object($block))
    {
		$block->initialize($params);
        return $block->process();
    }
    else
    {
        if (Config::$debugLevel)
            return 'Can not find block "'.$name.'".';
        else
            return '';
    }
}

function get_setting($name) {
	return Core::getInstance()->Settings->getSetting($name);
}

function get_text($name, $default=null) {
	if($default === null) {
		$default = $name;
	}
	return Core::getInstance()->Localizer->getStringValue($name, $default);
}

function json_response($var) {
	die($_REQUEST['callback'] . '(' . json_encode($var) . ')');
}

function mysql_format_price($price) {
	return "replace(rtrim(replace(ltrim(replace(rtrim(replace(ltrim(IFNULL(".$price.", 0)),'0',' ')),' ','0')),'.',' ')),' ','.')";
}