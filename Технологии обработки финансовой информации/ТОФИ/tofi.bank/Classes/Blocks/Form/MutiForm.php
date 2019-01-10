<?php
class MutiForm extends Block 
{
	protected $params;
	public function initialize($params=array()) {
		$this->params = $params;
	}
	
	function process() {
		
		$params = $this->params;
				
		$out = '';
		if (@$params['begin'] == 'true') 
		{
			$form_name = isset($params['name']) ? $params['name'] : '';
			if ('' == trim($form_name))
			{
				$form_name = isset($params['id']) ? $params['id'] : '';
			}
			if ('' == trim($form_name))					
			{
				throw new Exception('IT:Form должна иметь name или id');
			}

			Form::$currentRunningForm = $form_name;
			
			$out .= '<script type="text/javascript">
						<!--
						function send_'.Form::$currentRunningForm.'_form(action, issubmit) 
						{
							'.Form::getFormJSEditorsSave(Form::$currentRunningForm).'
							$(\'#'.Form::$currentRunningForm.'_form_action\').val(action);
							if(issubmit)
							{
								$(\'#'.Form::$currentRunningForm.'\').submit();
							}
						}
						
						'.Form::getFormJSValidator(Form::$currentRunningForm).'
					//--></script>
				';
			$out .= '<form name="'.htmlspecialchars(Form::$currentRunningForm).'" id="'.htmlspecialchars(Form::$currentRunningForm).'"';
			
			if(!array_key_exists('method', $params)) {
				$out .= ' method="post"';
			}
			if(!array_key_exists('action', $params)) {
				if (strlen((isset($_SERVER['QUERY_STRING']))?($_SERVER['QUERY_STRING']):('')) > 0)
				{
					$out .= ' action="' . htmlspecialchars(((isset($_SERVER['PHP_SELF']))?($_SERVER['PHP_SELF']):('')) . '?' . ((isset($_SERVER['QUERY_STRING']))?($_SERVER['QUERY_STRING']):(''))) . '"';
				}
				else
				{
					$out .= ' action="' . htmlspecialchars((isset($_SERVER['PHP_SELF']))?($_SERVER['PHP_SELF']):('')) . '"';
				}
			}
				
			foreach ($params as $k => $v)
			{
				if (
					($k != 'id') &&
					($k != 'begin') &&
					($k != 'end') &&
					($k != 'onsubmit') &&
					(!empty($v)) &&
					(strcasecmp($k, 'name') != 0) &&
					(strcasecmp($k, 'multiform') != 0)
					)
					{
						$out .= ' ' . $k . '="' . htmlspecialchars($v) . '"';
					}
			}
			$onsubmit = isset($params['onsubmit']) ? $params['onsubmit'] : '';
			if ( $onsubmit != '' )
			{
				$onsubmit .= ';';
			}
			$out .= ' onsubmit="' . $onsubmit . ' return false;">
				<div style="display:none"><input type="hidden" name="send_form_name" value="' . htmlspecialchars($form_name) . '" /></div>
				<div style="display:none"><input type="hidden" id="'.Form::$currentRunningForm.'_form_action" name="form_action" value="" /></div>
			';
		}
		elseif ($params['end'] == 'true') 
		{
			$out .= '</form>';
			Form::$currentRunningForm = '';
		}
		else
		{
			throw new Exception('IT:Form ошибка контроллера: необходим атрибут begin или end');
		}
		
		return $out;
	}
}