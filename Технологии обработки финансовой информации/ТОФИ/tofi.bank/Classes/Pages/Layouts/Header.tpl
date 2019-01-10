<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cs" lang="cs">
    <head>
		<title><?=get_block('PageTitle', array('current_page_id' => $current_page_id, 'page_title' => htmlspecialchars(@$scv_meta_title)))?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
		<link rel="stylesheet" type="text/css" href="<?=$CSS?>screen.css" media="all" />
		<script type="text/javascript" src="<?=$JS?>jquery.js"></script>
		<script type="text/javascript" src="<?=$JS?>jquery.jcarousel.min.js"></script>
		<script type="text/javascript" src="<?=$JS?>jquery.cycle.all.min.js"></script>
		<script type="text/javascript" src="<?=$JS?>cms/apps/jquery.cookie.js"></script>
		<script type="text/javascript" src="<?=$JS?>cufon.js"></script>
		<script type="text/javascript" src="<?=$JS?>cufon-config.js"></script>
		<script type="text/javascript" src="<?=$JS?>font.js"></script>
		
		<link href="/css_pirobox/style_5/style.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="<?=$JS?>jquery_1.5-jquery_ui.min.js"></script>
		<script type="text/javascript" src="<?=$JS?>pirobox_extended_feb_2011.js"></script>
<link rel="icon" type="image/png" href="/favicon.png" />
		<script type="text/javascript" src="<?=$HTTP?>js/cms/apps/jquery.validate/jquery.form.js"></script>
		<script type="text/javascript" src="<?=$HTTP?>js/cms/apps/jquery.validate/jquery.metadata.js"></script>
		<script type="text/javascript" src="<?=$HTTP?>js/cms/apps/jquery.validate/jquery.validate.js"></script>
    </head>
    <body>
        <div id="header">
            <div class="wrap">
                <!-- Logo start -->
                  <a id="logo" href="<?=$HTTP?>" title="blackdiamond"><strong><?=htmlspecialchars($site_name)?></strong></a>
                <!-- Logo end -->
                <hr class="noscreen noprint" />
       			<?='';/*get_block('TopMenu', array('parent' => 0))*/?>
            </div>
        </div>
        <hr class="noscreen" />