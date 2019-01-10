<?php
class StructureOptions extends ModelOptions
{
	function getFields()
	{		
		if( ($this->tplVars['operation'] == 'edit') and $this->UserLevel != USER_LEVEL_ARHITECTOR)
		{
			$page_data = $this->Core->getModel('Structure')->getByID($this->tplVars['item_id']);
			$can_edit = $page_data['can_edit'];
		}
		else
		{
			$can_edit = 1;
		}
		
		$e_select = array(
			'title' => array(
				'type' => 'text' ,
				'validator' => array(
					'isRequired' => true, 
					'minLength' => 1,
					'maxLength' => 256
				)
			)
		);
		
		if ($can_edit == 1)
		{
			$e_select += array(
				'system' => array(
					'type' => 'text' ,
					'validator' => array(
						'isRequired' => true, 
						'isSystem' => true
					)
				)
			);
		}
		
		if ($this->UserLevel == USER_LEVEL_ARHITECTOR)
		{
			$roles = array(
				USER_LEVEL_GUEST => $this->Localizer->getString('guest'),
				USER_LEVEL_USER => $this->Localizer->getString('user'),
				USER_LEVEL_MANAGER => $this->Localizer->getString('manager'),
			);

			$e_select += array(
				'perms' => array(
					'type' => 'select', 
					'select_arr' => $roles,
					'validator' => array(
						'isRequired' => true, 
						'keyEnumeration' => $roles,
						'default' => 0
					)
				),
				'id_template' => array(
					'type' => 'select', 
					'select_arr' => $this->Core->getModel('StructureTemplates')->getList(), 
					'validator' => array(
						'isRequired' => true, 
						'keyEnumeration' => $this->Core->getModel('StructureTemplates')->getList(),
						'default' => 0
					)
				),
			);
		}
		elseif ($can_edit == 1)
		{
			$e_select += array(
				'id_template' => array(
					'type' => 'select', 
					'select_arr' => $this->Core->getModel('StructureTemplates')->getList('id', 'title', array('hidden' => 0)), 
					'validator' => array(
						'isRequired' => true, 
						'keyEnumeration' => $this->Core->getModel('StructureTemplates')->getList('id', 'title', array('hidden' => 0)),
						'default' => 0
					)
				),
			);
		}
		
		$e_select += array(
			'show_in_menu' => array(
				'type' => 'checkbox', 
				'validator' => array(
					'default' => 0
				)
			),
			'status' => array(
				'type' => 'checkbox', 
				'edit' => 'active',
				'validator' => array(
					'default' => 0
				)
			)
		);
		
		if ($this->UserLevel == USER_LEVEL_ARHITECTOR)
		{
			$e_select += array(
				'can_edit' => array(
					'type' => 'checkbox', 
					'validator' => array(
						'default' => 0
					)
				),
				'can_add' => array(
					'type' => 'checkbox', 
					'validator' => array(
						'default' => 0
					)
				)
			);
		}
		
		return $e_select;
	}

	function Init()
	{
		$this->ControlName = 'stucture';
		
		$this->eFields = $this->getFields();
		$this->eTitleField = 'title';
	} 
}