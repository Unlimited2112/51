<?php
class ActiveTableColumn
{
	public $name;
	public $sort;
	public $width;
	public $length;
	public $function;
	public $wrap;
	public $hidden;
	public $clickable;
	public $noEscape = false;

	function __construct($name, $sort)
	{
		$this->wrap = false;
		$this->clickable = true;

		if (is_null($name))
		{
			$this->hidden = true;
		}
		else 
		{
			if (!is_null($sort))
			{
				$this->sort = strval($sort);
			}
			$this->name = $name;
			$this->hidden = false;
		}
	}

	public function setWidth($width)
	{
		$this->width = strval($width);
	}

	public function setLength($length)
	{
		$this->length = $length;
	}

	public function setFunction($function)
	{
		$this->function = $function;
	}

	public function setWrap($wrap = false)
	{
		$this->wrap = !$wrap;
	}
	
	public function setVisibility($hidden = false)
	{
		$this->hidden = !$hidden;
	}

	public function setClick($clickable = true)
	{
		$this->clickable = (bool)$clickable;
	}
}

class ActiveTable extends Block 
{
	/**
	 * @var DataBase
	 */
	protected $DataBase;
	protected $title;
	protected $query;
	public $columns;
	public $clickLink;
	public $disabledList;
	public $highlightedField;
	public $highlightedBy;
	public $sortable;
	public $sortBy;
	public $sortMode;
	public $empty;
	public $checkable;
	public $emptyMessage;
	public $hideNumber = false;
	
	public $tplVars;
	
	public $template;

    protected $fixedSort = false;
	
	function __construct($objectId, $query, $sortable = array(), $defaultSortBy = null, $defaultSortMode = true, $fixedSort=false)
	{
		parent::__construct();

        $this->fixedSort = $fixedSort;
		
		$this->TplFileName = 'ActiveTable/ActiveTable.tpl';
		$this->controlerPath = BLOCKS_ADMIN_BASE;
		
		$this->DataBase = $this->Core->DataBase;
		$this->tplVars = $this->Core->tplVars;
		
		$this->columns = array();
		$this->query = $query;
		$this->disabledList = null;
		$this->sortable = $sortable;
		
		$count = $this->DataBase->selectCustomSql($this->query)->rowCount();

		$this->tplVars['objectId'] = null;

		if ($count > 0)
		{
			$this->Core->tplVars[$this->objectId.'_active_table_empty'] = false;
			$this->empty = false;

			$sortBy = InPostGetCache('sortBy'.$this->objectId, '', $this->objectId);

            if($this->fixedSort) {
                $this->sortable = array();
                setcookie('jqCookieJar_tablesorter', null, -1, '/');
            }

			if ($sortBy != '' && !$this->fixedSort)
			{
				if (array_key_exists($sortBy, $this->sortable)) $this->sortBy = $sortBy;
				elseif (!is_null($defaultSortBy)) $this->sortBy = $defaultSortBy;

				$sortmode = InPostGetCache('sortMode'.$this->objectId, '', $this->objectId);
				
				if ($sortmode)
				{
					if ($sortmode == 'desc') $this->sortMode = false;
					elseif ($sortmode == 'asc') $this->sortMode = true;
					else $this->sortMode = true;
				} 
				else 
				{
					$this->sortMode = true;
				}

			} 
			elseif (!is_null($defaultSortBy))
			{
				$this->sortBy = $defaultSortBy;
				$this->sortMode = strToBool($defaultSortMode);
			}

			if (!is_null($this->sortBy))
			{
				if (isset($this->sortable[$this->sortBy]))
				{
					$this->query .= ' ORDER BY '.$this->sortable[$this->sortBy];
					if (!$this->sortMode) $this->query .= ' DESC';
				}
				else {
					$this->query .= ' ORDER BY '.$this->sortBy;
					if (!$this->sortMode) $this->query .= ' DESC';
				}
			}
			
			if ($this->sortMode)
			{
				$sortMode = 'asc';
			}
			else
			{
				$sortMode = 'desc';
			}

			SetCacheVar('sortBy'.$this->objectId, $this->sortBy, $this->objectId);
			SetCacheVar('sortMode'.$this->objectId, $sortMode, $this->objectId);
		} 
		else 
		{
			$this->Core->tplVars[$this->objectId.'_active_table_empty'] = true;
			$this->empty = true;
		}
		
		if(isset($this->sortable['sort_id']))
		{
			$this->tplVars['js_sort'] = false; 
		}
		else
		{
			$this->tplVars['js_sort'] = true;
		}
	}
	
	function setCheckable($checkable = true)
	{
		$this->checkable = $checkable;
	}
	
	function setClickLink($link)
	{
		$this->clickLink = $link;
	}

	function setHighlight($field, $value)
	{
		$this->highlightedField = $field;
		$this->highlightedBy = $value;
	}

	function addColumn($name, $sort)
	{
		$id = array_push($this->columns, new ActiveTableColumn($name, $sort));
		return ($id - 1);
	}
	
	function setWidth($id, $width)
	{
		$this->headers[$id]->setWidth($width);
	}

	function setTitle($title)
	{
		$this->title = $title;
	}

	function setDisabledList($disabledList)
	{
		$this->disabledList = $disabledList;
	}

