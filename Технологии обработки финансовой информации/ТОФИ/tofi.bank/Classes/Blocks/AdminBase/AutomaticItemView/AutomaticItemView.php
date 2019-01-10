<?php
/**
 * 
 * @name		AutomaticItemView
 * @author	EG
 * @version	1.0.0.0 created (EvGo)
 */
class AutomaticItemView extends AdminBlock {

	/**
	 * Current Management Object
	 *
	 * @var Model
	 */
	protected $Item;

	/**
	 * Current Management Options Object
	 *
	 * @var ModelOptions
	 */
	protected $ItemOptions;

	/**
	 * Navigator SQL Statment
	 *
	 * @var string
	 */
	protected $SQL;

	/**
	 * Custom Condition for SQL
	 *
	 * @var string
	 */
	protected $Condition = '';

	/**
	 * Fields Parrams Array
	 *
	 * @var array
	 */
	protected $Fields;

	/**
	 * Fields Percents Array
	 *
	 * @var array
	 */
	protected $FieldsPercents;

	/**
	 * Fields Length Array
	 *
	 * @var array
	 */
	protected $FieldsLength;

	/**
	 * Fields Wrap Array
	 *
	 * @var array
	 */
	protected $FieldsWrap;

	/**
	 * Fields Functions Array
	 *
	 * @var array
	 */
	protected $FieldsFunc;

	/**
	 * Disablet Elements List Array
	 *
	 * @var array
	 */
	protected $DisabletList;

	/**
	 * Order Field Name by Default
	 *
	 * @var string
	 */
	protected $Order;

	/**
	 * Order Type by Default
	 *
	 * @var string
	 */
	protected $OrderType;

	protected $hideNumber;

	/**
	 * Is User Can Delete
	 *
	 * @var bool
	 */
	protected $canDelete;

	/**
	 * Is User Can Add
	 *
	 * @var bool
	 */
	protected $canAdd;

	/**
	 * Navigator Control Name
	 *
	 * @var string
	 */
	protected $viewName;

	/**
	 * Is MultiLanguage Enabled
	 *
	 * @var bool
	 */
	protected $multilanguage;

	/**
	 * Filters
	 * 
	 * @var array
	 */
	protected $Filters;

    protected $fixedSort = false;

	/**
	 * @param string $Item
	 * @param string $tpl
	 * @param bool $UseFullTemplate
	 * @return AutomaticItemView
	 */
	function __construct($Item, $tpl = '', $UseFullTemplate = false) {
		parent::__construct();
		$this->Item = $this->Core->getModel($Item);
		$this->ItemOptions = $this->Item->getOptions();
		$this->multilanguage = &$this->Item->multilanguage;
		$this->TplFileName = $tpl;
		$this->UseFullTemplate = $UseFullTemplate;
	}

	/**
	 * Main Function
	 *
	 * @return parse
	 */
	function process() {
		$this->InitControl();
		
		$this->tplVars['redirect_page_url'] = $this->tplVars['HTTP'] . 'admin/' . $this->Core->currentLangStr .preg_replace(array('/\/\//', '/^\//'), array('/', ''), $this->page_url);
		
		Loader::loadBlock('SubMenu', 'AdminBase');
		BlocksRegistry::getInstance()->registerBlock('SubMenu', new SubMenu($this->page));
		
		$this->InitFilter();
		$this->ProcessFilter();
		$this->AfterProcessFilter();
		$this->ProcessDelete();
		$this->InitActiveTable();
		
		return $this->ProcessControl();
	}

	/**
	 * Init Management Paramms
	 */
	protected function InitControl() {
		$this->canDelete = $this->ItemOptions->Get('vDelete');
		$this->canAdd = $this->ItemOptions->Get('vAdd');
		$this->viewName = $this->ItemOptions->Get('ControlName');
		$this->SQL = $this->ItemOptions->Get('vSQL');
		$this->DisabletList = $this->ItemOptions->Get('vDisabletList');
		$this->Fields = $this->ItemOptions->Get('vFields');
		$this->FieldsPercents = $this->ItemOptions->Get('vFieldsPercents');
		$this->FieldsLength = $this->ItemOptions->Get('vFieldsLength');
		$this->FieldsWrap = $this->ItemOptions->Get('vFieldsWrap');
		$this->FieldsFunc = $this->ItemOptions->Get('vFieldsFunc');
		$this->Order = $this->ItemOptions->Get('vOrder');
		$this->OrderType = $this->ItemOptions->Get('vOrderType');
		$this->hideNumber = $this->ItemOptions->Get('vHideNumber');
		$this->fixedSort = $this->ItemOptions->Get('vFixedSort');
		$this->tplVars['can_delete'] = &$this->canDelete;
		$this->tplVars['can_add'] = &$this->canAdd;
		$this->tplVars['filters_count'] = 0;
	}

