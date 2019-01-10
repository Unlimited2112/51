<?php
abstract class ItemSubValues extends AdminBlock 
{
	/**
	 * @var Model
	 */	
	protected $Item;
	protected $model_name_values;
	protected $model_name_fields;
	protected $model_name_elements;
	
	protected $fields_prefix;
	
	/**
	 * @var PDOStatement
	 */
	protected $fields;
	
	protected $url;
	protected $id_element;
	
	protected $id_category_title = 'id_category';
	protected $id_element_title = 'id_element';
	protected $id_field_title = 'id_field';
	
	function __construct()
	{
		parent::__construct();
		$this->controlerPath = BLOCKS_ADMIN_BASE;
		$this->TplFileName = "ItemSubValues/ItemSubValues.tpl";
	}
	
	function initialize($params=array()) 
	{
		$this->initControlValues();
		$this->initBaseValues();
		$this->initForm();
		$this->processEditForm();
	}

	protected function initControlValues()
	{
		$this->id_element = UUrl::getParram(1);
		$this->params_string = 'edit/'.$this->id_element.'/'.$this->url.'/';
		$this->Item  = $this->Core->getModel($this->model_name_values);
		$this->Item->getOptions();
		
		$item = $this->Core->getModel($this->model_name_elements)->getByID($this->id_element);
		if($item)
		{
			$this->fields = $this->Core->getModel($this->model_name_fields)->getAll(array($this->id_category_title => $item[$this->id_category_title]));
		}
		
		$this->tplVars['itemName']  = $this->Item->dbName;
	}
	
	protected function initBaseValues()
	{
		$this->tplVars['redirect_page_url'] = $this->tplVars['HTTP'].'admin/'.$this->Core->currentLangStr.preg_replace(array('/\/\//','/^\//'),array('/',''),$this->page_url).$this->params_string;
		
		$this->tplVars['subedit_info'] = unserialize(InCache('subedit_info',serialize(array())));
		SetCacheVar ('subedit_info', serialize(array()) );
	}
		
	protected function initForm()
	{
		Form::addForm('subedit_form');
		
		$this->tplVars['show_form'] = false;
		
		if(sizeof($this->fields))
		{
			foreach($this->fields as $row) {
				$this->Localizer->addString($this->fields_prefix.$row['system'], '', 1, $row['title']);
				if (isset($row['validator']))
				{
					$validator = unserialize($row['validator']);
				}
				else
				{
					$validator = array();
				}
				
				if ($row['required'] == 1)
				{
					Form::addField('subedit_form', new FormFieldRequired($this->fields_prefix.$row['system'], $validator, $row['type']));
				}
				else
				{
					Form::addField('subedit_form', new FormField($this->fields_prefix.$row['system'], $validator, $row['type']));
				}
			}
		
			$this->tplVars['show_form'] = true;
		}
	}
	
	protected function getDataForm()
	{
		if(sizeof($this->fields)) {
			foreach ($this->fields as $row) {
				$content = $this->Item->getOne(array($this->id_element_title => $this->id_element, $this->id_field_title => $row['id']));
				if($content)
				{
					$this->tplVars[$this->fields_prefix.$row['system']] = $content['value'];	
				}
				else 
				{
					$content = $this->Item->addItem(array($this->id_element_title => $this->id_element, $this->id_field_title => $row['id'], 'value' => ''));
					$this->tplVars[$this->fields_prefix.$row['system']] = '';	
				}
			}
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
		foreach($this->fields as $row) {
			$content = $this->Item->getOne(array($this->id_element_title => $this->id_element, $this->id_field_title => $row['id']));
			if($content)
			{
				$content = $this->Item->updateItem($content['id'], array('value' => $this->tplVars[$this->fields_prefix.$row['system']]));
			}
			else 
			{
				$content = $this->Item->addItem(array($this->id_element_title => $this->id_element, $this->id_field_title => $row['id'], 'value' => $this->tplVars[$this->fields_prefix.$row['system']]));
			}
		}
		
		$info['subedit_info'] = $this->Localizer->getString('v_info_'.$this->Item->dbName.'_update');
		SetCacheVar('subedit_info',serialize($info));
		
		Core::redirect($this->tplVars['redirect_page_url']);
	}

	/**
	 * Open or Add Item. Create arrays for form generation.
	 */
	protected function Open()
	{
		$this->getDataForm();
	}
};