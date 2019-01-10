<?php
class ArrayOutput extends Block
{
	protected $params;
	public function initialize($params=array()) {
		$this->params = $params;
	}
	
	function generateOutput($array, $block_begin, $block_end, $item_begin, $item_end, $add_block)
	{
		$output = '';
		$arr = array();
		
		if (isset($this->Core->tplVars[$array]))
		{
			if (is_array($this->Core->tplVars[$array]))
			{
				$arr = $this->Core->tplVars[$array];
			}
			else
			{
				$arr = array($this->Core->tplVars[$array]);
			}
		}
		
		foreach ($arr as $v)
		{
			$output .= $item_begin . htmlspecialchars($v) . $item_end;
		}
		
		if ( ( ($add_block == '') && ($output != '') ) || ($add_block == 'true') )
		{
			$output = $block_begin . $output . $block_end;
		}
		
		return $output;
	}

	function process() {
		$params = $this->params;
		
		$array = isset($params['array']) ? $params['array'] : '';
		$block_begin = isset($params['block_begin']) ? $params['block_begin'] : '';
		$block_end = isset($params['block_end']) ? $params['block_end'] : '';
		$item_begin = isset($params['item_begin']) ? $params['item_begin'] : '';
		$item_end = isset($params['item_end']) ? $params['item_end'] : '';
		$add_block = isset($params['add_block']) ? $params['add_block'] : '';

		return $this->generateOutput($array, $block_begin, $block_end, $item_begin, $item_end, $add_block);
	}
}