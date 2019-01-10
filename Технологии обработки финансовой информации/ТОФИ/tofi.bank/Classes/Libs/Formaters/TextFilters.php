<?php
class TextFilters
{
	static protected $instance = null;
	
	protected $methodMirrors = array(
		'#' 	=> 'htmlspecialchars',
		'+' 	=> 'urlencode',
		'upper' => 'mb_strtoupper',
		'lower' => 'mb_strtolower',
		'rn2br' => 'rn2br',
		'dateconvert' => 'dateconvert'
	);
	
	protected function __construct()
	{
	}
	
	static public function getInstance()
	{
		if (self::$instance == null)
		{
			self::$instance = new TextFilters();
		}
		return self::$instance;
	}
	
	// now not using - it's for future coll text processor :)
	protected function getFilterParams($filter)
	{
		$filter_params = explode(':', $filter);
		array_shift($filter_params);
		array_walk($filter_params, 'trim');
		
		return $filter_params;
	}
	
	protected function mirrorExists($filter)
	{
		$filter = strtolower($filter);
		return isset($this->methodMirrors[$filter]);
	}
	
	protected function getMirror($filter)
	{
		$filter = strtolower($filter);
		return $this->methodMirrors[$filter];
	}
	
	public function goThrowFilters($content, $filters)
	{
		foreach ($filters as $filter)
		{
			$filter_name = trim(strtolower($filter));
			
			if (function_exists($filter_name))
			{
				$content = $filter_name($content);
			}
			elseif ($this->mirrorExists($filter_name))
			{
				$mirror = $this->getMirror($filter_name);
				$content = $mirror($content);
			}
		}
		
		return $content;
	}
	
}