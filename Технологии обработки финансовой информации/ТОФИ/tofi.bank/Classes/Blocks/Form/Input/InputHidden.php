<?php
class InputHidden extends Input 
{
	function __construct($name, $priority, $loop, $type, $inputVars)
	{
		parent::__construct($name, $priority, $loop, $type, $inputVars);
	}
	
	protected function processInput()
	{
		$out = '<input autocomplete="off" type="hidden"';
		
		if (!isset($this->inputVars['value'])) $out .= ' value="'.htmlspecialchars($this->value).'"';

		foreach ($this->inputVars as $k => $v)
		{
			if ($this->checkParamAllowed($k, array("type", "priority")))
			{
				$out .= ' ' . $k . '="' . htmlspecialchars($v) . '"';
			}
		}
		$out .= ' />';
		
		return $out;
	}
}