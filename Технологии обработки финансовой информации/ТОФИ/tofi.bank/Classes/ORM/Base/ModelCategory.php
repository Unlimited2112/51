<?php
/**
 * 
 * @name	ModelCategory
 * @author	EG
 * @version	1.0.0.0 created (EvGo)
 */
class ModelCategory extends Model
{
	/**
	 * Parent ID Field
	 *
	 * @var string
	 */		
	public $dbParentId = 'id_parent';

	/**
	 * @var bool
	 */
	public $rootAdd = true;

	/**
	 * @var integer
	 */
	public $maxLevel = 2;
	
	/**
	 * @var array
	 */
	protected $menu = array(
	);
	
	/**
	 * @param string $modelName
	 * @param string $dbName
	 * @param string $dbSql
	 * @return ModelCategory
	 */
	function __construct($modelName, $dbName, $dbSql=NULL) 
	{
		parent::__construct($modelName, $dbName, $dbSql);
		
		$this->sequence = true;
	}

	public function deleteItem ($item_id) 
	{
		if($this->onDelete($item_id))
		{
			$res = $this->getAll(array($this->dbParentId => $item_id));
			foreach ($res as $row) {
				$this->deleteItem($row[$this->dbId]);
			}
			
			$this->DataBase->deleteSql($this->dbName, array($this->dbId => $item_id));
			$this->afterDelete($item_id);
			return true;
		}
		else return false;
	}

	public function deleteItemAndUpdateSequences($id_item) 
	{
		$res =$this->getByID($id_item);
		$this->deleteItem($id_item);
		
		if ($this->multilanguage)
		{
			$this->SequenceController->UnickArr['id_lang'] = $this->Core->currentLang;
		}
		
		if ($res)
		{
			$this->SequenceController->UnickArr[$this->dbParentId] = $res[$this->dbParentId];
		}
		else 
		{
			$this->SequenceController->UnickArr[$this->dbParentId] = 0;
		}
		
		$this->SequenceController->recalculate();
	}

	public function moveUp($id_item) 
	{
		if ($this->multilanguage)
		{
			$this->SequenceController->UnickArr['id_lang'] = $this->Core->currentLang;
		}
		
		$res =$this->getByID($id_item);
		if($res)
		{
			$this->SequenceController->UnickArr[$this->dbParentId] = $res[$this->dbParentId];
			$this->SequenceController->moveUp($res[$this->sortField]);
		}
	}

	public function moveDown($id_item) 
	{
		if ($this->multilanguage)
		{
			$this->SequenceController->UnickArr['id_lang'] = $this->Core->currentLang;
		}
		
		$res =$this->getByID($id_item);
		if($res)
		{
			$this->SequenceController->UnickArr[$this->dbParentId] = $res[$this->dbParentId];
			$this->SequenceController->moveDown($res[$this->sortField]);
		}
	}
	
	public function isCanDeleteSel($id_item, $sel_id) 
	{
		$id_item = intval($id_item);
		$sel_id = intval($sel_id);
		
		if (!$id_item)
		{
			return false;
		}
		
		if (empty($sel_id))
		{
			return false;
		}
		
		if ($sel_id == $id_item)
		{
			return true;
		}
		
		list ($del_id_arr) = $this->getTree();
		
		$path = array();
		$path = $this->getPathToRoot($del_id_arr, $sel_id);
		if (is_array($path)) 
		{
			foreach ($path as $v) 
			{
				if (($v == $id_item))
				{
					return true;
				}
			}
		}
		
		return false;
	}

	/**
	 * @param integer $parent_id
	 * @param array $whereArray
	 * @param array $fields
	 * @return PDOStatement
	 */	
	public function getByParent($parent_id, $whereArray = array(), $fields = array())
	{
		if (empty($parent_id)) 
		{
			throw new Exception('Invalid input data: Parent ID must be greter than zero.');
		}
		else 
		{
			$whereArray += array($this->dbParentId => $parent_id);
			
			return $this->getOne($whereArray, $fields);
		}
	}
	
