<?php
class MutiInput extends Block
{
	/**
	 * @var Input
	 */
	private $input;
	
	protected $params;
	public function initialize($params=array()) {
		$this->params = $params;
	}
	
	function process() {
		$params = $this->params;
		
		if(func_num_args() > 0) 
		{
			$loop = func_get_arg(0);
		}
		else
		{
			$loop = 0;
		}
		
		$name = preg_replace('/\[\]/', '', isset($params['name']) ? $params['name'] : '');
		$type = strtolower(isset($params['type']) ? $params['type'] : '');
		$priority = explode(',', isset($params['priority']) ? $params['priority'] : 'post,get,template');
		
		if ($type == "multi_select")
		{
			$type = "select";
		}
		
		$inputTypeClass = 'Input'.ucfirst($type);
		
		if(Loader::loadBlock($inputTypeClass, 'Form/Input'))
		{
			$this->input = new $inputTypeClass($name, $priority, $loop, $type, $params);
			return $this->input->process();
		}
		else 
		{
			return 'Ошибочный тип контроллера ввода "'.$type.'"';
		}
	}
}