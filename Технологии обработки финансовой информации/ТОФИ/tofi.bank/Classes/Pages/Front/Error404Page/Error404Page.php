<?php
class Error404Page extends FrontPage  
{
	protected function initialize() {
		parent::initialize();
		@header('HTTP/1.1 404 Not Found');
		@header('Status: 404 Not Found');
	}
}