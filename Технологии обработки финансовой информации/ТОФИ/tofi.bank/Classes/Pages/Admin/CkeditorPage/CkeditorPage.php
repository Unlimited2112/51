<?php
class CkeditorPage extends AdminPage 
{
	function process()
	{
		require_once Config::$filePath.'js/ckfinder/ckfinder.php';
		$finder = new CKFinder();
		
		$finder->BasePath = '/js/ckfinder/';
		$finder->DisableThumbnailSelection = true;
		$finder->SelectFunction = false;
		$finder->Height = '500';
		
		$this->tplVars['ckeditor'] = $finder->CreateHtml();
	}
}