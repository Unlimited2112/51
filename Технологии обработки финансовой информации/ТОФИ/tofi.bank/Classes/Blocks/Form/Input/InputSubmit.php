<?php
class InputSubmit extends Input 
{
	function __construct($name, $priority, $loop, $type, $inputVars)
	{
		parent::__construct($name, $priority, $loop, $type, $inputVars);
	}
	
	protected function processInput()
	{
		$out = '<input type="submit"';

		foreach ($this->inputVars as $k => $v)
		{
			if ($this->checkParamAllowed($k, array("type", "validation", "priority")))
			{
				$out .= ' ' . $k . '="' . htmlspecialchars($v) . '"';
			}
		}

		$out .= ' onclick="';

		if (isset($this->inputVars['confirm']))
		{
			$out .= 'if (confirm(\''.htmlspecialchars($this->inputVars['confirm']).'\')) ';
		}

		$out .= 'send_'.Form::$currentRunningForm.'_form(\'\', true);"';

		$out .= ' />';
		
		return $out;
	}
}