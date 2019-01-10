<?php
class Item extends FrontBlock
{
	/**
	 * @var Model
	 */
	protected $model = null;
	
	/**
	 * @var PDOStatement
	 */
	protected $item = null;
	
	/**
	 * @var array
	 */
	protected $whereArray = array('uri' => '', 'status' => 1);
	
	/**
	 * @var array
	 */
	protected $orderArray = array();
	
	/**
	 * @var string
	 */
	protected $prefix = "i_";
	
	/**
	 * @var bool
	 */
	protected $showInPath = true;
	
	protected $saveParams = 0;
	
	function initialize($params=array()) 
	{
		if (empty($this->model))
		{
			throw new Exception('Model is empty!');
		}
		
		$this->getData();
		$this->initItem();
	}
	
	/**
	 * Init $this->items
	 */
	protected function getData()
	{
		$this->item = $this->model->getAll($this->whereArray, $this->orderArray, array(), 1);
	}
	
	protected function initItem($prefix='')
	{
		if(sizeof($this->item))
		{
			$this->tplVars[$prefix.'items_count'] = 0;
			$this->tplVars[$prefix.'counter']= array();
			
			foreach($this->item as $row) {
				foreach ($row as $key => $val) {
					
					if(!isset($this->tplVars[$prefix.$key])) {
						$this->tplVars[$prefix.$key] = array();
					}
					
					$this->tplVars[$prefix.$key][] = $val;
				}
				
				$this->tplVars[$prefix.'counter'][] = $this->tplVars[$prefix.'items_count']+1;
				$this->tplVars[$prefix.'items_count']++;
			}
			
			if ($this->showInPath)
			{
				$this->addPageItems();
			}
		}
		else 
		{
			Core::redirect(UUrl::getCuttedURL($this->saveParams));
		}
	}
	
	protected function addPageItems()
	{
		$this->page->addPageItems(array('title' => $this->item[0]['title'], 'uri' => UUrl::getCuttedURL($this->saveParams).@$this->item[0]['uri'].'/'));
	}
}