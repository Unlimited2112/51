<?php
class StructureTemplatesOptions extends ModelOptions
{
	function __construct ($multilanguage)
	{
		parent::__construct($multilanguage);
	}
	
	function getFields()
	{		
		$e_select = array(
			'title' => array(
				'type' => 'text' ,
				'validator' => array(
					'isRequired' => true, 
					'minLength' => 1,
					'maxLength' => 256
				)
			),
			'system' => array(
				'type' => 'text' ,
				'validator' => array(
					'isRequired' => true, 
					'minLength' => 1,
					'maxLength' => 256
				)
			),
			'action' => array(
				'type' => 'checkbox', 
				'validator' => array(
					'default' => 0
				)
			),
			'hidden' => array(
				'type' => 'checkbox', 
				'validator' => array(
					'default' => 0
				)
			)
		);
		return $e_select;
	}

	function Init()
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
			$this->eFields = $this->getFields();
		}
		{ // view
			$v_select = array('id' => '', 'title' => '100%');
			$v_length = array('title' => 100);
			$this->vSQL = '
				select
					' . implode(',', array_keys($v_select)) . '
				from
					wf_structure_templates
				where
					1=1
			';
			$this->MakeVSelect($v_select, $v_length);
			
		}
		{ // 
			$this->eTitleField = 'title';
			$this->ControlName = 'structure_templates';
		}
	} 
}