	function initialize($params=array())
	{
		$this->tplVars['hide_number'] = $this->hideNumber;

		if(array_key_exists('title', $params)) {
			$this->setTitle($params['title']);
		}
		
		if(array_key_exists('checkable', $params)) {
			$this->setCheckable(strToBool($params['checkable']));
		}
		
		if(array_key_exists('clicklink', $params)) {
			$this->setClickLink($params['clicklink']);
		}

		if (!$this->empty) 
		{
			$this->tplVars['objectId'] = $this->objectId;
			$this->tplVars['is_empty'] = false;
			$this->tplVars['title'] = $this->title;

			if ($this->checkable) 
			{
				$this->tplVars['checkable'] = true;
			} 
			else
			{
				$this->tplVars['checkable'] = false;
			}
			$count = 0;
			
			foreach ($this->columns as $column) 
			{
				if (!$column->hidden)
				{
					if (!is_null($column->width)) 
					{
						$this->tplVars['column_have_width'][$count] = true;
						$this->tplVars['column_width'][$count] = $column->width;
					} 
					else 
					{
						$this->tplVars['column_have_width'][$count] = false;
					}
	
					if (array_key_exists($column->sort, $this->sortable)) 
					{
						$this->tplVars['column_is_link'][$count] = true;
	
						if (!is_null($this->sortBy) && ($column->sort == $this->sortBy))
						{
							if ($this->sortMode)
							{
								$sortMode = 'desc';
								$this->tplVars['column_sort'][$count] = 'up';
							} 
							else
							{
								$sortMode = 'asc';
								$this->tplVars['column_sort'][$count] = 'down';
							}
						} 
						else
						{
							$sortMode = 'asc';
							$this->tplVars['column_sort'][$count] = false;
						}
	
						$sortBy = $column->sort;

						$this->tplVars['column_link'][$count] = $this->tplVars['redirect_page_url'].'?sortBy'.$this->objectId.'='.$sortBy.'&sortMode'.$this->objectId.'='.$sortMode;
					} 
					else
					{
						$this->tplVars['column_is_link'][$count] = false;
					}
	
					$this->tplVars['column_name'][$count] = $column->name;

					$count++;
				}
			}
			$this->tplVars['columns_count'] = $count;
			$this->tplVars['row_class'] = array();
			$this->tplVars['field_click'] = array();
			$this->tplVars['field_nowrap'] = array();
			$this->tplVars['field_val'] = array();
			
			$row_count = 0;
			
			$res = $this->DataBase->selectCustomSql($this->query);

			foreach($res as $row) {
				$highlight = false;
				
				if (!is_null($this->highlightedField) and ($row[$this->highlightedField] == $this->highlightedBy))
				{
					$highlight = true;
				}
	
				if ($highlight) $this->tplVars['row_class'][$row_count] = 'hl';
				else $this->tplVars['row_class'][$row_count] = 'light';
				
				$id = $row['id'];
					
				if (!is_null($this->clickLink)) 
				{
					$this->tplVars['row_click'][$row_count] = 'gotoURL(\'' . htmlspecialchars($this->clickLink) . $id . '/\');';
				}
				
				if ($this->checkable) 
				{
					$this->tplVars['row_check_val'][$row_count] = $id;
	
					if (!is_null($this->disabledList) && in_array($id, $this->disabledList))
					{
						$this->tplVars['row_check_disabled'][$row_count] = ' disabled=\'disabled\'';
					}
					else
					{
						$this->tplVars['row_check_disabled'][$row_count] = '';
					}
				}
				
				$this->tplVars['field_click'][$row_count] = array();
				$this->tplVars['field_nowrap'][$row_count] = array();
				$this->tplVars['field_val'][$row_count] = array();
				
				foreach ($this->columns as $column) 
				{
					if ($column->clickable) 
					{
						$this->tplVars['field_click'][$row_count][] = $this->tplVars['row_click'][$row_count];
					}
					else
					{
						$this->tplVars['field_click'][$row_count][] = "";
					}
					
					if ($column->wrap)
					{
						$this->tplVars['field_nowrap'][$row_count][] = '';
					}
					else
					{
						$this->tplVars['field_nowrap'][$row_count][] = ' nowrap=\'nowrap\'';
					}
					
					if (!is_null($column->function)) 
					{
						if ($column->noEscape)
						{
							$this->tplVars['field_val'][$row_count][] = call_user_func($column->function, $row[$column->sort]);
						}
						else
						{
							$this->tplVars['field_val'][$row_count][] = htmlspecialchars(call_user_func($column->function, $row[$column->sort]));
						}

					} 
					elseif (strlen($row[$column->sort]) == 0) 
					{
							$this->tplVars['field_val'][$row_count][] = '&nbsp;';

					} 
					else 
					{
						$value = trim($row[$column->sort]);

						if (!is_null($column->length))
						{
							$value = wordwrap($value, $column->length, PHP_EOL, 1);
						}
						if ($column->noEscape)
						{
							$this->tplVars['field_val'][$row_count][] = nl2br($value);
						}
						else
						{
							$this->tplVars['field_val'][$row_count][] = nl2br(htmlspecialchars($value));
						}
					}
				}
				
				$row_count++;
			}
			
			$this->tplVars['row_count'] = $row_count;
		} 
		else 
		{
			$this->tplVars['is_empty'] = true;
			
			if (!is_null($this->emptyMessage))
			{
				$this->tplVars['empty_message'] = $this->emptyMessage;
			}
			else
			{
				$this->tplVars['empty_message'] = $this->Core->Localizer->getString('no_records');
			}
		}
	}
}