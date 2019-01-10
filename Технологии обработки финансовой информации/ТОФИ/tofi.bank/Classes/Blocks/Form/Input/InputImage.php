<?php
class InputImage extends Input 
{
	function __construct($name, $priority, $loop, $type, $inputVars)
	{
		parent::__construct($name, $priority, $loop, $type, $inputVars);
	}
	
	protected function processInput()
	{
		$out = '<a';

		if (isset($this->inputVars['class']))
		{
			$out .= ' class="' . htmlspecialchars($this->inputVars['class']) . '"';
		}

		$out .= ' href="javascript: ';

		if (isset($this->inputVars['confirm']))
		{
			$out .= 'if (confirm(\''.htmlspecialchars($this->inputVars['confirm']).'\')) ';
		}

		$out .= 'send_'.Form::$currentRunningForm.'_form(\'' . 
					(isset($this->inputVars['name'])?$this->inputVars['name']:'') . 
				'\', true);"';
				
		$out .= '>';
		$out .= '<img';

		foreach ($this->inputVars as $k => $v)
		{
			if ($this->checkParamAllowed($k, array("type", "validation", "priority", "value", "name", "confirm")))
			{
				$out .= ' ' . $k . '="' . htmlspecialchars($v) . '"';
			}
		}
		if (!isset($this->inputVars['alt']))
		{
			$out .= ' alt=""';
		}

		$out .= ' border="0" /></a>';
		
		return $out;
	}
}