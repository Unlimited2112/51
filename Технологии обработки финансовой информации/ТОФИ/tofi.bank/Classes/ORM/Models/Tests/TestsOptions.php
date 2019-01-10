<?php
class TestsOptions extends ModelOptions
{
	function Init ()
	{
		$TestsThemes = Core::getInstance()->getModel('TestsThemes'); /**@var $TestsThemes TestsThemes*/
		$res = $TestsThemes->getAll(array(), array('sort_id' => 'ASC'));
		$themes = array();
		foreach($res as $row) {
			$themes[$row['id']] = $row['title'];
		}

		{ //filters
			$this->vFilters = array(
//				array(
//					'title'		=>'theme',
//					'type'		=>'selector',
//					'event'		=>'=',
//					'source'	=>'theme',
//					'select_arr'=> $themes,
//				)
			);
		}
		{ // edit

			$answer_type = 'text';

			$this->eFields = array(
				'theme' => array(
					'type' => 'select',
					'validator' => array(
						'isRequired' => true,
						'keyEnumeration' => $themes
					),
					'select_arr' => $themes
				),
				'difficulty' => array(
					'type' => 'text' ,
					'validator' => array(
						'isRequired' => true,
						'minLength' => 1,
						'maxLength' => 100500
					)
				),
				'text' => array(
					'type' => 'html' ,
					'validator' => array(
						'isRequired' => true, 
						'minLength' => 1,
						'maxLength' => 100500
					)
				),
				'answer1' => array(
					'type' => $answer_type,
					'validator' => array(
						'isRequired' => true,
						'minLength' => 1,
						'maxLength' => 100500
					)
				),
				'answer2' => array(
					'type' => $answer_type,
					'validator' => array(
						'isRequired' => false,
						'minLength' => 1,
						'maxLength' => 100500
					)
				),
				'answer3' => array(
					'type' => $answer_type,
					'validator' => array(
						'isRequired' => false,
						'minLength' => 1,
						'maxLength' => 100500
					)
				),
				'answer4' => array(
					'type' => $answer_type,
					'validator' => array(
						'isRequired' => false,
						'minLength' => 1,
						'maxLength' => 100500
					)
				),
				'answer5' => array(
					'type' => $answer_type,
					'validator' => array(
						'isRequired' => false,
						'minLength' => 1,
						'maxLength' => 100500
					)
				),
				'answer6' => array(
					'type' => $answer_type,
					'validator' => array(
						'isRequired' => false,
						'minLength' => 1,
						'maxLength' => 100500
					)
				),
				'correct_answer' => array(
					'type' => 'text' ,
					'validator' => array(
						'isRequired' => true,
						'minLength' => 1,
						'maxLength' => 100500
					)
				),
				'sort_id' => array(
					'type' => 'text' ,
					'validator' => array(
						'isRequired' => true,
						'minLength' => 1,
						'maxLength' => 100500
					)
				),
			);
		}
		{ // view
			$v_select = array('id' => '', 'theme_title' => '15%', 'difficulty' => '5%', 'text' => '80%');
			$v_length = array('text' => 100);
			$this->vSQL = '
				select
					wf_tests.id,
					wf_tests.text,
					wf_tests.difficulty,
					wf_tests_themes.title as theme_title
				from
					wf_tests
				left join wf_tests_themes on wf_tests.theme = wf_tests_themes.id
				where
					1=1
			';

			$this->vOrder = 'id';
			$this->vOrderType = true;
			$this->MakeVSelect($v_select, $v_length);
		}
		{ // 
			$this->eTitleField = 'text';
			$this->ControlName = 'tests';
		}
	} 
}