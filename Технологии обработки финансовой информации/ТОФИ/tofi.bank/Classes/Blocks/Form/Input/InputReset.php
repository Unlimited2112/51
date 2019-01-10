<?php
class InputReset extends Input 
{
	function __construct($name, $priority, $loop, $type, $inputVars)
	{
		parent::__construct($name, $priority, $loop, $type, $inputVars);
	}
	
	protected function processInput()
	{
		if (isset($this->inputVars['src']))
		{
			$out = '<a href="javascript:document.forms.'.Form::$currentRunningForm.'.reset();"><img';
			foreach ($this->inputVars as $k => $v)
			{
				if ($this->checkParamAllowed($k, array("type", "priority")))
				{
					$out .= ' ' . $k . '="' . htmlspecialchars($v) . '"';
				}
			}
			
			if (!isset($this->inputVars['alt']))
			{
				$out .= ' alt=""';
			}
			$out .= ' border="0" /></a>';
		}
		else
		{
			$out = '<input type="reset"';
			foreach ($this->inputVars as $k => $v)
			{
				if ($this->checkParamAllowed($k, array("type", "priority")))
				{
					$out .= ' ' . $k . '="' . htmlspecialchars($v) . '"';
				}
			}
			$out .= ' />';
		}
		
		return $out;
	}
}