<?php
Loader::loadBlock('ItemSubEdit', 'AdminBase');

class CreditsPlanEdit extends ItemSubEdit
{
	function __construct()
	{
		parent::__construct('ItemEdit');
		$this->model_name = "CreditsPlan";
		$this->url = "creditplan";
        $this->id_field = 'credit_id';
		$this->controlerPath = BLOCKS_ADMIN;
		$this->TplFileName = "CreditsPlanEdit/CreditsPlanEdit.tpl";
	}
};