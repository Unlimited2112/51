<?php
abstract class ItemsList extends FrontBlock
{
	/**
	 * @var Model
	 */
	protected $model = null;
	
	/**
	 * @var PDOStatement
	 */
	protected $items = null;
		
	/**
	 * @var array
	 */
	protected $whereArray = array('status' => 1);
	
	/**
	 * @var array
	 */
	protected $orderArray = array('id' => 'DESC');
	
	/**
	 * @var array
	 */
	protected $fields = array();
	
	/**
	 * @var integer/array
	 */
	protected $limit = 1;
	
	/**
	 * @var string
	 */
	protected $prefix = 'il_';
	
	function initialize($params=array()) 
	{
		if (empty($this->model))
		{
			throw new Exception('Model is empty!');
		}
		
		$this->getData();
		$this->initList();
	}
	
	/**
	 * Init $this->items
	 */
	protected function getData()
	{
		$this->items = $this->model->getAll($this->whereArray, $this->orderArray, $this->fields, $this->limit);
	}
	
	/**
	 * Init list
	 *
	 */
	protected function initList()
	{
		$prefix = $this->prefix;
		if(sizeof($this->items))
		{
			$this->tplVars[$prefix.'items_count'] = 0;
			$this->tplVars[$prefix.'counter']= array();
			
			foreach($this->items as $row) {
				foreach ($row as $key => $val) {
					if(!isset($this->tplVars[$prefix.$key])) {
						$this->tplVars[$prefix.$key] = array();
					}
					
					$this->tplVars[$prefix.$key][] = $val;
				}
				
				$this->tplVars[$prefix.'counter'][] = $this->tplVars[$prefix.'items_count']+1;
				$this->tplVars[$prefix.'items_count']++;
			}			
		}
		else 
		{
			$this->tplVars[$this->prefix.'items_count'] = 0;
		}
	}
}