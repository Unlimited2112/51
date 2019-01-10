<?php
/**
 * 
 * @name		AutomaticItemEdit
 * @author	EG
 * @version	1.0.0.0 created (EvGo)
 */
class AutomaticItemEdit extends AdminBlock
{
	/**
	 * Current Management Object
	 *
	 * @var Model
	 */	
	protected $Item;

	/**
	 * Current Management Options Object
	 *
	 * @var ModelOptions
	 */		
	protected $ItemOptions;

	/**
	 * Fields Paramms Array
	 *
	 * @var array
	 */
	protected $Fields = array();

	/**
	 * Is User Can Add
	 *
	 * @var bool
	 */
	protected $Add = true;

	/**
	 * Is User Can Edit
	 *
	 * @var bool
	 */
	protected $Edit = true;
	
	protected $add_path = '';
	
	protected $params_from = 0;
	
	/**
	 * @param string $Item
	 * @param string $tpl
	 * @param bool $UseFullTemplate
	 * @return AutomaticItemEdit
	 */
	function __construct($Item, $tpl='', $UseFullTemplate = false)
	{
		parent::__construct();
		$this->TplFileName	= $tpl;
		$this->UseFullTemplate	= $UseFullTemplate;
		$this->Item  = $this->Core->getModel($Item);
		$this->ItemOptions  = $this->Item->getOptions();
		$this->tplVars['ItemName']  = $Item;
		$this->tplVars['ItemSmallName']  = $this->Item->dbName;
		$this->tplVars['custom_data'] =(!isset($this->tplVars['custom_data']))? null: $this->tplVars['custom_data'];
	}
	
	/**
	 * Main Function
	 *
	 * @return parse
	 */
	function process()
	{
		if ( UUrl::getParram($this->params_from, false) !== false ) 
		{
			$operation = UUrl::getParram($this->params_from, false);
		}

		
		if ( UUrl::getParram($this->params_from+1, false) !== false ) 
		{
			$item_id = UUrl::getParram($this->params_from+1, false);
		}
		else 
		{
			$item_id = 0;
		}
		
		$this->tplVars['operation'] = &$operation;
		$this->tplVars['item_id'] = &$item_id;
		
		$this->InitControl();
		
		if($this->Item->uri == true and $operation == "add" and isset($this->Fields['uri']))
		{
			unset($this->Fields['uri']);
		}
		elseif($this->Item->uri == true and $operation == "edit" and isset($this->Fields['uri']) and isset($this->ItemOptions->vDisabletList) and is_array($this->ItemOptions->vDisabletList) and in_array($item_id, $this->ItemOptions->vDisabletList))
		{
			unset($this->Fields['uri']);
		}
		
		$this->AfterInitControl($item_id, $operation);

		$this->InitValidators($operation);
		$this->AfterInitValidators($item_id, $operation);

		$this->tplVars['redirect_page_url'] = $this->tplVars['HTTP'].'admin/'.$this->Core->currentLangStr.preg_replace(array('/\/\//','/^\//'),array('/',''),$this->page_url).$this->add_path;

		if (Form::isSubmited('item_edit_form'))
		{
			if (Form::validate('item_edit_form'))
			{
				$this->BeforeSave($operation, $item_id);
				$this->Save($operation, $item_id);
			}
			else
			{
				$this->tplVars['error']=Form::getFormErrors('item_edit_form');

				$this->BeforeOpen($operation, $item_id);
				$this->Open($operation, $item_id);
			}
		}
		else
		{
			$this->BeforeOpen($operation, $item_id);
			$this->Open($operation, $item_id);
		}
		
		return $this->ProcessControl();
	}

	/**
	 * Init Management Paramms
	 */
	protected function InitControl()
	{
		$this->Add = $this->ItemOptions->Get('eSave');
		$this->Edit = $this->ItemOptions->Get('eSave');
		$this->Fields = $this->ItemOptions->Get('eFields');
		$this->TitleField = $this->ItemOptions->Get('eTitleField');
		$this->CommentField = $this->ItemOptions->Get('eCommentField');
		$this->tplVars['can_save'] = &$this->Edit;
	}
	
