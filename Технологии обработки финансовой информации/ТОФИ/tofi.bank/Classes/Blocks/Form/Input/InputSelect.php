<?php
class InputSelect extends Input 
{
	function __construct($name, $priority, $loop, $type, $inputVars)
	{
		parent::__construct($name, $priority, $loop, $type, $inputVars);
	}
	
	protected function processInput()
	{
		$core = Core::getInstance();
		
       		$confirm = (isset($this->inputVars['confirm']) && strcasecmp($this->inputVars['confirm'], 'true')==0);

		if ($confirm) 
		{
			$out = '<input autocomplete="off" type="hidden"';
		}
		else 
		{
			$out = '<select autocomplete="off"';
		}
		
		if (isset($this->inputVars['multiple'])) 
		{
			$out .= ' name="'.$this->inputVars['name'].'[]"';
		}
		else 
		{
			$out .= ' name="'.$this->inputVars['name'].'"';
		}

		foreach ($this->inputVars as $k => $v)
		{
			if ($this->checkParamAllowed($k, array("type", "name", "priority", "confirm")))
			{
				$out .= ' ' . $k . '="' . htmlspecialchars($v) . '"';
			}
		}

		if (!$confirm) 
		{
			$out .= '>'; 
		}
		else 
		{
			$out .= ' value="'.htmlspecialchars($this->value).'">';
		}
		
		if (!isset($core->tplVars[$this->name.":select"]))
		{
			throw new Exception("Данные для контроллера воода [".$this->name."] не переданы");
		}

		if (!is_array($core->tplVars[$this->name.":select"]))
		{
			throw new Exception("Данные для контроллера воода [".$this->name."] не переданы");
		}
        		
		$groupstarted = false;
		
		foreach ($core->tplVars[$this->name.":select"] as $optname => $v)
		{
			if (is_array($v))
			{
				$optvalue = $v[0];
				$optgroup = $v[1];
				$optstyle = isset($v[2])?'style="'.$v[2].'"':'';
			}
			else 
			{
				$optvalue = $v;
				$optgroup = false;
				$optstyle = '';
			}
			
			if (!$core->tplVars[$this->name.':escaped'])
			{
				$optname = htmlspecialchars($optname);
				$optvalue = htmlspecialchars($optvalue);
			}
		
			if ( ( (is_array($this->value)) && (in_array($optname, $this->value)) ) || ( (!is_array($this->value)) && (strcmp($this->value, $optname) == 0) ) )
			{
				if (!$confirm)
				{
					$out .= '<option '.($optstyle).' value="'.($optname).'" selected="selected">'.($optvalue).'</option>';
				}
				else
				{
					$out .= $optvalue;
				}
			}
			else
			{
				if (!$confirm)
				{
					if ($optgroup)
					{
						if ($groupstarted)
						{
							$out .= '</optgroup>';
						}
						
						$out .= '<optgroup '.($optstyle).' label="'.($optvalue).'">';
					}
					else 
					{
						$out .= '<option '.($optstyle).' value="'.($optname).'">'.($optvalue).'</option>';
					}
				}
			}
		}
		
		if ($groupstarted)
		{
			$out .= '</optgroup>';
		}

		if (!$confirm)
		{
			$out .= '</select>';
		}

		return $out;
	}
}