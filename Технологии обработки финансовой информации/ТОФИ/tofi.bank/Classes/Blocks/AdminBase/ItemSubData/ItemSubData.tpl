<p class="hdr1"><?=get_text('nav_title_' . $itemName)?></p>
<br />
<?=get_block('FormGenerator', array(
	'name' => 'subedit_form',
	'cancel' => $redirect_page_url,
	'form_file_folder' => $subedit_file_folder,
))?>