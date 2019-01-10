<?=get_block('form', array(
	'begin' => 'true',
	'name' => $form_name,
	'method' => 'post',
	'enctype' => 'multipart/form-data',
	'action' => $form_action,
))?>
	<table border="0" cellspacing="5" cellpadding="0" class="form">
	<?=get_block('ArrayOutput', array(
		'block_begin' => '<tr><td><p class="info">',
		'block_end' => '</p></td></tr>',
		'item_begin' => '<span>',
		'item_end' => '</span><br />',
		'array' => 'info',
	))?>
	<?=get_block('ArrayOutput', array(
		'block_begin' => '<tr><td><p class="comment">',
		'block_end' => '</p></td></tr>',
		'item_begin' => '<span>',
		'item_end' => '</span><br />',
		'array' => 'comments',
	))?>
	<?=get_block('ArrayOutput', array(
		'block_begin' => '<tr><td><p class="error">',
		'block_end' => '</p></td></tr>',
		'item_begin' => '<span>',
		'item_end' => '</span><br />',
		'array' => 'error',
	))?>
	
	<?php for($i = 0; $i < $fields_count; $i++) : ?>
		<?php if($field_type[$i] == 'hidden') : ?>
			<?=get_block('input', array(
				'type' => $field_type[$i],
				'name' => $field_name[$i],
			))?>
		<?php else : ?>
			<tr>
				<td class="left" valign="top">
					<?php if($field_type[$i] != 'checkbox') : ?>
						<strong><?=get_text($field_name[$i])?>:</strong>
						<?php if($field_required[$i]) : ?>
							<span class="red">*</span>
						<?php endif; ?>
						<? if(in_array($field_name[$i], array('performer_id', 'tester_id'))) : ?>
						<strong id="pay-method-<?=$field_name[$i]?>"><? echo get_pay_methods($field_value[$i]); ?></strong>
						<? endif; ?>
						<br clear="all" />
					<?php endif; ?>
					
					<?php if($field_type[$i] == 'text' or $field_type[$i] == 'password' or $field_type[$i] == 'select') : ?>
						<? if(in_array($field_name[$i], array('performer_id', 'tester_id'))) : ?>
						<?=get_block('input', array(
							'type' => $field_type[$i],
							'name' => $field_name[$i],
							'class' => 'inp inp2',
							'onchange' => "$('#pay-method-".$field_name[$i]."').html('');",
						))?>
						<? else : ?>
						<?=get_block('input', array(
							'type' => $field_type[$i],
							'name' => $field_name[$i],
							'class' => 'inp inp2',
						))?>
						<? endif; ?>
					<?php endif; ?>
					<?php if($field_type[$i] == 'file') : ?>
						<?=get_block('input', array(
							'type' => $field_type[$i],
							'name' => $field_name[$i],
							'browser' => $field_option[$i],
							'class' => 'inp inp2',
						))?>
					<?php endif; ?>
					<?php if($field_type[$i] == 'multi_select') : ?>
						<?=get_block('input', array(
							'type' => "select",
							'name' => $field_name[$i],
							'class' => 'inp inp2',
							'multiple' => "multiple",
						))?>
					<?php endif; ?>
					<?php if($field_type[$i] == 'date') : ?>
						<?=get_block('input', array(
							'type' => $field_type[$i],
							'name' => $field_name[$i],
						))?>
					<?php endif; ?>
					<?php if($field_type[$i] == 'html') : ?>
						<?=get_block('input', array(
							'type' => "textarea",
							'name' => $field_name[$i],
							'id' => $field_name[$i],
							'style' => "width:100%",
							'rows' => "25",
						))?>
					<?php endif; ?>
					<?php if($field_type[$i] == 'textarea') : ?>
						<?=get_block('input', array(
							'type' => "textarea",
							'name' => $field_name[$i],
							'id' => $field_name[$i],
							'rows' => "10",
							'class' => "inp inp3",
						))?>
					<?php endif; ?>
					<?php if($field_type[$i] == 'checkbox') : ?>
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="1%" nowrap="nowrap"><strong><?=get_text($field_name[$i])?> &nbsp;</strong></td>
								<td width="99%">
									<?=get_block('input', array(
										'type' => "checkbox",
										'name' => $field_name[$i],
										'value' => '1',
									))?>
								</td>
							</tr>
						</table>		
					<?php endif; ?>
				</td>
			</tr>
		<?php endif; ?>
	<?php endfor; ?>
	
	<?php if(!empty($custom_data)) : ?>
		<?=$custom_data?>
	<?php endif; ?>
		<tr>
			<td>
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
			                <tr>
								<td nowrap="nowrap"><?=get_text('fields_marked_with')?> <span class="red">*</span> <?=get_text('are_obligatory')?> &nbsp;&nbsp;</td>
			                  <td align="right" nowrap="nowrap"><?=get_block('input', array(
			                  	'type' => 'submit',
			                  	'name' => 'save',
			                  	'id' => 'save',
			                  	'value' => get_text($form_submit_title),
			                  	'class' => 'butt inact',
			                  	'onmouseover' => "this.className='butt act';",
			                  	'onmouseout' => "this.className='butt inact';",
			                  ))?>
			            <?php if($form_cancel != '') : ?>
							&nbsp;<?=get_block('input', array(
			                  	'type' => 'button',
			                  	'value' => get_text('button_cancel'),
			                  	'class' => 'butt inact',
			                  	'onmouseover' => "this.className='butt act';",
			                  	'onmouseout' => "this.className='butt inact';",
			                  	'onclick' => "document.location.href='$form_cancel';",
			                  ))?>
						<?php endif; ?>
			                  </td>
			                </tr>
		              </table>
		        </td>
		</tr>
	</table>
<?=get_block('form', array('end' => 'true'))?>
<br />
<?php if($html_fields > 0) : ?>
	<script type="text/javascript">
		<?php for($i = 0; $i < $html_fields; $i++) : ?>
			CKEDITOR.replace('<?=$html_fields_title[$i]?>', {customConfig : '/js/ckeditor/config_small.js'});
		<?php endfor; ?>
	</script>
<?php endif; ?>