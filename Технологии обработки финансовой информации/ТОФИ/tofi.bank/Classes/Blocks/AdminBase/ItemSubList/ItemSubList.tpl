<?=get_block('ArrayOutput', array(
	'block_begin' => '<div class="info" style="background-color: #ffffff">',
	'block_end' => '</div>',
	'item_begin' => '<span>',
	'item_end' => '</span><br />',
	'array' => 'subedit_info',
))?>
<?=get_block('form', array(
	'begin' => 'true',
	'name' => 'subedit_form',
	'method' => 'post',
	'enctype' => 'multipart/form-data',
	'action' => $redirect_page_url,
))?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding-left: 20px">
	<?php for($i = 0; $i < $lists; $i++) : ?>
		<tr>
			<td width="99%" nowrap="nowrap"><?=$list_title[$i]?> &nbsp;</td>
			<td width="1%">
				<?=get_block('input', array(
					'type' => 'checkbox',
					'name' => 'list_' . $list_id[$i],
					'value' => '1',
					'priority' => 'template,post,get',
				))?>
			</td>
		</tr>
	<?php endfor; ?>
</table>
<br />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="right">
			<?=get_block('input', array(
			  	'type' => 'submit',
			  	'name' => 'save',
			  	'id' => 'save',
			  	'value' => get_text('button_save'),
			  	'class' => 'butt inact',
			  	'onmouseover' => "this.className='butt act';",
			  	'onmouseout' => "this.className='butt inact';",
			  ))?>
          </td>
        </tr>
  </table>
<?=get_block('form', array('end' => 'true'))?>
<br />