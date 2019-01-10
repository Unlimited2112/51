<?php
class RedirectPage extends FrontPage 
{
	protected function initialize()	
	{
		$this->tplVars += $this->Structure->getContentValues($this->tplVars['current_page_id']);
		Core::redirect($this->tplVars['scv_redirect_link']);
	}
}