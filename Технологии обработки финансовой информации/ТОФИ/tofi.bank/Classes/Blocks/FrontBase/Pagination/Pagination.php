<?php
class Pagination extends FrontBlock
{
	/**
	 * @var integer
	 */
	public $paginationPagesCount = 1;
	
	/**
	 * @var integer
	 */
	public $paginationCurrentPage = 1;
	
	/**
	 * @var string
	 */
	public $paginationUrl = '/page/';
	
	/**
	 * @var string
	 */
	public $paginationUrlEnd = '/';
	
	function __construct()
	{
		parent::__construct();
		$this->controlerPath = BLOCKS_FRONT_BASE;
	}

	function initialize($params=array()) 
	{
		$this->tplVars['pagination_current'] = $this->paginationCurrentPage;
		
		$this->tplVars['pagination_count'] = 0;
		$this->tplVars['pagination_pages'] = array();
		$this->tplVars['pagination_pages_url'] = array();
		
		for ($i=1;$i<=$this->paginationPagesCount;$i++)
		{
			$this->tplVars['pagination_pages'][] = $i;
			$this->tplVars['pagination_pages_url'][] = $this->paginationUrl.$i.$this->paginationUrlEnd;
			$this->tplVars['pagination_count']++;
		}
		
		if ($this->paginationPagesCount > $this->paginationCurrentPage)
		{
			$this->tplVars['pagination_next_url'] = $this->paginationUrl.($this->paginationCurrentPage+1).$this->paginationUrlEnd;
		}
		else 
		{
			$this->tplVars['pagination_next_url'] = "";
		}
		
		if ($this->paginationCurrentPage > 1)
		{
			$this->tplVars['pagination_prev_url'] = $this->paginationUrl.($this->paginationCurrentPage-1).$this->paginationUrlEnd;
		}
		else 
		{
			$this->tplVars['pagination_prev_url'] = "";
		}
	}
}