<?php
class SettingsOptions extends ModelOptions 
{
	function Init()
	{
		if($this->UserLevel == USER_LEVEL_ARHITECTOR)
		{
			$this->vSQL = '
							SELECT
								t1.id,
								t1.title,
								CASE WHEN hidden=0 THEN \''.$this->Localizer->getString('no').'\'
									 WHEN hidden=1 THEN \''.$this->Localizer->getString('yes').'\'
								END AS hidden
							FROM
								wf_settings t1
							';

			$this->vFields['title']		= 'title';
			$this->vFields['hidden']		= 'hidden';
			$this->vFieldsPercents['title']	 = '80%';	
			$this->vFieldsPercents['hidden']	 = '20%';	
		}
		else 
		{
			$this->vSQL = '
							SELECT
								t1.id,
								t1.title
							FROM
								wf_settings t1
							WHERE
								hidden = \'0\'
							';

			$this->vFields['title']		= 'title';
			$this->vFieldsPercents['title']	 = '100%';							
		}
		
		if($this->UserLevel != USER_LEVEL_ARHITECTOR)
		{
			$this->vAdd = false;
			$this->vDelete = false;
		}
		$this->vOrder = 'title';
		
		if($this->UserLevel == USER_LEVEL_ARHITECTOR)
		{
			$this->eFields['title']		= array(
										'type' => 'text',
										'validator' => array(
														'isRequired' => true, 
														'minLength' => 1,
														'maxLength' => 200
													)
										);
							
			$this->eFields['system']		= array(
										'type' => 'text', 
										'validator' => array(
														'isRequired' => true, 
														'minLength' => 1,
														'maxLength' => 200
													)
										);
										
			$this->eFields['type'] = array(
				'type' => 'select', 
				'select_arr' => array(
					FormField::TYPE_STRING => $this->Localizer->getString('type_string'),
					FormField::TYPE_TEXT => $this->Localizer->getString('type_text'),
					FormField::TYPE_HTML => $this->Localizer->getString('type_html'),
					FormField::TYPE_DATE => $this->Localizer->getString('type_date'),
					FormField::TYPE_BOOL => $this->Localizer->getString('type_bool'),
					FormField::TYPE_FILE => $this->Localizer->getString('type_file'),
					FormField::TYPE_NUMBER => $this->Localizer->getString('type_number'),
					FormField::TYPE_EMAIL => $this->Localizer->getString('type_email'),
					FormField::TYPE_URL => $this->Localizer->getString('type_url'),
					FormField::TYPE_SYSTEM => $this->Localizer->getString('type_system'),
				), 
				'validator' => array(
					'isRequired' => true, 
					'default' => 'string'
				)
			);
										
			$this->eFields['mandatory']	=  array(
										'type' => 'checkbox',
										'validator' => array(
														'default' => 0
													)
										); 	
				
			$this->eFields['hidden']	=  array(
										'type' => 'checkbox',
										'validator' => array(
														'default' => 0
													)
										); 			
				
			$this->eFields['comment']	=  array(
										'type' => 'text', 
										'validator' => array(
														'minLength' => 1,
														'maxLength' => 200
													)
										); 								
		}
						
		$this->eFields['value']			= array(
										'type' => 'text', 
										'validator' => array(
														'isRequired' => true, 
														'minLength' => 1,
														'maxLength' => 200
													)
										);	
		$this->eTitleField = 'title';
		$this->eCommentField = 'comment';
		
		$this->ControlName = 'settings';		
	}
}