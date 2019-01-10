<?php
Loader::loadBlock('Menu', 'FrontBase');

class SiteMap extends Menu
{
	protected $prefix = "sm_";
	
	function initialize($params=array())
	{
		$this->tplVars[$this->prefix.'current_level'] = isset($params['level']) ? $params['level'] : 1;
		$this->tplVars[$this->prefix.'get_level'] = $this->tplVars[$this->prefix.'current_level'] + 1;

		parent::initialize($params);
	}
}