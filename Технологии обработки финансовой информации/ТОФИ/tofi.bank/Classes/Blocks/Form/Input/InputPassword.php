<?php
class InputPassword extends Input 
{
	function __construct($name, $priority, $loop, $type, $inputVars)
	{
		parent::__construct($name, $priority, $loop, $type, $inputVars);
	}
	
	protected function processInput()
	{
		$out = '<input autocomplete="off" type="password" value=""';

		foreach ($this->inputVars as $k => $v)
		{
			if ($this->checkParamAllowed($k, array("type", "value", "priority")))
			{
				$out .= ' ' . $k . '="' . htmlspecialchars($v) . '"';
			}
		}
		$out .= ' />';
		
		return $out;
	}
}