	/**
	 * Add filter FUNCTION
	 * @param array(
	 * 	input_type		=> ('text' | 'select' | 'date' | 'selector'),
	 * 	sql_event		=> ('=' | '<' | '>' | '>=' | '<=' | 'like'),
	 * 	sql_field		=> ('string' | array(...)),
	 *  validation		=> array(VRT_CONST, param, param),
	 * 	add				=> ('default_value'),
	 * 	select_arr		=> array(...),
	 * 	concat			=> ('yes' | 'no' | true | false | 0 | 1),
	 * 	mandatory_check	=> ('yes' | 'no' | true | false | 0 | 1),
	 * 	style			=> ('CSS string')
	 * )
	 * 
	 */
	protected function AddFilter() {
		static $defaults = array(
								'title' => array(
														MANDATORY , 
														'TEXT_FIELD'
								) , 
								'type' => array(
														MANDATORY , 
														'ENUMERATION_FIELD' , 
														array(
																			'text' , 
																			'select' , 
																			'date' , 
																			'date' , 
																			'selector'
														)
								) , 
								'event' => array(
														MANDATORY , 
														'ENUMERATION_FIELD' , 
														array(
																			'=' , 
																			'>' , 
																			'<' , 
																			'>=' , 
																			'<=' , 
																			'like'
														)
								) , 
								'source' => array(
														MANDATORY , 
														'SQL_FIELD_EXISTS_FIELD'
								) , 
								
								'add' => array(
													OPTIONAL , 
													'TEXT_FIELD'
								) , 
								'select_arr' => array(
															OPTIONAL , 
															'KEYENUMERATION_FIELD'
								) , 
								'concat' => array(
														OPTIONAL , 
														'ENUMERATION_FIELD' , 
														array(
																			'yes' , 
																			'no' , 
																			true , 
																			false , 
																			1 , 
																			0
														)
								) , 
								'mandatory_check' => array(
																OPTIONAL , 
																'ENUMERATION_FIELD' , 
																array(
																					'yes' , 
																					'no' , 
																					true , 
																					false , 
																					1 , 
																					0
																)
								) , 
								'validation' => array(
															OPTIONAL
								) , 
								'style' => array(
														OPTIONAL , 
														'TEXT_FIELD'
								) , 
								'sql_in' => array(
														OPTIONAL , 
														'TEXT_FIELD'
								) , 
								'sql_table' => array(
															OPTIONAL , 
															'TEXT_FIELD'
								),
								'sql_source' => array(
															OPTIONAL ,
															'TEXT_FIELD'
								)
		);
		
		// checked SQL fields of the current table
		static $sql_approved = array(
		);
		
		// mandatory params
		static $mandatory_array = NULL;
		
		$filters = func_get_args();
		$filters_count = sizeof($this->Filters);
		
		// collect mandatory params
		if (!isset($mandatory_array)) {
			$mandatory_array = array(
			);
			foreach ($defaults as $k => $v) {
				if (isset($v[0]) and $v[0] == MANDATORY and strlen($k)) {
					$mandatory_array[strtolower($k)] = 1;
				}
			}
		}
		
		while (sizeof($filters) > 0) {
			$filter = array_shift($filters);
			
			// create 2d array
			if (!is_array(array_shift(array_values($filter)))) {
				$filter = array(
									$filter
				);
			}
			
			// check filters
			while (sizeof($filter) > 0) {
				// shift first element
				$current_filter = array_shift($filter);
				
				// replaced sql table
				$dbName = (isset($current_filter['sql_table'])) ? $current_filter['sql_table'] : NULL;
				
				// mandatory keys
				$mandatory_check = $mandatory_array;
				
				// check each param
				foreach ($current_filter as $key => $value) {
					// key to lower case
					$lc_key = strtolower($key);
					
					if ($lc_key != $key) {
						unset($current_filter[$key]);
						$current_filter[$lc_key] = $value;
					}
					
					// unset mandatory keys
					unset($mandatory_check[$lc_key]);
					
					// check presented type
					if (!isset($defaults[$lc_key])) {
						throw new Exception('Unknown method ' . htmlentities($key));
					}
					
					// validator params
					$vrt = $defaults[$lc_key];
					
					if (isset($vrt) and isset($vrt[0])) {
						
						// MANDATORY TYPE and null-length value
						if ($vrt[0] == MANDATORY and ((is_array($value) and !$value) or ((is_string($value) or is_numeric($value)) and !strlen($value)))) {
							throw new Exception('Empty value for requared field ' . htmlentities($key));
						}
						if ($vrt[0] == MANDATORY or ($vrt[0] == OPTIONAL and !empty($value))) {
							if (isset($vrt[1]) and is_numeric($vrt[1])) {
								switch ($vrt[1]) {
									case 'TEXT_FIELD':
										if (isset($value) and !(is_numeric($value) or is_string($value))) {
											throw new Exception('Value for ' . htmlentities($key) . ' are not text');
										}
										break;
									
									case 'ENUMERATION_FIELD':
										if (isset($vrt[2])) {
											if (is_array($vrt[2]) and !in_array(strtolower($value), $vrt[2])) {
												throw new Exception('Incorrec value for ' . htmlentities($key) . ' (' . htmlentities($value) . '), allowed values: ' . implode(',' . PHP_EOL, $vrt[2]));
											}
										} else {
											if (!is_array($value) or (is_array($value) and !array_values($value))) {
												throw new Exception('List for ' . htmlentities($key) . ' this is not Enumeration');
											}
										}
										break;
									
									case 'SQL_FIELD_EXISTS_FIELD':
										if (!is_array($value)) {
											$current_filter[$key] = array(
																					$value
											);
											$value = array(
																	$value
											);
										}
										foreach ($value as $field) {
											if (!isset($sql_approved[$field])) {
												$query = $this->DataBase->selectCustomSql('SHOW FIELDS FROM `' . (isset($dbName) ? $dbName : $this->Item->dbName) . '` LIKE \'' . $this->DataBase->likeEscape($field) . '\'');
												$sql_approved[$field] = $query->rowCount();
											}
											if (!$sql_approved[$field]) {
												throw new Exception('Undefined field ' . htmlentities($field) . ', for element ' . $key);
											}
										}
										break;
									
									case 'KEYENUMERATION_FIELD':
										if (isset($vrt[2])) {
											if (is_array($vrt[2]) and !in_array(strtolower($value), array_keys($vrt[2]))) {
												throw new Exception('Incorrec value for ' . htmlentities($key) . ' (' . htmlentities($value) . '), allowed values: ' . implode(',' . PHP_EOL, $vrt[2]));
											}
										} else {
											if (!is_array($value) or (is_array($value) and !(array_values($value) or array_keys($value)))) {
												throw new Exception('List for ' . htmlentities($key) . ' this is not Enumeration');
											}
										}
										break;
								}
							}
						}
					}
				}
				
				if ((!isset($current_filter['mandatory_check']) or (isset($current_filter['mandatory_check']) and (strtolower($current_filter['mandatory_check']) != 'no' or strtolower($current_filter['mandatory_check'])))) and sizeof($mandatory_check)) {
					throw new Exception('Empty values for mandatory fields. List: ' . implode(',' . PHP_EOL, array_keys($mandatory_check)));
				}
				// add current filter to filters list
				$this->Filters[$filters_count][] = $current_filter;
			}
			
			// apply filters index
			$filters_count = sizeof($this->Filters);
		}
	}

