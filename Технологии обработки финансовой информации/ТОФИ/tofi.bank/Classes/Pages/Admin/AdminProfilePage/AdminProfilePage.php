<?php
class AdminProfilePage extends AdminPage 
{
	function __construct()
	{
		parent::__construct();
		$this->IsCheckAllowed = false;
	}

	function process()
	{
		Form::addForm('user_edit_form');
		Form::addField('user_edit_form', new FormFieldRequired('new_login', array('minLength' => 4, 'maxLength' => 64)));
		Form::addField('user_edit_form', new FormField('new_password', array('minLength' => 4, 'maxLength' => 64), 'password'));
		Form::addField('user_edit_form', new FormField('new_confirm_password', array('minLength' => 4, 'maxLength' => 64, 'equalTo' => array('new_password', 'new_password')), 'password'));
		Form::addField('user_edit_form', new FormFieldRequired('new_email', array('isEmail' => true)));

		$this->tplVars['redirect_page_url'] = $this->tplVars['HTTP'].'admin/'.$this->Core->currentLangStr.preg_replace(array('/\/\//','/^\//'),array('/',''),$this->page_url);
		
		if (Form::isSubmited('user_edit_form'))
		{
			if (Form::validate('user_edit_form'))
			{
				$update_arr['login'] = $this->tplVars['new_login'];
				$update_arr['email'] = $this->tplVars['new_email'];
				$update_arr['password'] = $this->tplVars['new_password'];
				$update_arr['cpassword'] = $this->tplVars['new_confirm_password'];
				
				if ($this->User->updateProfile($this->User->UserData['id'], $update_arr))
				{
					$info['info'] = $this->Localizer->getString('v_info_admin_update');
					SetCacheVar('info',serialize($info));
					
					Core::redirect( $this->tplVars['redirect_page_url'] );
				}
				else
				{ 
					$this->tplVars['error'][] = $this->User->getLastError(); 
				}
			} 
			else 
			{
				$this->tplVars['error'] = Form::getFormErrors('user_edit_form');
			}
		} 
		else 
		{
			$res = $this->User->getByID($this->User->UserData['id']);
			if ($res)
			{
				$this->tplVars['new_login'] = $res['login'];
				$this->tplVars['new_email'] = $res['email'];
			} 
			else 
			{
				Core::redirect( $this->tplVars['redirect_page_url'] );
			}
		}
	}
}