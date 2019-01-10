<?php
class InputFile extends Input 
{
	function __construct($name, $priority, $loop, $type, $inputVars)
	{
		parent::__construct($name, $priority, $loop, $type, $inputVars);
	}
	
	protected function processInput()
	{
		$out = '<table cellpadding="0" cellspacing="0"><tr><td><input type="text"';

		foreach ($this->inputVars as $k => $v)
		{
			if ($this->checkParamAllowed($k, array("type", "priority")))
			{
				$out .= ' ' . $k . '="' . htmlspecialchars($v) . '"';
			}
		}
		$out .= ' /></td><td>&nbsp;</td><td>';
		$out .= '<img src="'. htmlspecialchars(Config::$imagesPath. "core/buttonFiles.gif").'" id="' . $this->name . '_butt" name="' . $this->name . '_butt" class="hand" onclick="tinyBrowserPopUp(\'file\',\'' . $this->name . '\');
" /></td></tr></table>';
		
		return $out;
	}
}