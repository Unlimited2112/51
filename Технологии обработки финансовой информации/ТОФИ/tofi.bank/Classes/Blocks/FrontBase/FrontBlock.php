<?php
abstract class FrontBlock extends BaseBlock
{
	/**
	 * @var FrontPage
	 */	
	public $page;
	
	/**
	 * @var string
	 */
	protected $controlerPath = BLOCKS_FRONT;
}