	/**
	 * Process Filter
	 * @author D.Gulin
	 *
	 */
	protected function ProcessFilter() {
		$this->tplVars['filters_count'] = 0;
		$this->tplVars['is_search'] = false;
		$this->tplVars['date_input_pasted'] = false;
		$this->tplVars['item_search_elements_count'] = 0;
		$this->tplVars['item_selector_elements_count'] = 0;
		
		if (!$this->Filters) {
			return;
		}
		
		// search form
		$clear = Form::isSubmited('item_search_form', 'clear');
		$submit = Form::isSubmited('item_search_form');
		
		// selector form
		$s_submit = Form::isSubmited('item_selector_form');
		
		while (sizeof($this->Filters) > 0) {
			// shift first element
			$filters = array_shift($this->Filters);
			
			// SQL Condition
			$condition = '';
			
			// get filters count
			$filters_count = & $this->tplVars['filters_count'];
			
			// reset counter
			$this->tplVars['subfilters_count'][$filters_count] = 0;
			
			// get subfilters count
			$subfilters_count = & $this->tplVars['subfilters_count'][$filters_count];
			
			// set search title
			$this->tplVars['search_group_title'][] = (sizeof($filters) > 1) ? $filters[0]['source'] : $filters[0]['title'];
			
			// hide group title
			$this->tplVars['hide_group_elements'][] = false;
			
			// clear concat variable
			$concat = '';
			
			// sql in selector
			$sql_in = (isset($filters[0]['sql_in'])) ? $filters[0]['sql_in'] : NULL;
			
			while (sizeof($filters) > 0) {
				// shift first element
				$filter = array_shift($filters);
				
				foreach ($filter as $key => $value) {
					switch ($lc_key = strtolower($key)) {
						case 'source':
							$selector = (strtolower($filter['type']) == 'selector');
							$single_value = strtolower($filter['title']) . '_' . (is_array($filter['source']) ? $filter['source'][0] : $filter['source']);
							$name = 'search_' . $single_value;
							$default = (isset($filter['add'])) ? $filter['add'] : ((isset($filter['select_arr'])) ? array_shift(array_keys($filter['select_arr'])) : '');
							$cache_id = (isset($filter['sql_table']) and $filter['sql_table']) ? $filter['sql_table'] : $this->Item->dbName;

							if ((!$selector and $submit) or ($selector and $s_submit)) {
								SetCacheVar($name, InPost($name), $cache_id);
							}
							else if ($clear and !$selector) {
								SetCacheVar($name, $default, $cache_id);
							}
							elseif($selector && $submit) {
								SetCacheVar($name, InPost($name, $default), $cache_id);
							}

							// fix bug with deleted element of select_arr
							if ($selector and isset($filter['select_arr']) and !in_array(InCache($name, $default, $cache_id), array_keys($filter['select_arr']))) {
								SetCacheVar($name, array_shift(array_keys($filter['select_arr'])), $cache_id);
							}

							// get value
							$item = InCache($name, "", $cache_id);

							if (!strlen($item)) {
								SetCacheVar($name, $default, $cache_id);
								$item = $default;
							}
							
							// check A < B, if A with ('>' | '>=') event and B with ('<' | '<=')
							if (isset($filters[0])) {
								if (preg_match('/^\>=?$/', $filter['event']) and preg_match('/^\<=?$/', $filters[0]['event'])) {
									
									$next_selector = (strtolower($filters[0]['type']) == 'selector');
									$next_single_value = strtolower($filters[0]['title']) . '_' . $filters[0]['source'][0];
									$next_name = 'search_' . $next_single_value;
									$next_default = (isset($filters[0]['add'])) ? $filters[0]['add'] : ((isset($filters[0]['select_arr'])) ? array_shift(array_keys($filters[0]['select_arr'])) : '');
									
									if ((!$next_selector and $submit) or ($next_selector and $s_submit)) {
										SetCacheVar($next_name, InPost($next_name), $cache_id);
									} else if ($clear and !$next_selector) {
										SetCacheVar($next_name, $next_default, $cache_id);
									}
									
									// get value
									$next_item = InCache($next_name, $next_default, $cache_id);
									if (!strlen($next_item)) {
										$next_item = $next_default;
									}
									
									if (strlen($item) and strlen($next_item) and $item > $next_item) {
										$group_title = 'filter_by_' . array_pop(array_values($this->tplVars['search_group_title']));
										$this->tplVars['error_view'][] = $this->Localizer->getString('a_greater_then_b_error', $this->Localizer->getString($filter['title']), $this->Localizer->getString($filters[0]['title']), $this->Localizer->getString($group_title));
									}
								}
							}
							
							// create SQL condition
							if (strlen($item)) {
								if ((!preg_match('/^select(or)?$/i', $filter['type']) or (preg_match('/^select(or)?$/i', $filter['type']) and $item > -1))) {
									$sql = (($concat and $condition) ? $concat : '');
									
									$where = '';
									if (!is_array($value)) {
										$value = array(
																$value
										);
									}
									foreach ($value as $field) {
										$where .= (($where) ? ' or ' : '');
										if(isset($filter['sql_source'])) {
											$where .= $filter['sql_source'] . ' ' . $filter['event'] . ' ';
										}
										else {
											$where .= $field . ' ' . $filter['event'] . ' ';
										}
										//HACK FOR BLOG
										if ($filter['type'] == 'date') {
											if ($filter['event'] == '<=') {
													$where .= $this->DataBase->quote($item . ' 23:59:59'); 
											} else {
												$where .= $this->DataBase->quote($item . ' 00:00:00'); 
											}
										}
										else {
											if($filter['event'] == 'like') {
												$where .= "'%" . $this->DataBase->likeEscape($item) . "%'";
											}
											elseif($filter['event'] == 'in') {
												$where .= $item;
											}
											else {
												$where .= $this->DataBase->quote($item);
											}
										}
									}
									if ($where) {
										$sql .= (sizeof($value) > 1) ? '(' . $where . ')' : $where;
									}
									
									// local condition
									$condition .= $sql;
									
									if ($sql and !$selector and $item != $default) {
										$this->tplVars['is_search'] = true;
									}
								} else {
									//$this->tplVars['is_search'] =true;
								}
							}
							
							// is not first
							$this->tplVars['is_not_first_item'][$filters_count][] = ($subfilters_count != 0);
							
							// set name
							$this->tplVars['input_name'][$filters_count][] = $single_value;
							
							// set value
							$this->tplVars['input_value'][$filters_count][] = $item;
							
							// set value
							$this->tplVars[$name] = ($clear and !$selector) ? $default : $item;
							
							// add style
							$this->tplVars['input_style'][$filters_count][] = (isset($filter['style'])) ? 'style=\'' . $filter['style'] . '\'' : '';
							
							break;
						
						case 'validation':
							$single_value = 'search_' . strtolower($filter['title']) . '_' . (is_array($filter['source']) ? $filter['source'][0] : $filter['source']);
							switch (sizeof($value)) {
								/*case 1:{ Validator::add_nr($single_value, $value[0]); break; }
								case 2:{ Validator::add_nr($single_value, $value[0],$value[1]); break; }
								case 3:{ Validator::add_nr($single_value, $value[0],$value[1],$value[2]); break; }*/
							}
							break;
						
						case 'type':
							if ($value == 'date') {
								$this->tplVars['date_input_pasted'] = TRUE;
							}
							if (strtolower($value) == 'selector') {
								$this->tplVars['item_selector_elements_count']++;
							} else {
								$this->tplVars['item_search_elements_count']++;
							}
							
							$this->tplVars['input_type'][$filters_count][] = strtolower($value);
							break;
						
						case 'title':
							
							$this->tplVars['input_title'][$filters_count][] = strtolower($value);
							break;
						
						case 'select_arr':
							$single_value = strtolower($filter['title']) . '_' . (is_array($filter['source']) ? $filter['source'][0] : $filter['source']);
							Input::setSelectData('search_' . $single_value, $value);
							break;
					
					}
				}

				// concat string
				$concat = (isset($filter['concat']) and (strtolower($filter['concat']) == 'yes' or $filter['concat'])) ? ' and ' : ' or ';
				
				// increment subfilters count
				$subfilters_count++;
			}

			// global condition
			if ($condition) {
				if (isset($sql_in)) {
					$this->Condition .= ' AND (' . str_replace('%filter%', $condition, $sql_in) . ')';
				} else {
					$this->Condition .= ' AND (' . $condition . ')';
				}
			}
			
			// increment filters count
			$filters_count++;
		}
		
		if($s_submit or $clear or $submit)
		{
			Core::redirect($this->tplVars['redirect_page_url']);
		}
	}