	public function getTree($id_parent = 0, $whereArray = array()) 
	{
		if (!isset($this->menu[$id_parent][base64_encode(serialize($whereArray))])) 
		{
			$arr = array();
			$r_arr = array();
			$cur_arr = array();
			
			$res = $this->getAll($whereArray, array($this->sortField => 'ASC'));
			foreach ($res as $row) {
				$keys = array_keys($row);
				foreach ($keys as $field) 
				{
					$arr[$row[$this->dbId]][$field] = $row[$field];
				}
				
				if ($row[$this->dbParentId] != $id_parent) 
				{
					$arr[$row[$this->dbParentId]]['children'][$row[$this->dbId]] = $row[$this->dbId];
				} 
				else 
				{
					$cur_arr[$row[$this->dbId]] = $row[$this->dbId];
				}
				
				$r_arr[$row[$this->dbParentId]]['children'][] = $row[$this->dbId];
				$r_arr[$row[$this->dbId]][$this->dbParentId] = $row[$this->dbParentId];
				$r_arr[$row[$this->dbId]][$this->uriField] = $row[$this->uriField];
			}
			
			$arr = $this->getPlainMenuArray($arr, array_keys($cur_arr));
			
			$this->menu[$id_parent][base64_encode(serialize($whereArray))] = array($arr, $r_arr);
		}
		
		return $this->menu[$id_parent][base64_encode(serialize($whereArray))];
	}

	/**
	 * @param array $arr
	 * @param array $cur_arr
	 * @return array
	 */
	protected function getPlainMenuArray($arr, $cur_arr) 
	{
		$start_level = 1;
		$level = 1;
		$i = 0;
		
		$tmp_arr = array();
		$prev_arr = array();
		$prev_i = array();
		$levels = array();
		
		$tree_arr = $arr;
		
		while ($i < sizeof($cur_arr) or $level > $start_level)
		{
			$t = $cur_arr[$i];
			$cur_id = $tree_arr[$t][$this->dbId];
			
			$tmp_arr[$cur_id][$this->dbId] = $tree_arr[$t][$this->dbId];
			$tmp_arr[$cur_id]['title'] = $tree_arr[$t]['title'];
			$tmp_arr[$cur_id]['id_template'] = $tree_arr[$t]['id_template'];
			if (isset($tree_arr[$t]['system']))
			{
				$tmp_arr[$cur_id]['system'] = $tree_arr[$t]['system'];
			}
			$tmp_arr[$cur_id]['level'] = $level;
			$tmp_arr[$cur_id][$this->dbParentId] = $tree_arr[$t][$this->dbParentId];
			$tmp_arr[$cur_id][$this->sortField] = $tree_arr[$t][$this->sortField];
			$tmp_arr[$cur_id][$this->uriField] = $tree_arr[$t][$this->uriField];
			$tmp_arr[$cur_id]['levels'] = array_values($levels);
			$tmp_arr[$cur_id]['level_up'] = 0;
			$tmp_arr[$cur_id]['last_in_branch'] = false;
			if (isset($tree_arr[$t]['can_edit']))
			{
				$tmp_arr[$cur_id]['can_edit'] = $tree_arr[$t]['can_edit'];
			}
			$tmp_arr[$cur_id]['can_add'] = $this->isCanAdd($level, $tree_arr[$t]);
			
			$i++;
			if (array_key_exists('children', $tree_arr[$t]) and sizeof($tree_arr[$t]['children']) > 0)
			{
				$tmp_arr[$cur_id]['has_children'] = true;
				array_push($prev_arr, $cur_arr);
				array_push($prev_i, $i);
				
				if ($i < sizeof($cur_arr)) 
				{
					if (!array_key_exists($level, $levels))
					{	
						for ($k = sizeof($levels); $k <= $level; $k++)
						{
							if (!array_key_exists($k, $levels) || !$levels[$k])
							{
								$levels[$k] = 0;
							}
						}
					}
					$levels[$level] = 1;
				}
				
				$cur_arr = array_keys($tree_arr[$t]['children']);
				$i = 0;
				
				$level++;
			} 
			else 
			{
				$tmp_arr[$cur_id]['has_children'] = false;
				$t_id = $tree_arr[$cur_arr[$i - 1]][$this->dbId];
				
				while ($i >= sizeof($cur_arr) and $level > $start_level) 
				{
					$tmp_arr[$tree_arr[$cur_arr[$i - 1]][$this->dbId]]['last_in_branch'] = true;
					$tmp_arr[$t_id]['level_up']++;
					
					$i = array_pop($prev_i);
					$cur_arr = array_pop($prev_arr);
					$level--;
					
					if (!array_key_exists($level, $levels))
					{	
						for ($k = sizeof($levels); $k <= $level; $k++)
						{	
							if (!array_key_exists($k, $levels) || !$levels[$k])
							{	
								$levels[$k] = 0;
							}
						}
					}
					
					$levels[$level] = 0;
				}
				
				if ($i >= sizeof($cur_arr) and $level == 1) 
				{
					$tmp_arr[$tree_arr[$cur_arr[$i - 1]][$this->dbId]]['last_in_branch'] = true;
				}
			}
		}
		return $tmp_arr;
	}

