<?=get_block('form', array(
	'begin' => 'true',
	'name' => $form_name,
	'method' => 'post',
	'enctype' => 'multipart/form-data',
	'action' => $form_action,
	'target' => $form_target,
	'onsubmit' => $form_onsubmit,
))?>
	<div>
	<?=get_block('ArrayOutput', array(
		'block_begin' => '<p class=&quot;sucess&quot;>',
		'block_end' => '</p>',
		'item_begin' => '',
		'item_end' => '<br />',
		'array' => 'info',
	))?>
	<?=get_block('ArrayOutput', array(
		'block_begin' => '<p class=&quot;err&quot;>',
		'block_end' => '</p>',
		'item_begin' => '',
		'item_end' => '<br />',
		'array' => 'error',
	))?>
	<?php for($i = 0; $i < $fields_count; $i++) : ?>
		<?php if($field_type[$i] == 'hidden') : ?>
			<div style="display:none"><?=get_block('input', array(
				'type' => $field_type[$i],
				'name' => $field_name[$i],
			))?></div>
		<?php else : ?>
				<div>
					<label for="<?=$field_name[$i]?>"><?=get_text($field_name[$i])?>:</label>
					<?php if($field_type[$i] == 'text' || $field_type[$i] == 'password') : ?>
						<?=get_block('input', array(
							'type' => $field_type[$i],
							'name' => $field_name[$i],
							'id' => $field_name[$i],
							'class' => 'i-text',
						))?>
					<?php endif; ?>

					<?php if($field_type[$i] == 'textarea') : ?>
						<?=get_block('input', array(
							'type' => $field_type[$i],
							'name' => $field_name[$i],
							'id' => $field_name[$i],
							'cols' => '30',
							'rows' => '10',
						))?>
					<?php endif; ?>

					<?php if($field_type[$i] == 'captcha') : ?>
						<img style="position: absolute; right: 0px;" src="<?=$HTTP?>captcha.php" alt="<?=get_text($field_name[$i])?>" />
						<?=get_block('input', array(
							'type' => 'text',
							'name' => $field_name[$i],
							'id' => $field_name[$i],
							'class' => 'capt',
						))?>
					<?php endif; ?>
				</div>
		<?php endif; ?>
	<?php endfor; ?>
	<button type="submit" onclick="send_<?=$form_name;?>_form('', true);"><span>Отправить</span></button>
	<div class="clr"></div>
	</div>
<?=get_block('form', array('end' => 'true'))?>