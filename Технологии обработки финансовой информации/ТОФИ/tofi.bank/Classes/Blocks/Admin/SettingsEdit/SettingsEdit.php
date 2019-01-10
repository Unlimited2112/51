<?php
Loader::loadBlock('AutomaticItemEdit', 'AdminBase');

class SettingsEdit extends AutomaticItemEdit
{
	function  AfterInitControl($item_id, $operation)
	{
		if(!$item_id) return;
		
		$res = $this->Item->getByID($item_id);
		if ($res) 
		{
			$type = $res['type'];
			
			$this->Fields['value']['validator']['isRequired'] = (bool) $res['mandatory'];
			
			if($type == FormField::TYPE_HTML) {
				$this->Fields['value']['type'] = 'html';
			}
			elseif($type == FormField::TYPE_TEXT) {
				$this->Fields['value']['type'] = 'textarea';
			}
			elseif($type == FormField::TYPE_STRING) {
				$this->Fields['value']['type'] = 'text';
			}
			elseif($type == FormField::TYPE_DATE) {
				$this->Fields['value']['type'] = 'date';
				$this->Fields['value']['validator']['isDate'] = true;
			}
			elseif($type == FormField::TYPE_BOOL) {
				$this->Fields['value']['type'] = 'checkbox';
			}
			elseif($type == FormField::TYPE_FILE) {
				$this->Fields['value']['type'] = 'file';
			}
			elseif($type == FormField::TYPE_NUMBER) {
				$this->Fields['value']['type'] = 'text';
				$this->Fields['value']['validator']['isNumber'] = true;
			}
			elseif($type == FormField::TYPE_EMAIL) {
				$this->Fields['value']['type'] = 'text';
				$this->Fields['value']['validator']['isEmail'] = true;
			}
			elseif($type == FormField::TYPE_URL) {
				$this->Fields['value']['type'] = 'text';
				$this->Fields['value']['validator']['isUrl'] = true;
			}
			elseif($type == FormField::TYPE_SYSTEM) {
				$this->Fields['value']['type'] = 'text';
				$this->Fields['value']['validator']['isSystem'] = true;
			}
		}
		else
		{
			Core::redirect ( $this->tplVars['redirect_page_url'] );
		}
	}
};