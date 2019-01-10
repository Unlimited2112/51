<?php
Loader::loadBlock('ItemSubEdit', 'AdminBase');

class CreditsFactEdit extends ItemSubEdit
{
	function __construct()
	{
		parent::__construct('ItemEdit');
		$this->model_name = "CreditsFact";
		$this->url = "creditfact";
        $this->id_field = 'credit_id';
		$this->controlerPath = BLOCKS_ADMIN;
		$this->TplFileName = "CreditsFactEdit/CreditsFactEdit.tpl";
	}
};