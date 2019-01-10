<?php
abstract class Block 
{
	/**
	 * Ядро системы
	 *
	 * @var Core
	 */
	protected $Core;
	
	/**
	 * Имя контроллера
	 *
	 * @var string
	 */
	protected $objectName;
	
	/**
	 * ID контроллера
	 *
	 * @var string
	 */
	protected $objectId = null;
	
	protected $controlerPath;
	
	/**
	 * Конструктор контроллера
	 *
	 * @param string $name Имя контроллера
	 * @param string $objectId ID контроллера
	 */
	function __construct() 
	{
	    $this->Core = Core::getInstance();
	    
	    $this->objectName = get_class($this);
	    $this->objectId = null;
	
	    $this->Core->currentView->ViewControls[] = &$this;
	}
	
	/**
	 * Метод вызываемый при инициализации View
	 */
	public function initialize($params=array())
	{
		
	}
	
	/**
	 * Метод вызываемый на процессе View
	 */
	public function process()
	{
		extract($this->tplVars, EXTR_SKIP or EXTR_REFS);
		include($this->controlerPath . $this->TplFileName);
	}
}