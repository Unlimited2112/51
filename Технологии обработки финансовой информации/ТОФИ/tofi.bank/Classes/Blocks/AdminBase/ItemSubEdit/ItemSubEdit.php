<?php
abstract class ItemSubEdit extends AdminBlock 
{
	/**
	 * @var Model
	 */	
	protected $Item;
	protected $model_name;
	protected $id_field = 'id_parent';
	protected $title_field = 'title';
	protected $title_field_sql = 'title';
	protected $url;
	protected $parent_item;
	protected $item_id = -1;
	protected $sortable = false;
	protected $fields = array();
	protected $unic = array();
	
	function __construct()
	{
		parent::__construct();
		$this->controlerPath = BLOCKS_ADMIN_BASE;
		$this->TplFileName = "ItemSubEdit/ItemSubEdit.tpl";
	}
	
	function initialize($params=array()) 
	{
		$this->initControlValues();
		$this->initBaseValues();
		$this->initUnicValues();
		$this->initForm();
		$this->checkDelete();
		$this->InitActiveTable();
		$this->processEditForm();
	}

	protected function initControlValues()
	{
		$this->parent_item = UUrl::getParram(1);
		$this->params_string = 'edit/'.$this->parent_item.'/'.$this->url.'/';
		$this->params_level = 3;
		$this->Item  = $this->Core->getModel($this->model_name);
		$this->Item->getOptions();
		if(empty($this->fields) and method_exists($this->Item, 'getFields'))
		{
			$this->fields = $this->Item->getFields();
		}
		$this->tplVars['itemName']  = $this->Item->dbName;
	}
	
	protected function initBaseValues()
	{
		$this->tplVars['redirect_page_url'] = $this->tplVars['HTTP'].'admin/'.$this->Core->currentLangStr.preg_replace(array('/\/\//','/^\//'),array('/',''),$this->page_url);
		if(($this->params_string != '') and ($this->params_level != 0)) 
		{
			$this->tplVars['redirect_page_url'] .= $this->params_string;
		}
		
		if ( UUrl::getParram($this->params_level, false) !== false ) 
		{
			$this->item_id = UUrl::getParram($this->params_level, -1);
			if ($this->item_id == 'add') 
			{
				$this->item_id = 0;
			}
		}
		
		if ($this->item_id>=0) 
		{
			if ($this->item_id == 0) 
			{
				$this->tplVars['operation'] = 'add';
			}
			else 
			{
				$this->tplVars['operation'] = 'edit';
			}
		}
		else 
		{
			$this->tplVars['operation'] = '';
		}
		
		$this->tplVars['subedit_info'] = unserialize(InCache('subedit_info',serialize(array())));
		SetCacheVar ('subedit_info', serialize(array()) );
	}
	
	protected function initUnicValues()
	{
		$this->unic = array($this->id_field => $this->parent_item);
	}
	
	protected function initForm()
	{
		Form::addForm('subedit_form');
		
		foreach ($this->fields as $field => $values) 
		{
			if (isset($values['type']))
			{
				$type = $values['type'];
			}
			else 
			{
				$type = 'text';
			}
			
			if ($type == 'select')
			{
				Input::setSelectData($field, $values['select_arr']);
			}
			
			Form::addField('subedit_form', new FormField($field, (isset($values['validator'])?$values['validator']:array()), $type));
		}
	}
	
	protected function getFormData()
	{
		$update_arr = array();
		
		foreach ($this->unic as $key => $value) 
		{
			$update_arr[$key] = $value;
		}
		
		foreach ($this->fields as $field => $values) 
		{
			$update_arr[$field] = $this->tplVars[$field];
		}
		
		return $update_arr;
	}
	
	protected function getDataFromRes($res)
	{
		$this->tplVars['edTitle'] = $res[$this->title_field];
		
		foreach ($this->fields as $field => $values) 
		{
			$this->tplVars[$field] = $res[$field];
		}
	}
	
	protected function checkDelete()
	{
		if($this->Item->sequence)
		{
			$this->Item->SequenceController->UnickArr = $this->unic;
		}
		if (Form::isSubmited('subedit_show', 'delete')) 
		{				
			$data = InGetPost('checkboxes', '');
			if ( (is_array($data)) && (sizeof($data)) ) 
			{
				$ok = true;
				foreach($data as $k => $v) 
				{
					if(!$this->Item->DeleteItem($v))
					{
						$this->tplVars['error_upload_view'][] = $this->Item->getLastError();
						$ok = false;
						break;
					}
             			}
				if($this->Item->sequence)
				{
					$this->Item->SequenceController->recalculate();
				}
             			
				if($ok) $this->tplVars['info_upload_view'][] = $this->Localizer->getString('data_updated');
			} 
			else
			{
				$this->tplVars['error_upload_view'][] = $this->Localizer->getString('no_selected_items');
			}
		}		
	}
	