	/**
	 * Custom Init Control Function. Executed after InitControl() function.
	 *
	 * @param int $item_id
	 * @param string $operation
	 */
	protected function AfterInitControl($item_id, $operation) {}

	/**
	 * Init Management Validators
	 *
	 * @param string $operation
	 */
	protected function InitValidators ($operation)
	{
		Form::addForm('item_edit_form');
		foreach ($this->Fields as $k => $v)
		{
			if(isset($v['validator']))
			{
				$valid = $v['validator'];
				if (isset($valid['isRequired']) and $valid['isRequired'] === -1)
				{
					if ($operation == 'add')
					{
						$valid['isRequired'] = true;
					}
					else $valid['isRequired'] = false;
				}
				if($v['type'] == 'html')
				{
					if(!isset($valid['minLength']))
					{
						$valid['minLength'] = 1;
					}
					if(!isset($valid['minLength']))
					{
						$valid['maxLength'] = 40000;
					}
				}
				
				if(isset($v['option']))
				{
					$valid['option'] = $v['option'];
				}
				
				if($v['type'] == 'file')
				{
					Form::addField('item_edit_form', new FormField((empty($v['edit'])?$k:$v['edit']), $valid, $v['type']));
				}
				elseif ($v['type'] == 'select')
				{
					if(!empty($valid['enumeration']))
					{
						Input::setSelectData( (empty($v['edit'])?$k:$v['edit']), $v['select_arr'], $valid['enumeration'][1], $valid['enumeration'][2]);
					}
					else 
					{
						Input::setSelectData( (empty($v['edit'])?$k:$v['edit']), $v['select_arr'] );
					}
					
					Form::addField('item_edit_form', new FormField((empty($v['edit'])?$k:$v['edit']), $valid, $v['type']));
				}
				else
				{
					Form::addField('item_edit_form', new FormField((empty($v['edit'])?$k:$v['edit']), $valid, $v['type']));
				}
			}
		}
	}

	/**
	 * Custom Init Validators Function. Executed after InitValidators() function.
	 * 
	 * @param int $item_id
	 * @param string $operation
	 */
	protected function AfterInitValidators ($item_id, $operation) {}
	
	/**
	 * Custon Function. Initialised before Save.
	 *
	 * @param string $operation
	 * @param int $item_id
	 */
	protected function BeforeSave($operation, $item_id){}
	
