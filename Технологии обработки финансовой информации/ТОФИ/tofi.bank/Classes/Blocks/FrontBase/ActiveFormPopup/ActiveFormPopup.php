<?php
Loader::loadBlock('ActiveForm', 'FrontBase');

/**
 * 
 * @name	ActiveForm
 * @author	EvGo
 * @version	1.0.0.0 created (EvGo)
 */
abstract class ActiveFormPopup extends ActiveForm
{
	/**
	 * @param string $ControlName
	 * @param bool $UseCustomTemplate
	 * @return ActiveFormPopup
	 */
	function __construct($UseCustomTemplate = false )
	{
		parent::__construct($UseCustomTemplate = false );

		if (!$UseCustomTemplate)
		{
			$this->TplFileName = 'ActiveFormPopup/ActiveFormPopup.tpl';
		}
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
				$this->ShowErrors();
			}
		}
		else
		{
			$this->InitDefaults();
		}
	}

	protected function AfterSubmit()
	{
		$this->ShowSuccess();
	}

	protected function ShowErrors()
	{
		echo '<script type="text/javascript">window.parent.showErrors(\''.implode('<br />', $this->tplVars['error']).'\');</script>';
		exit;
	}

	protected function ShowSuccess()
	{
		echo '<script type="text/javascript">window.parent.showSuccess(\''.$this->Localizer->getString('txt_contact_success').'\');</script>';
		exit;
	}
}