	/**
	 * Init Navigator Paramms
	 */
	protected function InitActiveTable()
	{
		Loader::loadBlock('ActiveTable', 'AdminBase');
		
		if($this->Item->sequence)
		{
			$count = $this->Item->SequenceController->getMax();
			
			SetCacheVar("SequenceMaxValue", $count, "SequenceController2");	
			
			if (InGet("seq_up2",1) > 1 && InGet("seq_up2",1) <= $count)
			{
				$this->Item->SequenceController->moveUp(InGet("seq_up2",1));
				Core::redirect($this->tplVars['redirect_page_url']);
			}
			elseif (InGet("seq_down2",0) < $count and InGet("seq_down2",0) >= 1)
			{
				$this->Item->SequenceController->moveDown(InGet("seq_down2",1));
				Core::redirect($this->tplVars['redirect_page_url']);
			}			
		}
		
		if ($this->Item->sequence)
		{
			$fields = array($this->title_field => $this->title_field , 'sort_id' => 'sort_id');
			$fieldssql = array($this->title_field_sql, 'sort_id');
			$fieldspersents = array($this->title_field => '80%' , 'sort_id' => '20%');
			SetCacheVar('sortBy', 'sort_id', $this->Item->dbName);
			SetCacheVar('sortMode', 'ASC', $this->Item->dbName);
			$order = 'sort_id';
			$ordertype = 'ASC';			
			$fieldfunc['sort_id'] = "sequence_view_list2";
		}
		else 
		{
			$fields = array($this->title_field => $this->title_field);
			$fieldssql = array('id', $this->title_field_sql);
			$fieldspersents = array('id' => '' , $this->title_field => '100%');
			SetCacheVar('sortBy', $this->title_field, $this->Item->dbName);
			SetCacheVar('sortMode', 'ASC', $this->Item->dbName);
			$order = 'title';
			$ordertype = 'ASC';			
		}

		$fieldlength = array($this->title_field => '80');
		
		$sql = '
			select
				id,
				' . implode(',', $fieldssql) . '
			from
				'.$this->Item->dbName.' t1
			where
				1=1 ';
		
		foreach ($this->unic as $key => $value) 
		{
			$sql .= 'and '.$key.' = \''.$value.'\' ';
		}
	
		$table = new ActiveTable('subedit_list', $sql, $fields, $order, $ordertype);
		BlocksRegistry::getInstance()->registerBlock('ActiveTable', $table, 'subedit_list');
		
		if($this->Item->sequence)
		{
			$table->sortable = array();
		}
		
		foreach ($fieldspersents as $k => $v) {
			$columnId = $table->addColumn($this->Localizer->getString($k), $k);
			$table->columns[$columnId]->setWidth($v);
			if ((isset($fieldlength[$k])) & (!empty($fieldlength[$k])))
			{
				$table->columns[$columnId]->setLength($fieldlength[$k]);
			}
			if ((isset($fieldfunc[$k])) & (!empty($fieldfunc[$k])))
			{
				$table->columns[$columnId]->noEscape = true;
				$table->columns[$columnId]->setFunction($fieldfunc[$k]);
			}
			if($this->Item->sequence and $this->Item->SequenceController->SortIdField == $k)
			{
				$table->columns[$columnId]->noEscape = true;
				$table->columns[$columnId]->clickable = false;
			}
		}
			
		if ($this->item_id > 0) 
		{
			$table->setHighlight('id', $this->item_id );
		}
	}
	
	protected function processEditForm()
	{
		if (Form::isSubmited('subedit_form'))
		{
			if (Form::validate('subedit_form'))
			{
				$this->Save();
			}
			else
			{
				$this->tplVars['error']=Form::getFormErrors('subedit_form');

				$this->Open();
			}
		}
		elseif($this->tplVars['operation'] != '')
		{
			$this->Open();
		}
	}
	
	/**
	 * Save Item. Redirect after save or create error messages on errors.
	 *
	 */
	protected function Save()
	{
		$update_arr = $this->getFormData();

		$ok = false;

		if(!empty($update_arr))
		{
			
			if ($this->Item->uri)
			{
				$this->Item->uriCheckArray = $this->unic;
			}
			
			if (($this->tplVars['operation'] == 'add'))
			{
				
				if ($this->Item->sequence)
				{
					$update_arr['sort_id'] = $this->Item->SequenceController->getNext();
				}
				
				$this->item_id = $this->Item->addItem($update_arr);
				if ($this->item_id)
				{
					$info['subedit_info'] = $this->Localizer->getString('v_info_'.$this->Item->dbName.'_add');
					SetCacheVar('subedit_info',serialize($info));
					$ok = true;
				}
				else $this->tplVars['error'][] = $this->Item->getLastError();
			}

			if(($this->tplVars['operation'] == 'edit'))
			{
				if ($this->Item->getByID($this->item_id))
				{
					if ($this->Item->updateItem ($this->item_id, $update_arr))
					{
						$info['subedit_info'] = $this->Localizer->getString('v_info_'.$this->Item->dbName.'_update');
						SetCacheVar('subedit_info',serialize($info));
						$ok = true;
					}
					else $this->tplVars['error'][] = $this->Item->getLastError();
				}
				else Core::redirect ( $this->tplVars['redirect_page_url'] );
			}
		}

		if($ok)
		{
			Core::redirect ( $this->tplVars['redirect_page_url'].$this->item_id.'/' );
		}
		else $this->Open($this->tplVars['operation'], $this->item_id);
	}

	/**
	 * Open or Add Item. Create arrays for form generation.
	 */
	protected function Open()
	{
		if( $this->tplVars['operation'] == 'add' ) 
		{
			foreach ($this->fields as $field => $values) 
			{
				if(array_key_exists('add',$values) and $values['add'] != '') $this->tplVars[$field] = $values['add'];
			}
		}
		
		if( $this->tplVars['operation'] == 'edit' )
		{
			$res = $this->Item->getByID($this->item_id);
			if ($res)
			{
				$this->getDataFromRes($res);
			} 
			else 
			{
				Core::redirect ( $this->tplVars['redirect_page_url'] );
			}
		}
	}
};