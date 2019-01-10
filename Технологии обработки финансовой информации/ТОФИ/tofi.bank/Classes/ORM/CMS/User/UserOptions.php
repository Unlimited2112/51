<?php
class UserOptions extends ModelOptions 
{
	/**
	 * Доступные для отображения уровни пользователей
	 *
	 * @var array
	 */
	public $showedLevels = array();
	
	function Init()
	{
		$this->showedLevels = array(
			USER_LEVEL_USER => $this->Localizer->getString('user'),
			USER_LEVEL_CLIENT_SERVICES => 'Специалист по работе с клиентами',
			USER_LEVEL_SECURITY => 'Сотрудник службы безопасности',
			USER_LEVEL_CREDIT_COMMITTEE => 'Член кредитного комитета',
			USER_LEVEL_CREDIT_DEPARTMENT_MANAGER => 'Начальник кредитного отдела',
			USER_LEVEL_CREDIT_OPERATOR => 'Операционист',
			USER_LEVEL_ADMIN => $this->Localizer->getString('admin'),
			USER_LEVEL_MANAGER => $this->Localizer->getString('manager'),
		);

		/*
define('USER_LEVEL_CLIENT_SERVICES', 11);
define('USER_LEVEL_SECURITY', 12);
define('USER_LEVEL_CREDIT_COMMITTEE', 13);
define('USER_LEVEL_CREDIT_DEPARTMENT_MANAGER', 14);
define('USER_LEVEL_CREDIT_OPERATOR', 15);

			2 => 'Проверена специалистом по работе с клиентами (этап 2)',
			3 => 'Одобрена сотрудником службы безопасности (этап 3)',
			4 => 'Одобрена членом кредитного комитета (этап 4)',
			5 => 'Утверждена начальником кредитного отдела (этап 5)',
			6 => 'Операционист выдал деньги (этап 6)',
		 */

		if($this->UserLevel == USER_LEVEL_ARHITECTOR)
		{
			$this->showedLevels += array(
				USER_LEVEL_SUPERVISOR => $this->Localizer->getString('supervisor'),
			);
		}
		
		{ //filters
			$this->vFilters = array(
				array(
					'title'		=>'login',
					'type'		=>'text',
					'event'	=>'like',
					'source'	=>'login',
				),
				array(
					'title'		=>'title',
					'type'		=>'text',
					'event'	=>'like',
					'source'	=>'title',
				),
				array(
					'title'		=>'id_level',
					'type'		=>'selector',
					'event'	=>'=',
					'source'	=>'id_level',
					'select_arr'	=> array(-1 => $this->Core->Localizer->getString('all')) + 
					$this->showedLevels,
					'add'		=>-1
				),
				array(
					'title'		=>'status',
					'type'		=>'selector',
					'event'		=>'=',
					'source'	=>'status',
					'select_arr'=> $this->Core->Localizer->translateArray(
									array(
										-1				=> 'all',
										0				=> 'inactive',
										1				=> 'active',
									)
								),
					'add'		=> -1
				)				
			);
		}
		
		if( ($this->tplVars['operation'] == 'edit'))
		{
			$user = $this->User->getByID($this->tplVars['item_id']);
			$user_level = $user['id_level'];
		}
		else $user_level = USER_LEVEL_USER;

		if($this->UserLevel == USER_LEVEL_ARHITECTOR)
		{		
			$this->vSQL = '
				SELECT
					id,
					login,
					title,
					CASE WHEN status=0 THEN \''.$this->Localizer->getString('inactive').'\'
						 WHEN status=1 THEN \''.$this->Localizer->getString('active').'\'
					END AS status,

					CASE ';
						foreach($this->showedLevels as $id_level => $level_title) {
							$this->vSQL .= 'WHEN id_level=' . $id_level . ' THEN \'' . $level_title . '\'';
						}
				$this->vSQL .= '
					END AS id_level
				FROM
					wf_me_members
				WHERE
					id_level <= '.$this->UserLevel.'
				and 
					id <> '.$this->User->UserData['id'].'
					';
			
			$this->vFields['login'] = 'login';
			$this->vFields['title'] = 'title';
			$this->vFields['status'] = 'status';
			$this->vFields['id_level'] = 'id_level';

			$this->vFieldsPercents['login'] = '30%';
			$this->vFieldsPercents['title'] = '40%';
			$this->vFieldsPercents['status'] = '10%';
			$this->vFieldsPercents['id_level'] = '20%';
		}
		elseif($this->UserLevel >= USER_LEVEL_SUPERVISOR)
		{			
			$this->vSQL = '
				SELECT
					id,
					login,
					title,
					CASE WHEN status=0 THEN \''.$this->Localizer->getString('inactive').'\'
						 WHEN status=1 THEN \''.$this->Localizer->getString('active').'\'
					END AS status,
					CASE WHEN id_level=' . USER_LEVEL_USER . ' THEN \'' . $this->Localizer->getString('user') . '\'
						 WHEN id_level=' . USER_LEVEL_ADMIN . ' THEN \'' . $this->Localizer->getString('admin') . '\'
					END AS id_level
				FROM
					wf_me_members
				WHERE
					id_level <= '.$this->UserLevel.'
				and 
					id <> '.$this->User->UserData['id'].'
					';
			
			$this->vFields['id'] = 'id';
			$this->vFields['login'] = 'login';
			$this->vFields['title'] = 'title';
			$this->vFields['status'] = 'status';
			$this->vFields['id_level'] = 'id_level';

			$this->vFieldsPercents['id'] = '10%';
			$this->vFieldsPercents['login'] = '20%';
			$this->vFieldsPercents['title'] = '40%';
			$this->vFieldsPercents['status'] = '10%';
			$this->vFieldsPercents['id_level'] = '20%';
		}
		else 
		{
			Core::redirect('/admin/');
		}	
				
		$this->vOrder = 'login';
		$this->eFields['title'] = array(
										'type' => 'text', 
										'validator' => array(
														'isRequired' => true, 
														'minLength' => 4,
														'maxLength' => 180
													)
								);
		$this->eFields['login'] = array(
										'type' => 'text', 
										'validator' => array(
														'isRequired' => true, 
														'minLength' => 4,
														'maxLength' => 64
													)
								);
		$this->eFields['id_level'] = array(
										'type' => 'select', 
										'validator' => array(
														'isRequired' => true,  
														'keyEnumeration' => $this->showedLevels
													),
										'select_arr' => $this->showedLevels
									);
		$this->eFields['password'] = array(
										'type' => 'password', 
										'edit' => 'a_password', 
										'validator' => array(
														'isRequired' => -1, 
														'minLength' => 4,
														'maxLength' => 64
													)
									);
		$this->eFields['cpassword'] = array(
										'type' => 'password' ,
										'edit' => 'confirm_password', 
										'db_field' => false,
										'validator' => array(
														'isRequired' => -1, 
														'minLength' => 4,
														'maxLength' => 64,
														'equalTo' => array('a_password', 'confirm_password')
													)
										);
		$this->eFields['email'] = array(
										'type' => 'text',
										'validator' => array(
														'isRequired' => true, 
														'isEmail' => true
													)
										);

		$this->eFields['contacts'] = array(
										'type' => 'html',
										'validator' => array(
														'isRequired' => false,
														'minLength' => 1,
														'maxLength' => 10000
													)
										);

		$this->eFields['status'] = array(
										'edit' => 'active',
										'type' => 'checkbox',
										'add' => 1,
										'validator' => array(
														'default' => 0
													)
										);
		$this->eTitleField = 'login';
		$this->ControlName = 'me_members';
	}
}