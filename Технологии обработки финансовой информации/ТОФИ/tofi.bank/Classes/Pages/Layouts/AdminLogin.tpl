<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?=get_text('administrative_suite')?> <?=$site_shortname?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	
	<link rel="stylesheet" href="<?=$CSS?>cms/global.css" type="text/css" />
	<!--[if lt IE 7]><link rel="stylesheet" type="text/css" href="<?=$CSS?>cms/ie6.css" /><![endif]-->

	<script type="text/javascript" src="<?=$HTTP?>js/cms/core/jquery.js"></script>
	<script type="text/javascript" src="<?=$HTTP?>js/cms/core/jquery.ui.js"></script>
	<script type="text/javascript" src="<?=$HTTP?>js/cms/apps/jquery.cookie.js"></script>
	<script type="text/javascript" src="<?=$HTTP?>js/cms/apps/jquery.validate/jquery.form.js"></script>
	<script type="text/javascript" src="<?=$HTTP?>js/cms/apps/jquery.validate/jquery.metadata.js"></script>
	<script type="text/javascript" src="<?=$HTTP?>js/cms/apps/jquery.validate/jquery.validate.js"></script>
</head>
<body>
	<div style="padding-top:200px;padding-bottom:200px;">
		<center>
			<div>
				<?=get_block('FormGenerator', array(
					'name' => 'login_form',
					'action' => $HTTP.'admin/',
					'submit_title' => 'button_login',
				))?>
			</div>
		</center>
	</div>
</body>
</html>