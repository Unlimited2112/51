<?php
class BlocksRegistry 
{
	/**
	 * Class instance (for Singleton usage)
	 *
	 * @var BlocksRegistry
	 */
	protected static $instance;
	
	/**
	 * @var array
	 */
	protected $contollers = array();
	
	private function __construct() {}
	
	/**
	 * @return BlocksRegistry
	 */
	public static function getInstance()
	{
		if (!isset(self::$instance)) {
			self::$instance = new self();
		}
		
		return self::$instance;
	}
	
	/**
	 * @param string $name
	 * @param string $objectId
	 * @param Block $object
	 */
	public function registerBlock($name, Block $object, $objectId=null)
	{
		$name = strtolower($name);
		if (is_null($objectId)) 
		{
			$this->contollers[$name] = $object;
		}
		else 
		{
			if (!array_key_exists($name, $this->contollers)) 
			{
				$this->contollers[$name] = array();
			}
			$this->contollers[$name][$objectId] = $object;
		}
	}
	
	/**
	 * @param string $name
	 * @param string $objectId
	 * @return Block
	 */
	public function getBlock($name, $objectId = null)
	{
		if (!array_key_exists($name, $this->contollers))
		{
			if (strcasecmp($name, 'form')==0) 
			{
				Loader::loadBlock('MutiForm', 'Form');
				BlocksRegistry::getInstance()->registerBlock('Form', new MutiForm());
			}
			elseif (strcasecmp($name, 'input')==0) 
			{
				Loader::loadBlock('MutiInput', 'Form');
				BlocksRegistry::getInstance()->registerBlock('Input', new MutiInput());
			}
		}
			
		$obj = null;
		if (is_null($objectId))
		{
			if ((array_key_exists($name, $this->contollers)) && (!is_array($this->contollers[$name])))
			{
				$obj = &$this->contollers[$name];
			}
		}
		else
		{
			if (array_key_exists($name, $this->contollers) && is_array($this->contollers[$name]) && array_key_exists($objectId, $this->contollers[$name]))
			{
				$obj = &$this->contollers[$name][$objectId];
			}
		}
		
		return $obj;
	}
}