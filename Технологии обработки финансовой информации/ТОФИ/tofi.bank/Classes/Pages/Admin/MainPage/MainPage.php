<?php
class MainPage extends AdminPage 
{
	function __construct()
	{
		parent::__construct();
		$this->IsCheckAllowed = false;
	}

	protected function initCustomControls()
	{
	}
}