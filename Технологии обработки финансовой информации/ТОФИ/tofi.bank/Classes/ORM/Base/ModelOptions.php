<?php
class ModelOptions
{
	/**
	 * Page Core Object
	 *
	 * @var Core
	 */
	protected $Core;
	
	/**
	 * Localizer Object
	 *
	 * @var Localizer
	 */
	protected $Localizer;
	
	/**
	 * DataBase Object
	 *
	 * @var DataBase
	 */
	protected $DataBase;
	
	/**
	 * User Object
	 *
	 * @var User
	 */
	protected $User;
	
	/**
	 * Settings Object
	 *
	 * @var Settings
	 */
	protected $Settings;
	
	/**
	 * User Permisions Level
	 *
	 * @var int
	 */
	protected $UserLevel;
	
	/**
	 * Global Template Vars
	 *
	 * @var array
	 */
	protected $tplVars;
	
	/**
	 * System Names for Controls
	 *
	 * @var string
	 */
	public $ControlName = '';
	
	/**
	 * Navigator SQL Statment
	 *
	 * @var string
	 */
	public $vSQL = '';
	
	public $vDisabletList = array();
	
	/**
	 * Fields Parrams Array
	 *
	 * @var array
	 */
	public $vFields = array();
	
	/**
	 * Fields Percents Array
	 *
	 * @var array
	 */	
	public $vFieldsPercents = array();
	
	/**
	 * Fields Length Array
	 *
	 * @var array
	 */	
	public $vFieldsLength = array();
	
	/**
	 * Fields Wrap Array
	 *
	 * @var array
	 */	
	public $vFieldsWrap = array();
	
	/**
	 * Fields Functions Array
	 *
	 * @var array
	 */
	public $vFieldsFunc = array();
	
	/**
	 * Sequence Controller Using
	 *
	 * @var bool
	 */
	public $vSequence = false;
	
	/**
	 * Order Field Name by Default
	 *
	 * @var string
	 */
	public $vOrder = '';
	
	/**
	 * Order Type by Default
	 *
	 * @var string
	 */
	public $vOrderType = true;

	public $vHideNumber = false;
	
	/**
	 * Is User Can Add
	 *
	 * @var bool
	 */
	public $vAdd = true;
	
	/**
	 * Is User Can Delete
	 *
	 * @var bool
	 */
	public $vDelete = true;

    public $vFixedSort = false;
	
	/**
	 * @var array
	 */
	public $vFilters = array();

	/**
	 * Fields For Edit Control
	 *
	 * @var array( 'db_field_name' => array(
										'add' => 'value', 
										'edit' => 'field_name_for_view', 
										[m]'type' => 'type_name', 
										'enabled' => true, 
										'db_field' => true, 
										'validator' => array(type[0,1,2], params),
										'select_arr' => array(),
										'file_index' => 'index',
										'textarea_rows' => '15',
										'can_delete' => 'true',
										'is_resize' => 'true',
										'class' => ''
										'width' => ''
									),
			  ... )
	 */
	public $eFields = array();
	
	/**
	 * Is User Can Save
	 *
	 * @var bool
	 */
	public $eSave = true;
	
	/**
	 * DB Field  for Title Generation
	 *
	 * @var bool
	 */
	public $eTitleField = 'title';
	
	/**
	 * DB Field for Comment Generation
	 *
	 * @var bool
	 */
	public $eCommentField = '';
	
	/**
	 * Is MultiLanguage Enabled
	 *
	 * @var bool
	 */
	public $multilanguage;
	
	function __construct($multilanguage = false)
	{
		$this->Core = Core::getInstance();
		$this->multilanguage = $multilanguage;
		$this->tplVars = &$this->Core->tplVars;
		$this->DataBase = $this->Core->DataBase;
		$this->Localizer = $this->Core->Localizer;
		$this->Settings = $this->Core->Settings;
		$this->User = $this->Core->User;
		
		$this->UserLevel = (isset($this->User->UserData['id_level']))?$this->User->UserData['id_level']:0;
		
		$this->Init();
	}
	
	public function Get($name)
	{
		return $this->$name;
	}
	
	public function Set($name, $value)
	{
		$this->$name = $value;
	}
	
	protected function SqlCase($name, $array)
	{
		$map_function	=create_function('$k, $v', 'return "when '. $name .'=\'{$k}\' then \'{$v}\'";');
		if(strpos($name, '.')=== false)
		{
			$case	='case '. implode("\n", array_map($map_function, array_keys($array), array_values($array))). ' end as '. $name;
		}
		else
		{
			$case	='case '. implode("\n", array_map($map_function, array_keys($array), array_values($array))). ' end as '. substr($name, strpos($name, '.')+1);
		}
		return $case;
	}
	
	protected function MakeVSelect($v_select, $v_length = array())
	{
		foreach($v_select as $k =>$prc)
		{
			if(! (integer) $prc){
				continue;
			}
			
			if(preg_match('/^([^\s\r\n]+)$/si', $k, $rexp) or preg_match('/[\s\r\n]+as[\s\r\n]+([^\s\r\n]+)/si', $k, $rexp)){
				$k =$rexp[1];
			}
			
			$this->vFields[$k] =$k;
			$this->vFieldsPercents[$k] =((integer) $prc). '%';
			
			if(isset($v_length[$k]))
				$this->vFieldsLength[$k] =$v_length[$k];
		}
	}
	
	protected function Init()
	{
		
	}
	
	public function getSequenceUnicArr()
	{		
		return array();
	} 
	
	public function getSequenceAllovedArr()
	{	
		return array();
	} 
	
	public function isSequenceAlloved()
	{	
		foreach ($this->getSequenceAllovedArr() as $key => $data) 
		{
			$type = $data['type'];
			$value = $data['value'];
			if($type)
			{
				if ($value <= 0 or empty($value))
				{
					return false;
				}
			}
			else 
			{
				if($key == 'status')
				{
					if ($value != -1)
					{
						return false;
					}
				}
				else 
				{
					if ($value == -1)
					{
						$value = 0;
					}
					if (!empty($value))
					{
						return false;
					}
				}
			}
		}
		return true;	
	} 
}