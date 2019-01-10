<?php
function _stripslashes($v)
{
	if (!is_array($v)) return stripslashes($v);
	else foreach ($v as $k => $tv) $v[$k] = stripslashes($v[$k]);
	return $v;
}
function InPost($VarName, $DefValue = ''){
return ((isset($_POST[$VarName]))?utf16parse(_stripslashes($_POST[$VarName])):($DefValue));
}
function InGet($VarName, $DefValue = ''){
return ((isset($_GET[$VarName]))?utf16parse(_stripslashes($_GET[$VarName])):($DefValue));
}
function InCookie($VarName, $DefValue = ''){
return ((isset($_COOKIE[$VarName]))?(_stripslashes($_COOKIE[$VarName])):($DefValue));
}
function InCache($VarName, $DefValue = '', $CacheId = 'common'){
return ((isset($_SESSION['cache'][$CacheId][$VarName]))?(_stripslashes($_SESSION['cache'][$CacheId][$VarName])):($DefValue));
}
function InGetPost($VarName, $DefValue = ''){
return ((isset($_GET[$VarName]))?utf16parse(_stripslashes($_GET[$VarName])):((isset($_POST[$VarName]))?utf16parse(_stripslashes($_POST[$VarName])):($DefValue)));
}
function InPostGet($VarName, $DefValue = ''){
return ((isset($_POST[$VarName]))?utf16parse(_stripslashes($_POST[$VarName])):((isset($_GET[$VarName]))?utf16parse(_stripslashes($_GET[$VarName])):($DefValue)));
}
function InGetPostCache($VarName, $DefValue = '', $CacheId = 'common'){
return ((isset($_GET[$VarName]))?utf16parse(_stripslashes($_GET[$VarName])):((isset($_POST[$VarName]))?utf16parse(_stripslashes($_POST[$VarName])):((isset($_SESSION['cache'][$CacheId][$VarName]))?(_stripslashes($_SESSION['cache'][$CacheId][$VarName])):($DefValue))));
}
function InPostGetCache($VarName, $DefValue = '', $CacheId = 'common'){
return ((isset($_POST[$VarName]))?utf16parse(_stripslashes($_POST[$VarName])):((isset($_GET[$VarName]))?utf16parse(_stripslashes($_GET[$VarName])):((isset($_SESSION['cache'][$CacheId][$VarName]))?(_stripslashes($_SESSION['cache'][$CacheId][$VarName])):($DefValue))));
}