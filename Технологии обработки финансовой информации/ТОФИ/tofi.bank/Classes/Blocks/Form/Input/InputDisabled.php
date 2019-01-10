<?php
class InputDisabled extends Input 
{
	function __construct($name, $priority, $loop, $type, $inputVars)
	{
		parent::__construct($name, $priority, $loop, $type, $inputVars);
	}
	
	protected function processInput()
	{
		$out = '<input autocomplete="off" type="hidden" name="'.htmlspecialchars($this->name).'" value="'.htmlspecialchars($this->value).'" /><span';
		foreach ($this->inputVars as $k => $v)
		{
			if ($this->checkParamAllowed($k, array("type", "name", "value", "priority")))
			{
				$out .= ' ' . $k . '="' . htmlspecialchars($v) . '"';
			}
		}
		
		$out .= '>';
		$out .= htmlspecialchars($this->value);
		$out .= '</span>';
		
		return $out;
	}
}