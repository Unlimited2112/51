<?php
class CreditsPlanOptions extends ModelOptions
{
	function Init ()
	{
		{ //filters
			$this->vFilters = array(
				array(
					'title'		=>'title',
					'type'		=>'text',
					'event'		=>'like',
					'source'	=>'title',
				)
			);
		}
		{ // edit
			$this->eFields = array(
				'title' => array(
					'type' => 'text' ,
					'validator' => array(
						'isRequired' => true, 
						'minLength' => 1,
						'maxLength' => 180
					)
				),
			);
		}
		{ // view
			$v_select = array('id' => '' , 'date' => '20%' , 'title' => '80%');
			$v_length = array('title' => 100);
			$this->vSQL = '
				select
					' . implode(',', array_keys($v_select)) . '
				from
					wf_contract_agreements
				where
					1=1
			';
			$this->vOrder = 'date';
			$this->vOrderType = 'asc';
			$this->MakeVSelect($v_select, $v_length);
			$this->vFieldsFunc['date'] = 'dateconvert';
		}
		{ // 
			$this->eTitleField = 'title';
			$this->ControlName = 'contract_agreements';
		}
	} 
}