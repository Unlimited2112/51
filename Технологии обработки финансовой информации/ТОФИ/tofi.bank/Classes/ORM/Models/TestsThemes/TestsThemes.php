<?php

class TestsThemes extends Model
{
	function __construct()
	{
		parent::__construct('TestsThemes', 'wf_tests_themes');
		
		$this->uri = false;
		$this->savetime = false;
		$this->sequence = true;
	}
}