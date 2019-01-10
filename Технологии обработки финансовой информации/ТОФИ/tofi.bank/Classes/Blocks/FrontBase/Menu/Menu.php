<?php
class Menu extends FrontBlock
{
	/**
	 * @var Structure
	 */
	protected $Structure;
	
	/**
	 * @var integer
	 */
	protected $parent = 0;
	
	/**
	 * @var integer
	 */
	protected $maxlevel = 1;
	
	/**
	 * @var array
	 */
	protected $whereArray = array('show_in_menu' => 1, 'status' => 1);
	
	/**
	 * @var string
	 */
	protected $prefix = 'm_';
	
	/**
	 * @param string $controlName
	 * @return Menu
	 */
	function __construct()
	{
		parent::__construct();
		
		if(get_class($this) == 'Menu')
		{
			$this->controlerPath = BLOCKS_FRONT_BASE;
		}
		
		$this->Structure = $this->Core->getModel('Structure');
	}

	function initialize($params=array()) 
	{
		$this->tplVars[$this->prefix.'parent'] = $this->parent = (isset($params['parent']) ? $params['parent'] : $this->parent);
		
		$links = $this->Structure->getTree($this->parent, $this->whereArray);
		$links = $links[0];

		$this->tplVars[$this->prefix.'items_count'] = 0;
		$this->tplVars[$this->prefix.'current'] = array();
		$this->tplVars[$this->prefix.'id'] = array();
		$this->tplVars[$this->prefix.'title'] = array();
		$this->tplVars[$this->prefix.'id_template'] = array();
		$this->tplVars[$this->prefix.'level'] = array();
		$this->tplVars[$this->prefix.'next_level'] = array();
		$this->tplVars[$this->prefix.'uri'] = array();
		$this->tplVars[$this->prefix.'counter'] = array();
		foreach ($links as $link) 
		{
			if ($link['level'] <= $this->maxlevel)
			{
				if ($link['id'] == $this->tplVars['current_page_id'])
				{
					$this->tplVars[$this->prefix.'current_section'] = $link['system'];
					$this->tplVars[$this->prefix.'current_section_title'] = $link['title'];
					$this->tplVars[$this->prefix.'current_section_id'] = $link['id'];
					$this->tplVars[$this->prefix.'current'][] = 2;
				}
				elseif ($this->Structure->isDescendant($this->tplVars['current_page_id'], $link['id'], $links))
				{
					$this->tplVars[$this->prefix.'current_section_title'] = $link['title'];
					$this->tplVars[$this->prefix.'current_section'] = $link['system'];
					$this->tplVars[$this->prefix.'current_section_id'] = $link['id'];
					$this->tplVars[$this->prefix.'current'][] = 1;
				}
				else
				{
					$this->tplVars[$this->prefix.'current'][] = 0;
				}

				$this->tplVars[$this->prefix.'id'][] = $link['id'];
				$this->tplVars[$this->prefix.'title'][] = $link['title'];
				$this->tplVars[$this->prefix.'id_template'][] = $link['id_template'];
				$this->tplVars[$this->prefix.'level'][] = $link['level'];
				$this->tplVars[$this->prefix.'next_level'][] = $link['level']+1;
                if ($link['id'] == 1) {
                    $this->tplVars[$this->prefix.'uri'][] = '';
                } else {
                    $this->tplVars[$this->prefix.'uri'][] = preg_replace('/^\//','',$link['uri']);
                }
				$this->tplVars[$this->prefix.'counter'][] = $this->tplVars[$this->prefix.'items_count']+1;

				$this->tplVars[$this->prefix.'items_count']++;
			}
		}
	}
}