<?php

class Tests extends Model
{
	function __construct()
	{
		parent::__construct('Tests', 'wf_tests');
		
		$this->uri = false;
		$this->savetime = false;
		$this->sequence = true;
	}
}