<?php
class InputCheckbox extends Input 
{
	function __construct($name, $priority, $loop, $type, $inputVars)
	{
		parent::__construct($name, $priority, $loop, $type, $inputVars);
	}
	
	protected function processInput()
	{
		$out = '<input autocomplete="off" type="checkbox"';

		foreach ($this->inputVars as $k => $v)
		{
			if ($this->checkParamAllowed($k, array("type", "priority")))
			{
				$out .= ' ' . $k . '="' . htmlspecialchars($v) . '"';
			}
		}
		if (isset($this->inputVars['value']))
		{
			if ($this->value == $this->inputVars['value'])
			{
				$out .= ' checked="checked"';
			}
		}
		else
		{
			$out .= ' value="1"';
		}
					
		$out .= ' />';
		
		return $out;
	}
}