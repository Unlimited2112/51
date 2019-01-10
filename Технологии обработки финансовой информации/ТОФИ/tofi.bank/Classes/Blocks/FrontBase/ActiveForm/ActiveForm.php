<?php
/**
 * 
 * @name	ActiveForm
 * @author	EvGo
 * @version	1.0.0.0 created (EvGo)
 */
abstract class ActiveForm extends FrontBlock
{
	/**
	 * Array of Form Fields
	 *  
	 * @var array = array('field_name' => array([m]'type'=> '', [m]'validator' => array(), 'select_arr' => array(), 'add' => '', 'class' => '', 'style' => ''))
	 */
	protected $Fields = array();
	
	/**
	 * @var string
	 */
	protected $RedirectURI = '';
		
	/**
	 * @param string $ControlName
	 * @param bool $UseCustomTemplate
	 * @return ActiveForm
	 */
	function __construct( $ControlName = 'ActiveForm', $UseCustomTemplate = false )
	{
		if (!$UseCustomTemplate) 
		{
			$this->controlerPath = BLOCKS_FRONT_BASE;
			$this->TplFileName = 'ActiveForm/ActiveForm.tpl';
		}

		parent::__construct();
		
		$this->RedirectURI = $this->tplVars['HTTPl'].preg_replace('/^\//','',$this->page_url );
	}
	
	function initialize($params=array()) 
	{
		$this->tplVars['apply_form_name'] = 'apply_form_'.md5($this->ControlName);
		
		$this->SetFormData();
		$this->InitForm();
		if (Form::isSubmited($this->tplVars['apply_form_name'])) 
		{ 
			if (Form::validate($this->tplVars['apply_form_name'])) 
			{
				if($this->BeforeSubmit())
				{
					if($this->OnSubmit())
					{
						$this->AfterSubmit();
					}
				}
			}
			else 
			{
				$this->tplVars['error'] = Form::getFormErrors($this->tplVars['apply_form_name']);
				$this->InitDefaults();
			}
		}
		else
		{
			if(InCache('form_success', 'false', $this->tplVars['apply_form_name']) == 'true')
			{
				SetCacheVar('form_success', 'false', $this->tplVars['apply_form_name']);
				$this->tplVars['info'][] = $this->Localizer->getString('txt_contact_success');
			}
			$this->InitDefaults();
		}
	}
	
	/**
	 * Custom Function for Init $this->Fields
	 *
	 */
	abstract protected function SetFormData();
	
	/**
	 * Init Validators from $this->Fields array
	 *
	 */
	protected function InitForm()
	{
		Form::addForm($this->tplVars['apply_form_name']);
		
		foreach ($this->Fields as $k => $v) 
		{
			$k = 'txt_'.$k;
			Form::addField($this->tplVars['apply_form_name'], new FormField($k, $v['validator'], $v['type']));
			
			if($v['type'] == 'select')
			{
				if (isset($v['select_arr'])) 
				{
					Input::setSelectData($k, $v['select_arr']);
				}
			}
		}
	}
	
	/**
	 * Init Defaults from $this->Fields array
	 *
	 */
	protected function InitDefaults()
	{
		foreach ($this->Fields as $k => $v) 
		{
			$this->tplVars['txt_'.$k] = isset($v['add'])?$v['add']:null;
		}		
	}
	
	protected function BeforeSubmit()
	{
		return true;
	}
	
	/**
	 * Make some actions with sent data from $this->Fields array and input_vars
	 *
	 */
	abstract protected function OnSubmit();

	protected function AfterSubmit()
	{
		SetCacheVar('form_success', 'true', $this->tplVars['apply_form_name']);
		Core::redirect($this->RedirectURI);
	}
}