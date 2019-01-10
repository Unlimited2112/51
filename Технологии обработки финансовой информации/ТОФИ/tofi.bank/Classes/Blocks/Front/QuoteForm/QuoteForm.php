<?php
Loader::loadBlock('SendForm', 'FrontBase');

class QuoteForm extends SendForm
{
	protected function SetFormData()
	{
		$this->Fields = array(
			'name' => array(
					'type'=> 'text', 
					'validator' => array( 
								'isRequired' => true, 
								'minLength' => 3,
								'maxLength' => 50
							)
					),
			'email' => array(
					'type'=> 'text', 
					'validator' => array( 
								'isRequired' => true, 
								'isEmail' => true
							)
					),
			'message' => array(
					'type'=> 'textarea', 
					'validator' => array( 
								'isRequired' => true, 
								'minLength' => 10,
								'maxLength' => 2000
							)
					),
			'captcha' => array(
					'type'=> 'captcha',
					'validator' => array(
								'isRequired' => true,
								'minLength' => 5,
								'maxLength' => 5
							)
					)
		);	
	}

	protected function BeforeSubmit()
	{
		if ($this->tplVars['txt_captcha'] != $_SESSION["captcha"])
		{
			$this->tplVars['error'][] = $this->Localizer->getString('validator_enumeration', $this->Localizer->getString('txt_captcha'));
			$this->ShowErrors();
			return false;
		}

		$this->tplVars['txt_captcha'] = '';

		return true;
	}
}