<?php
class FormGenerator extends BaseBlock
{
	/**
	 * Имя кнопки submit в Локализации по-умолчанию
	 *
	 * @var string
	 */
	public $defaultSubmitTitle = '';
	
	/**
	 * @param string $FormType
	 * @return FormGenerator
	 */
	function __construct($FormType)
	{
		$this->TplFileName = 'FormGenerator/'.$FormType.'.tpl';
		
		parent::__construct();
	}
	
	function initialize($params=array()) 
	{
		$this->InitForm($params);
	}
	
	function InitForm($params)
	{
		$formType = isset($params['form_type']) ? $params['form_type'] : '';
		
		if(!empty($formType))
		{
			$this->TplFileName = 'FormGenerator/'.$formType.'.tpl';
		}
		
		$this->tplVars['form_name'] = isset($params['name']) ? $params['name'] : '';
		
		$fields = Form::getForm($this->tplVars['form_name']);
		
		$this->tplVars['form_submit_title'] = isset($params['submit_title']) ? $params['submit_title'] : $this->defaultSubmitTitle;
		$this->tplVars['form_action'] = isset($params['action']) ? $params['action'] : '';
		$this->tplVars['form_target'] = isset($params['target']) ? $params['target'] : '';
		$this->tplVars['form_onsubmit'] = isset($params['onsubmit']) ? $params['onsubmit'] : '';
		$this->tplVars['form_cancel'] = isset($params['cancel']) ? $params['cancel'] : (isset($this->tplVars['redirect_page_url'])?$this->tplVars['redirect_page_url']:'');
		
		$this->tplVars['fields_count'] = 0;
		$this->tplVars['field_name'] = array();
		$this->tplVars['field_type'] = array();
		$this->tplVars['field_option'] = array();
		$this->tplVars['field_value'] = array();
		$this->tplVars['field_required'] = array();
		
		$this->tplVars['html_fields'] = 0;
		$this->tplVars['html_fields_title'] = array();
		
		foreach ($fields as $field) 
		{
			$this->tplVars['field_name'][] = $field->name;
			$this->tplVars['field_type'][] = $field->type;
			if($field->type == 'file')
			{
				$this->tplVars['field_option'][] = $field->option;
				$this->tplVars['field_value'][] = isset($this->tplVars[$field->name])?$this->tplVars[$field->name]:'';
			}
			else 
			{
				$this->tplVars['field_option'][] = $field->option;
				$this->tplVars['field_value'][] = isset($this->tplVars[$field->name])?$this->tplVars[$field->name]:'';
			}
			$this->tplVars['field_required'][] = $field->isRequired;
			
			if($field->type == 'html')
			{
				$this->tplVars['html_fields']++;
				$this->tplVars['html_fields_title'][] = $field->name;
			}
			
			$this->tplVars['fields_count']++;
		}
		
	}
	
	function process()
	{
		ob_start();
		extract($this->tplVars, EXTR_SKIP or EXTR_REFS);
		include(BLOCKS . 'Form/' . $this->TplFileName);
		return ob_get_clean();
	}
}