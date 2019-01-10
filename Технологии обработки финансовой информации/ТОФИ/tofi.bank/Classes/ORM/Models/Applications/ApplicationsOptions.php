<?php
class ApplicationsOptions extends ModelOptions
{
	function Init ()
	{
		$statuses = array(
			0 => 'Черновик',
			1 => 'Отправлена (этап 1)',
			2 => 'Проверена специалистом по работе с клиентами (этап 2)',
			3 => 'Одобрена сотрудником службы безопасности (этап 3)',
			4 => 'Одобрена членом кредитного комитета (этап 4)',
			5 => 'Утверждена начальником кредитного отдела (этап 5)',
			6 => 'Операционист выдал деньги (этап 6)',
			100 => 'Отказано',
		);

		{ //filters
			$this->vFilters = array(
			);
		}

//		if ($this->Core->User->UserData['id_level'] == USER_LEVEL_USER )
		{ // edit
			$this->eFields = array(
				'amount' => array(
					'type' => 'text' ,
					'validator' => array(
						'isRequired' => true,
						'minValue' => 1
					)
				),
				'term_months' => array(
					'type' => 'text' ,
					'validator' => array(
						'isRequired' => true,
						'minValue' => 1
					)
				),
				'status' => array(
					'type' => 'select' ,
					'validator' => array(
						'isRequired' => false,
						'keyEnumeration' => $statuses
					),
					'select_arr' => $statuses
				),
				'fio' => array(
					'type' => 'text' ,
					'validator' => array(
						'isRequired' => true,
						'minLength' => 1,
						'maxLength' => 100500
					)
				),
				'passport_info' => array( // паспорт
					'type' => 'textarea' ,
					'validator' => array(
						'isRequired' => true,
						'minLength' => 1,
						'maxLength' => 100500
					)
				),
				'address_info' => array( // адрес
					'type' => 'textarea' ,
					'validator' => array(
						'isRequired' => true,
						'minLength' => 1,
						'maxLength' => 100500
					)
				),
				'phone' => array( // телефон
					'type' => 'text' ,
					'validator' => array(
						'isRequired' => true,
						'minLength' => 1,
						'maxLength' => 100500
					)
				),
				'request_printed' => array( // заявление
					'type' => 'file' ,
					'validator' => array(
						'isRequired' => true,
						'minLength' => 1,
						'maxLength' => 100500
					)
				),
				'income_statement' => array( // справка о доходах
					'type' => 'file' ,
					'validator' => array(
						'isRequired' => true,
						'minLength' => 1,
						'maxLength' => 100500
					)
				),
				'passport' => array( // паспорт
					'type' => 'file' ,
					'validator' => array(
						'isRequired' => true,
						'minLength' => 1,
						'maxLength' => 100500
					)
				),
				'military_id' => array( // военный билет
					'type' => 'file' ,
					'validator' => array(
						'isRequired' => false,
						'minLength' => 1,
						'maxLength' => 100500
					)
				),
				'insurance_certificate' => array( // военный билет
					'type' => 'file' ,
					'validator' => array(
						'isRequired' => true,
						'minLength' => 1,
						'maxLength' => 100500
					)
				),
				'third_party_documents' => array( // документы на залог/поручительство третьих лиц
					'type' => 'file' ,
					'validator' => array(
						'isRequired' => false,
						'minLength' => 1,
						'maxLength' => 100500
					)
				),
				'comments' => array(
					'type' => 'textarea' ,
					'validator' => array(
						'isRequired' => false,
						'minLength' => 1,
						'maxLength' => 100500
					)
				),
			);
		}

		{ // view
			$v_select = array(
                'id' => '' ,
				'fio' => '50%',
                'title' => '50%',
                'percent' => '50%',
                'amount' => '50%',
                'term_months' => '50%',
                'status' => '50%',
                'cdate' => '50%',
                'udate' => '50%',
            );

			$id_level = @$this->Core->User->UserData['id_level'];

			if ($id_level == USER_LEVEL_USER) {
				unset($v_select['fio']);
			} else {
				unset($v_select['title']);
			}

			$where = 'true';
			if ($id_level == USER_LEVEL_CLIENT_SERVICES) {
				$where = "status=1";
			} elseif ($id_level == USER_LEVEL_SECURITY) {
				$where = "status=2";
			} elseif ($id_level == USER_LEVEL_CREDIT_COMMITTEE) {
				$where = "status=3";
			} elseif ($id_level == USER_LEVEL_CREDIT_DEPARTMENT_MANAGER) {
				$where = "status=4";
			} elseif ($id_level == USER_LEVEL_CREDIT_OPERATOR) {
				$where = "status=5";
			}

			if ($id_level == USER_LEVEL_USER) {
				$where .= ' AND user_id=' . (int)$this->Core->User->UserData['id'].' ';
			}

			$v_length = array('title' => 100);

			$case = 'CASE ';
			foreach ($statuses as $val => $title) {
				$case .= 'WHEN status='.$val." THEN '".$title."' ";
			}
			$case .= ' END AS status';


			$this->vSQL = '
				select
					id,
					fio,
					title,
					percent,
					amount,
					term_months,
					'.$case.',
					cdate,
					udate
				from
					wf_applications
				where '.$where.'
			';
			$this->vOrder = 'id';
			$this->vOrderType = 'DESC';
			$this->MakeVSelect($v_select, $v_length);
//			$this->vFieldsFunc['cdate'] = 'dateconvert';
		}
		{ // 
			$this->eTitleField = 'title';
			$this->ControlName = 'applications';
		}
	} 
}