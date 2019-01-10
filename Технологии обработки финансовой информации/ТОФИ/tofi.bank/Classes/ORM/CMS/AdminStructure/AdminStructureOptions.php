<?php
class AdminStructureOptions extends ModelOptions
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
			'uri' => array(
				'type' => 'text' ,
				'validator' => array(
					'isRequired' => true, 
					'minLength' => 1,
					'maxLength' => 256
				)
			),
			'template' => array(
				'type' => 'text' ,
				'validator' => array(
					'isRequired' => true, 
					'minLength' => 1,
					'maxLength' => 256
				)
			),
			'parent' => array(
				'type' => 'text' ,
				'validator' => array(
					'minLength' => 1,
					'maxLength' => 256
				)
			),
			'perms' => array(
				'type' => 'select', 
				'select_arr' => array(
					USER_LEVEL_ADMIN => $this->Localizer->getString('admin'),
					USER_LEVEL_SUPERVISOR => $this->Localizer->getString('supervisor')
				),
				'validator' => array(
					'isRequired' => true, 
					'keyEnumeration' => array(
						USER_LEVEL_ADMIN => $this->Localizer->getString('admin'),
						USER_LEVEL_SUPERVISOR => $this->Localizer->getString('supervisor')
					)
				),		
				'add' => USER_LEVEL_ADMIN
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
					wf_admin_structure
				where
					1=1
			';
			$this->MakeVSelect($v_select, $v_length);
		}
		{
			$this->eTitleField = 'title';
			$this->ControlName = 'admin_structure';
		}
	} 
}