	public function getTreeLastChilds($parent_id)
	{
		$menu = $this->getTree($parent_id, array('status' => 1));
		$menu = array_keys($menu[0]);
		if (empty($menu))
		{
			$menu = array($parent_id);
		}
		
		return $menu;
	}
	
	protected function isCanAdd($level, $item)
	{
		return (int)(($level>$this->maxLevel)?0:(isset($item['can_add'])?$item['can_add']:1));
	}
	
	/**
	 * @param array $categories
	 * @param integer $id_item
	 * @return array
	 */
	public function getPathToRoot($categories, $id_item)
	{
		$path = array();
		
		if (isset($categories[$id_item]))
		{
			$id = $categories[$id_item][$this->dbParentId];
			$path[] = $id_item;
			if ($id) 
			{
				$path = array_merge($path, $this->getPathToRoot($categories, $id));
			}
		}
		return $path;
	}
	
	/**
	 * @param array $categories
	 * @param integer $id_item
	 * @return array
	 */
	public function getItemLevel($id_item)
	{
		$level = 0;
		
		$item = $this->getByID($id_item);
		if($item)
		{
			$parent = $item[$this->dbParentId];
			
			if ($parent != 0)
			{
				$level++;
				$level = $level + $this->getItemLevel($parent);
			}
		}
		
		return $level;
	}
	
	/**
	 * @param integer $id_item
	 * @return integer
	 */
	public function getItemTopParent($id_item)
	{
		$id_parent = $this->getItemParent($id_item);
		
		if ($id_parent == 0)
		{
			return $id_item;
		}
		else 
		{
			return $this->getItemTopParent($id_parent);
		}
	}
	
	/**
	 * @param integer $id_item
	 * @return integer
	 */
	public function getItemParent($id_item)
	{
		$item = $this->getByID($id_item);
		if($item)
		{
			return $item[$this->dbParentId];
		}
		else 
		{
			return 0;
		}
	}
	
	public function getItemUrl($item)
	{
		$path = '';
		
		$item = $this->getByID($item);
		if($item)
		{
			$path .= '/'.$item['uri'];
			$parent = $item[$this->dbParentId];
			if ($parent != 0)
			{
				$path = $this->getItemUrl($parent).$path;
			}
		}
		
		return $path;
	}
	
	/**
	 * @param integer $id1
	 * @param integer $id2
	 * @param array $r_arr
	 * @return bool
	 */
	public function isDescendant($id1, $id2, $r_arr) {
		$t = $id1;
		while (isset($r_arr[$t]) and array_key_exists($this->dbParentId, $r_arr[$t]) and isset($r_arr[$t][$this->dbParentId])) {
			if ($r_arr[$t][$this->dbParentId] == $id2) {
				return true;
			}
			$t = $r_arr[$t][$this->dbParentId];
		}
		return false;
	}
};