	/**
	 * Save Item. Redirect after save or create error messages on errors.
	 *
	 * @param string $operation
	 * @param int $item_id
	 */
	protected function Save($operation, $item_id)
	{
		foreach ($this->Fields as $k => $v)
		{
			if((!isset($v['enabled'])?true:$v['enabled']))
			{
				if($v['type'] == 'html')
				{
					Loader::loadCustom(LIBS.'Vendors/Jare/', 'Typograph');
					$oJareTypograph = new Jare_Typograph($this->tplVars[(empty($v['edit'])?$k:$v['edit'])]);
					$oJareTypograph->getTof('dash')->disableBaseParam(array(
						//'mdash',
						//'mdash_2',
						//'mdash_3',
						'years',
						//'iz_za_pod',
						//'to_libo_nibud'
					));
					$oJareTypograph->getTof('etc')->disableBaseParam(array(
						//'tm_replace',
						//'r_sign_replace',
						//'copy_replace',
						'acute_accent',
						'auto_links',
						'email',
						'hyphen_nowrap',
						//'simple_arrow',
						'ip_address',
						'optical_alignment',
						'paragraphs'
					));
					$oJareTypograph->getTof('number')->disableBaseParam(array(
						//'auto_times_x',
						'numeric_sub',
						'numeric_sup',
						'simple_fraction',
						//'math_chars'
					));
					$oJareTypograph->getTof('punctmark')->disableBaseParam(array(
						'auto_comma',
						'punctuation_marks_limit',
						//'punctuation_marks_base_limit',
						//'hellip',
						//'eng_apostrophe',
						//'fix_brackets'
					));
					$oJareTypograph->getTof('quote')->disableBaseParam(array(
						//'quotes_outside_a',
						//'open_quote',
						//'close_quote',',
						'optical_alignment'
					));
					$oJareTypograph->getTof('space')->disableBaseParam(array(
						'nobr_abbreviation',
						'nobr_acronym',
						'nobr_before_unit',
						//'remove_space_before_punctuationmarks',
						//'autospace_after_comma',
						'autospace_after_pmarks',
						'super_nbsp',
						'many_spaces_to_one',
						'clear_percent',
						'nbsp_before_open_quote',
						'nbsp_before_particle',
						'ps_pps'
					));
					
					$update_arr[$k] = $oJareTypograph->parse(array('dash', 'etc', 'number', 'punctmark', 'quote', 'space'));
				}
				elseif($v['type'] == 'date')
				{
					if(!empty($this->tplVars[(empty($v['edit'])?$k:$v['edit'])]))
						$update_arr[$k] = $this->tplVars[(empty($v['edit'])?$k:$v['edit'])];
					else 
						$update_arr[$k] = null;
				}
				elseif($v['type'] == 'checkbox')
				{
					if(!empty($this->tplVars[(empty($v['edit'])?$k:$v['edit'])]))
						$update_arr[$k] = $this->tplVars[(empty($v['edit'])?$k:$v['edit'])];
					else
						$update_arr[$k] = 0;
				}
				else
				{
					$update_arr[$k] = $this->tplVars[(empty($v['edit'])?$k:$v['edit'])];
				}
			}
		}
		
		$ok = false;

		if(!empty($update_arr))
		{
			if (($operation == 'add') && $this->Add)
			{
				if (is_subclass_of($this->Item, 'ModelCategory'))
				{
					$update_arr[$this->Item->dbParentId] = $item_id;
				}
				
				if($this->Item->sequence)
				{
					if ($this->Item->multilanguage)
					{
						$this->Item->SequenceController->UnickArr['id_lang'] = $this->Core->currentLang;
					}
					
					if (is_subclass_of($this->Item, 'ModelCategory'))
					{
						$this->Item->SequenceController->UnickArr[$this->Item->dbParentId] = $update_arr[$this->Item->dbParentId];
					}
					
					$update_arr[$this->Item->SequenceController->SortIdField] = $this->Item->SequenceController->getNext();
				}
				
				$item_id = $this->Item->addItem ($update_arr);
				if ($item_id)
				{
					$info['info'] = $this->Localizer->getString('v_info_'.$this->tplVars['ItemSmallName'].'_add');
					SetCacheVar('info',serialize($info));
					$ok = true;
				}
				else $this->tplVars['error'][] = $this->Item->getLastError();
			}

			if(($operation == 'edit') && $this->Edit)
			{
				$item = $this->Item->getByID($item_id);
				if ($item)
				{
					if (is_subclass_of($this->Item, 'ModelCategory'))
					{
						$update_arr[$this->Item->dbParentId] = $item[$this->Item->dbParentId];
					}
					if ($this->Item->updateItem ($item_id, $update_arr))
					{
						$info['info'] = $this->Localizer->getString('v_info_'.$this->tplVars['ItemSmallName'].'_update');
						SetCacheVar('info',serialize($info));
						$ok = true;
					}
					else $this->tplVars['error'][] = $this->Item->getLastError();
				}
				else Core::redirect ( $this->tplVars['redirect_page_url'] );
			}
		}
		else
		{
			if (($operation == 'add') && $this->Add)
			{
				$info['info'] = $this->Localizer->getString('v_info_'.$this->tplVars['ItemSmallName'].'_add');
				SetCacheVar('info',serialize($info));
				$ok = true;
			}

			if(($operation == 'edit') && $this->Edit)
			{
				$info['info'] = $this->Localizer->getString('v_info_'.$this->tplVars['ItemSmallName'].'_update');
				SetCacheVar('info',serialize($info));
				$ok = true;
			}
		}

		if($ok)
		{
			$this->AfterSave($operation, $item_id);
			Core::redirect ( $this->tplVars['redirect_page_url'].'edit/'.$item_id.'/' );
		}
		else $this->Open($operation, $item_id);
	}

