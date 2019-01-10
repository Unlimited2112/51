<?php
class LocalizerManagementOptions extends ModelOptions {
	
	function __construct($multilanguage){
		parent::__construct();
	}
	
	function Init()
	{
		{ //filters
			$this->vFilters = array(
				array(
					'title'		=>'name',
					'type'		=>'text',
					'event'		=>'like',
					'source'	=>'name',
				),
				array(
					'title'		=>'value',
					'type'		=>'text',
					'event'		=>'like',
					'source'	=>'value',
				)			
			);
		}
		{ // view
			$v_select =array(
				'id_string as id' => '',
				'name'		=> '40%',
				'value'		=> '60%'
			);
			$v_length =array(
				'name'			=> 60,
				'value'			=> 100,
			);

			$this->vSQL ='
				select
					'. implode(',', array_keys($v_select)). '
				from
					wf_loc_strings
				where
			';
			if($this->UserLevel != USER_LEVEL_ARHITECTOR) $this->vSQL.=' name like \'txt_%\'';
			else $this->vSQL.=' 1=1';
	
			$this->vOrder ='name';
	
			foreach($v_select as $k =>$prc){
				if(! (integer) $prc){
					continue;
				}
				$this->vFields[$k] =$k;
				$this->vFieldsPercents[$k] =((integer) $prc). '%';
				if(isset($v_length[$k])){
					$this->vFieldsLength[$k] =$v_length[$k];
				}
			}
			
			$this->vAdd = false;
			$this->vDelete = false;
		}
		
		{ // edit
			$v_select =array(
				'value'		=> array('type' => 'text', 'validator' =>array(
							'isRequired' => true, 
							'minLength' => 1,
							'maxLength' => 200
						)),
			);
			
			foreach($v_select as $k =>$opt){
				$this->eFields[$k] =$opt;
			}
			$this->eTitleField = 'name';
			$this->ControlName ='loc_strings';
		}
	}
}