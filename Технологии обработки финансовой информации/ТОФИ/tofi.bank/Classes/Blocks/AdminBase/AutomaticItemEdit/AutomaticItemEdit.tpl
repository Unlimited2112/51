<p class="hdr1">
	<?php if($operation == 'edit') : ?>
	<?=get_text($ItemSmallName . '_edit')?>: <?=htmlspecialchars($edTitle)?>
	<?php else : ?>
	<?=get_text($ItemSmallName . '_add')?>
	<?php endif; ?>
</p>
<?=get_block('FormGenerator', array(
	'name' => 'item_edit_form',
	'cancel' => $redirect_page_url,
	'form_file_folder' => @$aie_f_f,
))?>