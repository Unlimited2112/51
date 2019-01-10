<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>WYSIWYG</title>
	<meta content="text/html; charset=utf-8" http-equiv="content-type"/>
	<script type="text/javascript">
	<!--
		var openerArea='<?=$_GET["id"];?>';
	//-->
	</script>
</head>
<body style="margin: 0px; background: white; padding: 0px">
	<form name="cform" method="post" onsubmit="return false;" action="#" >
		<?php
			// Include CKEditor class.
			include_once "ckeditor.php";
			include_once '../ckfinder/ckfinder.php';

			$ckeditor = new CKEditor();
			$ckeditor->basePath = '/js/ckeditor/';
			$ckeditor->addEventHandler('instanceReady', 'function (evt) {
				evt.editor.setData(opener.CKEDITOR.instances.'.$_GET["id"].'.getData());
			}');
			
			$ckfinder = new CKFinder();
			$ckfinder->BasePath = '/js/ckfinder/'; // Note: BasePath property in CKFinder class starts with capital letter
			$ckfinder->SetupCKEditorObject($ckeditor);
			
			$ckeditor->editor("CKfulleditor", "");
		?>
	</form>
</body>
</html>