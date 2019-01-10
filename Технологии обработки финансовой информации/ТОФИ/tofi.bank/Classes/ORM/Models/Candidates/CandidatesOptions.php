<?php
class CandidatesOptions extends ModelOptions
{
	function Init ()
	{
        if(isset($_GET['full_content']) || isset($_GET['status'])) {
            $_SESSION['cache']['wf_candidates']['search_status_status'] = isset($_GET['status']) ? $_GET['status'] : -1;
            $_SESSION['cache']['wf_candidates']['search_full_content_full_content'] = isset($_GET['full_content']) ? $_GET['full_content'] : '';
        }
        
        $this->vAdd = false;

        $task_statuses_res = Input::getArrayForSelect($this->DataBase->selectSql('wf_candidates_statuses'), 'id', 'title');
        $task_statuses_res_in = array();
        foreach($task_statuses_res as $k => $v) {
            $task_statuses_res_in["($k)"] = $v;
        }

		{ //filters
			$this->vFilters = array(
				array(
					'title'		=>'full_content',
					'type'		=>'text',
					'event'		=>'like',
					'source'	=>'full_content',
				),
				array(
					'title'		=>'status',
					'type'		=>'selector',
					'event'	    =>'in',
					'source'	=>'status',
					'sql_source'	=>'WC.status',
					'select_arr' => array('(1,2,5)' => 'Не отклонен') + $task_statuses_res_in + array('(1,2,3,4,5)' => 'Все'),
				),
			);
		}
		{ // edit

			$this->eFields = array(
				'comment' => array(
					'type' => 'textarea' ,
					'validator' => array(
						'isRequired' => false,
						'minLength' => 1
					)
				),
				'status' => array(
					'type' => 'select' ,
					'validator' => array(
						'isRequired' => false,
						'keyEnumeration' => $task_statuses_res
					),
					'select_arr' => $task_statuses_res
				),
				'title' => array(
					'type' => 'text' ,
					'validator' => array(
						'minLength' => 1,
						'maxLength' => 180
					)
				),
				'skype' => array(
					'type' => 'text' ,
					'validator' => array(
						'minLength' => 1,
						'maxLength' => 180
					)
				),
				'email' => array(
					'type' => 'text' ,
					'validator' => array(
						'minLength' => 1,
						'maxLength' => 180
					)
				),
			);
		}
		{ // view
			$v_select = array(
                'id' => '' ,
                'cdate' => '50%',
                'title' => '50%',
                'age_address' => '50%',
                'tries' => '50%',
                'answer_count' => '50%',
                'status_title' => '50%',
            );
			$v_length = array('title' => 100);
			$this->vSQL = '
				select
					WC.id,
					WC.title,
					CONCAT(WC.address, \', \', WC.age, \' лет\') age_address,
					(SELECT count(*) from wf_candidates WC2 where
					( NOT ISNULL(WC.session) AND WC.session<>\'\' AND WC2.session = WC.session ) or
					( NOT ISNULL(WC.ip) AND WC.ip<>\'\' AND WC2.ip = WC.ip ) or
					( NOT ISNULL(WC.email) AND WC.email<>\'\' AND WC2.email = WC.email ) or
					( NOT ISNULL(WC.skype) AND WC.skype<>\'\' AND WC2.skype = WC.skype ) or
					( NOT ISNULL(WC.title) AND WC.title<>\'\' AND WC2.title = WC.title ) or
					( NOT ISNULL(WC.skype) AND WC.skype<>\'\' AND WC2.full_content like CONCAT(\'%\', WC.skype, \'%\')) or
					( NOT ISNULL(WC.email) AND WC.email<>\'\' AND WC2.full_content like CONCAT(\'%\', WC.email, \'%\')) or
					( NOT ISNULL(WC.title) AND WC.title<>\'\' AND WC2.full_content like CONCAT(\'%\', WC.title, \'%\'))
					) tries,
					answer_count,
					cdate,
					WCS.title as status_title
				from
					wf_candidates WC
				left join wf_candidates_statuses WCS on WC.`status`=WCS.id
				where
					1=1
			';
			$this->vOrder = 'id';
			$this->vOrderType = 'DESC';
			$this->MakeVSelect($v_select, $v_length);
//			$this->vFieldsFunc['cdate'] = 'dateconvert';
		}
		{ // 
			$this->eTitleField = 'title';
			$this->ControlName = 'candidates';
		}
	} 
}