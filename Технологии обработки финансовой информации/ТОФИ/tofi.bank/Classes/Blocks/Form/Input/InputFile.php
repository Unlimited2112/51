<?php
class InputFile extends Input 
{
	function __construct($name, $priority, $loop, $type, $inputVars)
	{
		parent::__construct($name, $priority, $loop, $type, $inputVars);
	}
	
	protected function processInput()
	{
		$out = '<input autocomplete="off" type="hidden" name="' . $this->name . '" value="' . $this->value . '" id="' . $this->name . '" />';
		$out .= '<table cellpadding="0" cellspacing="0"><tr><td><div class="file" id="' . $this->name . 'div" />'.$this->value.'</div></td><td>&nbsp;</td><td>';
		if (!isset($this->inputVars['browser']))
		{
			$this->inputVars['browser'] = 'Files';
		}
		if (!isset($this->inputVars['folder']))
		{
			$this->inputVars['folder'] = '/';
		}
		$out .= '<img src="'. htmlspecialchars(Config::$imagesPath. "cms/buttonFiles.gif").'" id="' . $this->name . '_butt" name="' . $this->name . '_butt" class="hand" onclick="BrowseServer(\''.$this->inputVars['browser'].'\',\''.$this->inputVars['browser'].':'.$this->inputVars['folder'].'\',\'' . $this->name . '\');
" alt="Выбрать" /></td>';
		$out .= '<td valign="middle"><img src="'. htmlspecialchars(Config::$imagesPath. "cms/bottonClearDate.gif").'" alt="Очистить" class="hand" onclick="$(\'#' . $this->name . '\').val(\'\'); $(\'#' . $this->name . 'div\').html(\'\');" /></td></tr></table>';

		if ($this->value) {
			$out .= '<a href="'.$this->value.'" target="_blank">'.$this->value.'</a>';
		}

		return $out;
	}
}