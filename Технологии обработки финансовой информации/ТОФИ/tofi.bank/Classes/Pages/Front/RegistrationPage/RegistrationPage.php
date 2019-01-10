<?php

class RegistrationPage extends FrontPage
{
	protected function initialize()
	{
		parent::initialize();

		$this->tplVars['percent'] = $this->Settings->getSetting('percent_year');

		Form::addForm('registration_form');
		Form::addField('registration_form', new FormFieldRequired('registration_form_name', array('minLength' => 4, 'maxLength' => 64)));
		Form::addField('registration_form', new FormFieldRequired('registration_form_password', array('minLength' => 4, 'maxLength' => 64), 'password'));
		Form::addField('registration_form', new FormField('registration_form_store', array('default' => 0), 'hidden'));
		if (Form::isSubmited('registration_form'))
		{
			if (Form::validate('registration_form'))
			{
				$this->Core->currentLang = InPost('admin_area_id_lang', 1);
				SetCacheVar('admin_area_id_lang', $this->Core->currentLang);

				$l = $this->tplVars['registration_form_name'];
				$p = $this->tplVars['registration_form_password'];

				Loader::loadBlock('ArrayOutput', 'Helper');
				BlocksRegistry::getInstance()->registerBlock('ArrayOutput', new ArrayOutput());

				if ($this->User->getCount(array('login' => $l))) {
					$this->tplVars['error'][] = 'Пользователь с таким логином уже есть в системе. Выберите другой логин.';
				} else {
					if ($this->User->addItem(array(
						'login' => $l,
						'title' => $l,
						'email' => $l . '@example.com',
						'password' => $p,
						'cpassword' => $p,
						'status' => 1,
					))) {
//						$this->tplVars['info'][] = 'Вы успешно зарегистрировались в системе.';

						if ($this->User->login($l, $p, 0)) {
							Core::redirect($this->tplVars['HTTP'].'admin/');
							return;
						}
					}

					$this->tplVars['error'][] = 'Произошла неизвестная ошибка при регистрации.';

				}
			}
			else
			{
				Loader::loadBlock('ArrayOutput', 'Helper');
				BlocksRegistry::getInstance()->registerBlock('ArrayOutput', new ArrayOutput());

				$this->tplVars['registration_form_errors'] = Form::getFormErrors('registration_form');
			}
		}

		$this->tplVars['calculator_show'] = false;
		if (isset($_GET['credit_amount'])) {
			$this->tplVars['calculator_show'] = true;
			$percent = Core::getInstance()->Settings->getSetting('percent_year');
			$credits = Core::getInstance()->getModel('Credits');
			/**@var Credits $credits */
			$this->tplVars['creditInfo'] = $credits->getCreditInfo(
				$_GET['credit_term'],
				$_GET['credit_amount'],
				$percent
			);
		}
	}
}