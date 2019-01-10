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
		</td>
	</tr>
</table>
<?=get_block('form', array('end' => 'true'))?>

<br clear="all" />
<br clear="all" />