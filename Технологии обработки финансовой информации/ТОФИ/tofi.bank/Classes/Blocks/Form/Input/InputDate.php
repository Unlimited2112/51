<?php
class InputDate extends Input 
{
	function __construct($name, $priority, $loop, $type, $inputVars)
	{
		parent::__construct($name, $priority, $loop, $type, $inputVars);
	}
	
	protected function processInput()
	{
		$out = '<input autocomplete="off" type="hidden" name="' . $this->name . '" value="' . $this->value . '" id="' . $this->name . '" />';
		$out .= '<table cellpadding="0" cellspacing="0"><tr><td nowrap="nowrap"><input type="text" readonly="readonly" name="' . $this->name . '_calendar_input" value="' . (($this->value)? date('d.m.Y', strtotime( $this->value )): '') . '" id="' . $this->name . '_calendar_input" class="inp inpdate" /></td>
		<td nowrap="nowrap" style="padding-left: 5px;"><input type="button" onclick="$(\'#'.$this->name.'\').val(\'\'); $(\'#'.$this->name.'_calendar_input\').val(\'\'); return false;" class="butt inact" value="'.Core::getInstance()->Localizer->getString('date_clear_button').'" onmouseout="this.className=\'butt inact\';" onmouseover="this.className=\'butt act\';" /></td>
		</tr></table>';
		
		$out .='
			<script type="text/javascript">
				$(document).ready(function(){
					$("#' . $this->name . '_calendar_input").datepicker({
						showOn: \'button\',
						buttonImage: \''.Config::$imagesPath.'cms/buttonDate.gif\',
						buttonImageOnly: true,
						altField: \'#' . $this->name . '\', 
						altFormat: \'yy-mm-dd\'
					});
				});
			</script>
		';
		
		return $out;
	}
}