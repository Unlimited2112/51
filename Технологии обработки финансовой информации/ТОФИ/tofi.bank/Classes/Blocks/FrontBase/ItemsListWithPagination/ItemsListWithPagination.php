<?php
Loader::loadBlock('ItemsList', 'FrontBase');

abstract class ItemsListWithPagination extends ItemsList
{
	/**
	 * @var integer
	 * 
	 */
	public $postsPerPage = null;
	
	/**
	 * @var integer
	 * 
	 */
	protected $pageNum = null;
	
	/**
	 * @var integer
	 * 
	 */
	protected $pagesCount = null;
	
	/**
	 * @var integer
	 * 
	 */
	protected $postsCount = null;
	
	/**
	 * @var string
	 */
	protected $pageUriAdd = 'page/';
	
	protected $pageParramNum = 1;
	
	public function initialize($params=array()) 
	{
		if (empty($this->postsPerPage))
		{
			throw new Exception('postsPerPage is empty!');
		}
		
		$this->getPaginationInfo();
		$this->getPagination();
		
		parent::initialize($params);
	}
	
	protected function getPaginationInfo()
	{
		$this->postsCount = $this->model->getCount($this->whereArray);
		
		$this->pageNum = $this->tplVars[$this->prefix.'current_page'] =UUrl::getParram($this->pageParramNum, 1);
		$this->pagesCount = ceil($this->postsCount/$this->postsPerPage);
		$this->limit = array(max(0, ($this->pageNum-1)*$this->postsPerPage), $this->postsPerPage);
		
		if($this->postsCount)
		{
			if($this->pageNum > $this->pagesCount) 
			{
				Core::redirect($this->tplVars['HTTPl'].$this->tplVars['current_page_url'].$this->pageUriAdd.(ceil($this->postsCount/$this->postsPerPage)).'/');
			}
			elseif ($this->pageNum < 1)
			{
				Core::redirect(UUrl::getCuttedURL(1));
			}
		}
	}
	
	protected function getPagination()
	{
		Loader::loadBlock('Pagination', 'FrontBase');
		$pagination = new Pagination();
		BlocksRegistry::getInstance()->registerBlock('Pagination', $pagination);
		$pagination->paginationCurrentPage = $this->pageNum;
		$pagination->paginationPagesCount = $this->pagesCount;
		$pagination->paginationUrl = $this->tplVars['HTTPl'].$this->tplVars['current_page_url'].$this->pageUriAdd;
	}
}