	/**
	 * Custom Init Filter Function. Executed after InitControl() function.
	 */
	protected function InitFilter() {
		foreach ($this->ItemOptions->vFilters as $filter) {
			$this->AddFilter($filter);
		}
	}

	/**
	 * Custom After Process Filter Function. Executed after ProcessFilter() function.
	 */
	protected function AfterProcessFilter() {
		return true;
	}

	protected function ProcessDelete() {
		if($this->Item->sequence and $this->ItemOptions->isSequenceAlloved())
		{
			$this->Item->SequenceController->UnickArr = $this->ItemOptions->getSequenceUnicArr();
		}
		if ((Form::isSubmited('show_item_form', 'delete')) && ($this->canDelete)) {
			$data = InGetPost('checkboxes', '');
			if ((is_array($data)) && (sizeof($data))) {
				$ok = true;
				foreach ($data as $k => $v) {
					if (!$this->Item->deleteItem($v)) {
						$this->tplVars['error_view'][] = $this->Item->getLastError();
						$ok = false;
						break;
					}
				}
				if ($ok)
				{
					$this->tplVars['info_view'][] = $this->Localizer->getString('data_updated');
				}
				if($this->Item->sequence)
				{
					$this->Item->SequenceController->recalculate();
				}
			}
			else
			{
				$this->tplVars['error_view'][] = $this->Localizer->getString('no_selected_items');
			}
		}
	}