	/**
	 * Custon Function. Initialised after Save.
	 *
	 * @param string $operation
	 * @param int $item_id
	 */
	protected function AfterSave($operation, $item_id) {}
	
	/**
	 * Custon Function. Initialised before Open.
	 *
	 * @param string $operation
	 * @param int $item_id
	 */
	protected function BeforeOpen($operation, $item_id) {}
	
	/**
	 * Open or Add Item. Create arrays for form generation.
	 *
	 * @param string $operation
	 * @param int $item_id
	 */
	protected function Open($operation, $item_id)
	{
		$this->tplVars['edcomment'] = '';
		if( $operation == 'edit' )
		{
			$res = $this->Item->getByID($item_id);
			if ($res)
			{
				foreach ($this->Fields as $k => $v)
				{
					if(isset($v['enabled']) and !$v['enabled'])
					{
						Input::disable((empty($v['edit'])?$k:$v['edit']));
					}
					if ((!isset($v['db_field'])?true:$v['db_field']) and ($v['type'] != 'password'))
					{
                        if(!empty($v['virtual'])) {
                            $this->tplVars[(empty($v['edit'])?$k:$v['edit'])] = null;
                        }
                        else {
						    $this->tplVars[(empty($v['edit'])?$k:$v['edit'])] = $res[$k];
                        }
					}
				}
				$this->tplVars['edTitle'] = $res[$this->TitleField];
				if (!empty($this->CommentField))
				{
					$comment = $res[$this->CommentField];
					if(!empty($comment))
						$this->tplVars['comments'][] = $res[$this->CommentField];
				}
			} else {
				Core::redirect ( $this->tplVars['redirect_page_url'] );
			}
		}

		if( $operation == 'add' ) 
		{
			foreach ($this->Fields as $k => $v)
			{
				if(array_key_exists('add',$v) and $v['add'] != '') $this->tplVars[(empty($v['edit'])?$k:$v['edit'])] = $v['add'];
			}
		}
		
		foreach ($this->Fields as $k => $v)
		{
			$name =(empty($v['edit'])?$k:$v['edit']);	

			if(!isset($v['validator']))
			{
				$v['validator'] = array();
				$v['validator']['isRequired'] = false;
			}
			elseif ((isset($v['validator']['isRequired'])) and ($v['validator']['isRequired'] === -1))
			{
				if ($operation == 'add')
				{
					$v['validator']['isRequired'] = true;
				}
				else
				{
					$v['validator']['isRequired'] = false;
				}
			}
			elseif (!isset($v['validator']['isRequired']))
			{
				$v['validator']['isRequired'] = false;
			}		
		}
	}

	function ProcessControl()
	{
		if($this->UseFullTemplate)
		{
			ob_start();
			extract($this->tplVars, EXTR_SKIP or EXTR_REFS);
			include(BLOCKS_ADMIN . $this->TplFileName);
			return ob_get_clean();
		}
		else
		{
			ob_start();
			extract($this->tplVars, EXTR_SKIP or EXTR_REFS);
			include(BLOCKS_ADMIN_BASE . 'AutomaticItemEdit/AutomaticItemEdit.tpl');
			$this->tplVars['edit_form'] = ob_get_clean();
			
			if ($this->TplFileName == '') {
				return $this->tplVars['edit_form'];
			}
			else {
				ob_start();
				extract($this->tplVars, EXTR_SKIP or EXTR_REFS);
				include(BLOCKS_ADMIN . $this->TplFileName);
				return ob_get_clean();
			}
		}		
	}
};