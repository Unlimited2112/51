<?php

class CreditsPlan extends Model
{
	public function __construct()
	{
		parent::__construct('CreditsPlan', 'wf_credits_plan');
		
		$this->uri = false;
		$this->savetime = false;
	}
}