	/**
	 * Init Navigator Paramms
	 */
	protected function InitActiveTable() 
	{
		
		if($this->Item->sequence and $this->ItemOptions->isSequenceAlloved())
		{
			$count = $this->Item->SequenceController->getMax();
			
			SetCacheVar("SequenceMaxValue", $count, "SequenceController");	
			
			if (InGet("seq_up",1) > 1 && InGet("seq_up",1) <= $count)
			{
				$this->Item->SequenceController->moveUp(InGet("seq_up",1));
				Core::redirect($this->tplVars['redirect_page_url']);
			}
			elseif (InGet("seq_down",0) < $count and InGet("seq_down",0) >= 1)
			{
				$this->Item->SequenceController->moveDown(InGet("seq_down",1));
				Core::redirect($this->tplVars['redirect_page_url']);
			}			
		}
		
		if ($this->multilanguage) {
			$this->Condition .= ' AND id_lang = \'' . $this->Core->currentLang . '\'';
		}
		
		Loader::loadBlock('ActiveTable', 'AdminBase');
		$table = new ActiveTable($this->viewName, $this->SQL . $this->Condition, $this->Fields, $this->Order, $this->OrderType, $this->fixedSort);
		$table->hideNumber = $this->hideNumber;
		BlocksRegistry::getInstance()->registerBlock('ActiveTable', $table, $this->viewName);
		
		if($this->Item->sequence and $this->ItemOptions->isSequenceAlloved())
		{
			$table->sortable = array();
		}
		
		foreach ($this->FieldsPercents as $k => $v) {
			$columnId = $table->addColumn($this->Localizer->getString($k), $k);
			$table->columns[$columnId]->setWidth($v);
			if ((isset($this->FieldsLength[$k])) & (!empty($this->FieldsLength[$k])))
				$table->columns[$columnId]->setLength($this->FieldsLength[$k]);
			if ((isset($this->FieldsWrap[$k])) & (!empty($this->FieldsWrap[$k]))) {
				$table->columns[$columnId]->setWrap($this->FieldsWrap[$k]);
			}
			if ((isset($this->FieldsFunc[$k])) & (!empty($this->FieldsFunc[$k])))
			{
				$table->columns[$columnId]->noEscape = true;
				$table->columns[$columnId]->setFunction($this->FieldsFunc[$k]);
			}
			if($this->Item->sequence and $this->ItemOptions->isSequenceAlloved() and $this->Item->SequenceController->SortIdField == $k)
			{
				$table->columns[$columnId]->noEscape = true;
				$table->columns[$columnId]->clickable = false;
			}
		}
		$table->disabledList = $this->DisabletList;
		
		if (arr_val($this->page_params, '2')) {
			$table->setHighlight('id', $this->page_params['2']);
		}
	}

	function ProcessControl() {
		if ($this->UseFullTemplate) {
			ob_start();
			extract($this->tplVars, EXTR_SKIP or EXTR_REFS);
			include(BLOCKS_ADMIN . $this->TplFileName);
			return ob_get_clean();
		} else {
			ob_start();
			extract($this->tplVars, EXTR_SKIP or EXTR_REFS);
			include(BLOCKS_ADMIN_BASE . 'AutomaticItemView/AutomaticItemView.tpl');
			$this->tplVars['view_form'] = ob_get_clean();
			
			if ($this->TplFileName == '')
			{
				return $this->tplVars['view_form'];
			}
			else
			{
				ob_start();
				extract($this->tplVars, EXTR_SKIP or EXTR_REFS);
				include(BLOCKS_ADMIN . $this->TplFileName);
				return ob_get_clean();
			}
		}
	}
}