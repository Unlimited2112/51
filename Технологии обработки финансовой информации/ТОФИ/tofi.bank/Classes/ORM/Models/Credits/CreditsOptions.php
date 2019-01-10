<?php
class CreditsOptions extends ModelOptions
{
	function Init ()
	{
		$this->vAdd = false;
		$this->vDelete = false;
		$this->eSave = false;

		{ //filters
			$this->vFilters = array(
			);
		}

//		if ($this->Core->User->UserData['id_level'] == USER_LEVEL_USER )
		{ // edit
			$this->eFields = array(
			);
		}

		{ // view
			$v_select = array(
                'id' => '' ,
				'fio' => '50%',
                'title' => '50%',
                'amount' => '50%',
                'date_start' => '50%',
                'date_end' => '50%',
                'fine' => '50%',
                'paid' => '50%',
            );

			$where = 'true';
			if ($this->Core->User->UserData['id_level'] == USER_LEVEL_USER) {
				unset($v_select['fio']);
				$where = ' user_id = ' . $this->Core->User->UserData['id'] . ' ';
			} elseif ($this->Core->User->UserData['id_level'] == USER_LEVEL_CREDIT_OPERATOR) {
				$where = ' true ';
			} else {
				$where = ' false ';
			}

			$v_length = array('title' => 100);

			$this->vSQL = "
				select
					id,
					fio,
					title,
					amount,
					date_start,
					date_end,
					fine,
					IF(paid, 'Да', 'Нет') paid
				from
					wf_credits
				where $where
			";
			$this->vOrder = 'id';
			$this->vOrderType = 'DESC';
			$this->MakeVSelect($v_select, $v_length);
//			$this->vFieldsFunc['cdate'] = 'dateconvert';
		}
		{ // 
			$this->eTitleField = 'title';
			$this->ControlName = 'credits';
		}
	} 
}