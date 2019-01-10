<?php
class InputButton extends Input 
{
	function __construct($name, $priority, $loop, $type, $inputVars)
	{
		parent::__construct($name, $priority, $loop, $type, $inputVars);
	}
	
	protected function processInput()
	{
		$out = '<input autocomplete="off" type="button"';

		foreach ($this->inputVars as $k => $v)
		{
			if ($this->checkParamAllowed($k, array("type", "validation", "priority", "confirm")))
			{
				$out .= ' ' . $k . '="' . htmlspecialchars($v) . '"';
			}
		}

		if (!isset($this->inputVars['onclick']))
		{
			$out .= ' onclick="';
	
			if (isset($this->inputVars['confirm']))
			{
				$out .= 'if (confirm(\''.htmlspecialchars($this->inputVars['confirm']).'\')) ';
			}
	
			$out .= 'send_'.Form::$currentRunningForm.'_form(\'' . 
						(isset($this->inputVars['name'])?$this->inputVars['name']:'') . 
					'\', true);"';
		}

		$out .= ' />';
		
		return $out;
	}
}