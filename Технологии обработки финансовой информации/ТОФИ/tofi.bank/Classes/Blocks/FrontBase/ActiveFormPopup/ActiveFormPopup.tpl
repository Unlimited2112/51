<?=get_block('FormGenerator', array(
	'name' => $apply_form_name,
	'action' => $redirect_page_url,
	'onsubmit' => "$('div.post-msg > label').remove()",
	'target' => "frame-".$apply_form_name,
))?>
<iframe id="frame-<?=$apply_form_name?>" style="display:none" name="frame-<?=$apply_form_name?>"></iframe>