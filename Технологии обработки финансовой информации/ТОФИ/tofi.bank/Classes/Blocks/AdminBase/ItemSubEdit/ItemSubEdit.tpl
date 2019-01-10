<p class="hdr1">
	<?=get_text('nav_title_' . $itemName)?>:
</p>
<br />
<?=get_block('ArrayOutput', array(
	'block_begin' => '<div class="info" style="background-color: #ffffff">',
	'block_end' => '</div>',
	'item_begin' => '<span>',
	'item_end' => '</span><br />',
	'array' => 'subedit_info',
))?>
<?=get_block('ArrayOutput', array(
	'block_begin' => '<div class="error" style="background-color: #ffffff">',
	'block_end' => '</div>',
	'item_begin' => '<span>',
	'item_end' => '</span><br />',
	'array' => 'error',
))?>

<?=get_block('form', array(
	'begin' => 'true',
	'name' => 'subedit_show',
	'action' => $redirect_page_url,
))?>
	<table width="1%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="left" valign="top">
				<?=get_block('ActiveTable', array(
					'title' => get_text('nav_title_' . $itemName),
					'enumerated' => 'true',
					'checkable' => 'yes',
					'clicklink' => $redirect_page_url,
					'popuped' => 'no',
				), 'subedit_list')?>
					<?php if(!$_active_table_empty) : ?>
						<div  style="float: left" id="delete_subedit_list">
							<?=get_block('input', array(
								'type' => 'button',
								'name' => 'delete',
								'value' => get_text('button_delete'),
								'confirm' => get_text('conf_sure_delete'),
								'class' => 'butt inact',
								'onmouseover' => "this.className='butt act';",
								'onmouseout' => "this.className='butt inact';",
							))?>
						</div>
					<?php endif; ?>
					<div  style="float: right" id="add_subedit_list">
							<?=get_block('input', array(
								'type' => 'button',
								'name' => 'add',
								'value' => get_text('button_add'),
								'class' => 'butt inact',
								'onmouseover' => "this.className='butt act';",
								'onmouseout' => "this.className='butt inact';",
								'onclick' => "javascript:document.location.href='".$redirect_page_url."add/'",
							))?>
					</div>
		        </td>
		</tr>
	</table>
<?=get_block('form', array('end' => 'true'))?>

<br clear="all" />
<br clear="all" />
<?php if($operation != '') : ?>
	<p class="hdr1">
		<?php if($operation == 'edit') : ?>
		<?=get_text($itemName . '_edit')?>: <?=htmlspecialchars($edTitle)?>
		<?php else : ?>
		<?=get_text($itemName . '_add')?>
		<?php endif; ?>
	</p>
	
	<?=get_block('FormGenerator', array(
		'name' => 'subedit_form',
		'cancel' => $redirect_page_url,
		'form_file_folder' => @$subedit_file_folder,
	))?>
<?php endif; ?>