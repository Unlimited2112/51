<?php
abstract class BaseBlock extends Block
{
	/**
	 * База данных 
	 *
	 * @var DataBase
	 */
	public $DataBase = null;
	
	/**
	 * Пользователи
	 *
	 * @var User
	 */
	public $User = null;
	
	/**
	 * Локализация
	 *
	 * @var Localizer
	 */
	public $Localizer = null;
	
	/**
	 * Настройки
	 *
	 * @var Settings
	 */
	public $Settings = null;
	
	/**
	 * @var View
	 */	
	public $page;
	
	/**
	 * Входные параметры
	 * 
	 * @var array
	 */
	public $page_params;
	
	/**
	 * URL
	 * 
	 * @var string
	 */
	public $page_url;
	
	/**
	 * Page parent
	 * 
	 * @var string
	 */
	public $page_parent;
	
	/**
	 * Значение пути
	 * 
	 * @var root_path
	 */
	public $root_path;
	
	/**
	 * Массив переменных шаблонов
	 *
	 * @var array
	 */
	public $tplVars;
		
	/**
	 * ID текущего языка
	 *
	 * @var string
	 */
	public $Lang;
	
	/**
	 * Путь к шаблону контроллера
	 * 
	 * @var string
	 */
	public $TplFileName = '';
	
	/**
	 * Конструктор контроллера
	 *
	 * @param string $name Имя контроллера
	 * @param string $objectId ID контроллера
	 */
	function __construct()
	{
		parent::__construct();

		$this->DataBase  = $this->Core->DataBase;
		$this->Localizer = $this->Core->Localizer;
		$this->Settings = $this->Core->Settings;
		$this->User	= $this->Core->getModel('User');
		
		$this->page = $this->Core->currentView;
		$this->page_params = &$this->page->page_params;
		$this->page_url = &$this->page->page_url;
		$this->page_parent = &$this->page->page_parent;
		$this->root_path = &$this->page->root_path;
		
		$this->tplVars = &$this->Core->tplVars;
		
		$this->Lang	= &$this->Core->currentLang;
		$this->ControlName = get_class($this);
		
		if($this->TplFileName == '') 
		{
			$this->TplFileName 	= $this->ControlName.'/'.$this->ControlName.'.tpl';
		}
	}
}