<?php
abstract class ItemSubData extends AdminBlock
{
	/**
	 * @var Model
	 */
	protected $Item;
	protected $model_name;
	protected $item_id;
	protected $url;
	protected $fields = array();

	function __construct()
	{
		parent::__construct();
		$this->controlerPath = BLOCKS_ADMIN_BASE;
		$this->TplFileName = "ItemSubData/ItemSubData.tpl";
	}

	function initialize($params=array())
	{
		$this->initControlValues();
		$this->initFields();
		$this->initBaseValues();
		$this->initForm();
		$this->processEditForm();
	}

	protected function initControlValues()
	{
		$this->item_id = UUrl::getParram(1);
		$this->params_string = 'edit/'.$this->item_id.'/'.$this->url.'/';
		$this->params_level = 3;
		$this->Item  = $this->Core->getModel($this->model_name);
		$this->tplVars['itemName']  = $this->Item->dbName;
	}

	protected function initBaseValues()
	{
		$this->tplVars['redirect_page_url'] = $this->tplVars['HTTP'].'admin/'.$this->Core->currentLangStr.preg_replace(array('/\/\//','/^\//'),array('/',''),$this->page_url);
		if(($this->params_string != '') and ($this->params_level != 0))
		{
			$this->tplVars['redirect_page_url'] .= $this->params_string;
		}

		$this->tplVars['subedit_info'] = unserialize(InCache('subedit_info',serialize(array())));
		SetCacheVar ('subedit_info', serialize(array()) );
	}

	abstract protected function initFields();

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

		foreach ($this->fields as $field => $values)
		{
			$update_arr[$field] = $this->tplVars[$field];
		}

		return $update_arr;
	}

	protected function getDataFromRes($res)
	{
		foreach ($this->fields as $field => $values)
		{
			$this->tplVars[$field] = $res[$field];
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
		else
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
			if ($this->Item->getByID($this->item_id))
			{
				if ($this->Item->updateItem($this->item_id, $update_arr))
				{
					$info['subedit_info'] = $this->Localizer->getString('v_info_'.$this->Item->dbName.'_update');
					SetCacheVar('subedit_info',serialize($info));
					$ok = true;
				}
				else $this->tplVars['error'][] = $this->Item->getLastError();
			}
			else Core::redirect($this->tplVars['redirect_page_url']);
		}

		if($ok)
		{
			Core::redirect($this->tplVars['redirect_page_url']);
		}
		else
		{
			$this->Open();
		}
	}

	/**
	 * Open or Add Item. Create arrays for form generation.
	 */
	protected function Open()
	{
		$res = $this->Item->getByID($this->item_id);
		if($res)
		{
			$this->getDataFromRes($res);
		}
		else
		{
			Core::redirect($this->tplVars['redirect_page_url']);
		}
	}
};