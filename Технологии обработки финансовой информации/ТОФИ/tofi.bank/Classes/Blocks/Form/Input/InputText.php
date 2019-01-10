<?php
class InputText extends Input 
{
	function __construct($name, $priority, $loop, $type, $inputVars)
	{
		parent::__construct($name, $priority, $loop, $type, $inputVars);
	}
	
	protected function processInput()
	{
		if (is_array($this->value)) $this->value = 'Array';

		$out = '<input autocomplete="off" type="text"';

		if (!isset($this->inputVars['value'])) $out .= ' value="'.htmlspecialchars($this->value).'"';

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