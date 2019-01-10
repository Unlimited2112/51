<?=get_block('SubMenu')?>
<?php if($filters_count && $item_search_elements_count) : ?>
<?=get_block('form', array(
	'begin' => 'true',
	'name' => 'item_search_form',
	'action' => $redirect_page_url,
))?>
<?php if($date_input_pasted) : ?>
<script type="text/javascript">
<!-- <![CDATA[

function reset_date(obj){
	if(document.getElementById && (o =document.getElementById(obj))!=null && (o2 =document.getElementById(obj+"_calendar_input")) !=null){
		o.value ='';
		o2.value ='';
	}
}

// ]]> -->
</script>
<?php endif; ?>

<table cellpadding="0" cellspacing="8" border="0">
	
<?php for($i = 0; $i < $filters_count; $i++) : ?>

<?php if(!$hide_group_elements[$i]) : ?>
<tr>
	<td class="right"><b><?=get_text('filter_by_' . $search_group_title[$i])?>:</b></td>
</tr>
<tr>
	<?php if($subfilters_count[$i] > 1) : ?>
		<td class="right">
			<table cellpadding="0" cellspacing="0" border="0">
			<tr>
			<?php for($j = 0; $j < $subfilters_count[$i]; $j++) : ?>
				<?php if($is_not_first_item[$i][$j]) : ?>
					<td>&nbsp;&nbsp;&nbsp;</td>
				<?php endif; ?>
				<td><?=get_text($input_title[$i][$j])?>:</td>
	
				<?php if($input_type[$i][$j] == 'date') : ?>
					<td><?=get_block('input', array(
						'type' => 'date',
						'name' => 'search_' . $input_name[$i][$j],
						'priority' => 'template,post,get',
					))?></td>
					<td valign="middle"><img src="<?=$IMAGES?>cms/bottonClearDate.gif" alt="" class="hand" onclick="reset_date('search_<?=$input_name[$i][$j]?>');" /></td>
				<?php endif; ?>
				<?php if($input_type[$i][$j] == 'select') : ?>
					<td><?=get_block('input', array(
						'type' => 'select',
						'name' => 'search_' . $input_name[$i][$j],
						'class' => 'inp inp1',
						'priority' => 'template,post,get',
					))?></td>
				<?php endif; ?>
				<?php if($input_type[$i][$j] == 'text') : ?>
					<td><?=get_block('input', array(
						'type' => 'text',
						'name' => 'search_' . $input_name[$i][$j],
						'class' => 'inp inp1',
						'priority' => 'template,post,get',
					))?></td>
				<?php endif; ?>
			<?php endfor; ?>
			</tr>
			</table>
		</td>
	<?php else : ?>
		<td class="right">
		<?php for($j = 0; $j < $subfilters_count[$i]; $j++) : ?>
			<?php if($input_type[$i][$j] == 'date') : ?>
				<table cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td><?=get_block('input', array(
						'type' => 'date',
						'name' => 'search_' . $input_name[$i][$j],
						'priority' => 'template,post,get',
					))?></td>
						<td valign="middle"><img src="<?=$IMAGES?>cms/bottonClearDate.gif" alt="" class="hand" onclick="reset_date('search_<?=$input_name[$i][$j]?>');" /></td>
					</tr>
				</table>
			<?php endif; ?>
			<?php if($input_type[$i][$j] == 'selector') : ?>
				<?=get_block('input', array(
						'type' => 'select',
						'name' => 'search_' . $input_name[$i][$j],
						'class' => 'inp inp1',
						'priority' => 'template,post,get',
					))?>
			<?php endif; ?>
			<?php if($input_type[$i][$j] == 'text') : ?>
				<?=get_block('input', array(
						'type' => 'text',
						'name' => 'search_' . $input_name[$i][$j],
						'class' => 'inp inp1',
						'priority' => 'template,post,get',
					))?>
			<?php endif; ?>
		<?php endfor; ?>
		</td>
	<?php endif; ?>
</tr>
<?php endif; ?>
<?php endfor; ?>
<tr>
	<td class="right">
	<?=get_block('input', array(
		'type' => 'submit',
		'name' => 'search',
		'id' => 'search',
		'value' => get_text('button_search'),
		'class' => 'butt inact',
		'onmouseover' => "this.className='butt act';",
		'onmouseout' => "this.className='butt inact';",
	))?>
		<?php if($is_search) : ?>
			&nbsp; 	<?=get_block('input', array(
						'type' => 'button',
						'name' => 'clear',
						'id' => 'clear',
						'value' => get_text('button_clear'),
						'class' => 'butt inact',
						'onmouseover' => "this.className='butt act';",
						'onmouseout' => "this.className='butt inact';",
					))?>
		<?php endif; ?>
	</td>
</tr>
</table>
<?=get_block('form', array('end' => 'true'))?>
<?php endif; ?>
<?=get_block('form', array(
	'begin' => 'true',
	'name' => 'show_item_form',
	'action' => $redirect_page_url,
))?>
	<table width="1%" border="0" cellspacing="0" cellpadding="0">
		<?=get_block('ArrayOutput', array(
			'block_begin' => '<div class="info" style="background-color: #ffffff">',
			'block_end' => '</div>',
			'item_begin' => '<span>',
			'item_end' => '</span><br />',
			'array' => 'info_view',
		))?>
		<?=get_block('ArrayOutput', array(
			'block_begin' => '<div class="error" style="background-color: #ffffff">',
			'block_end' => '</div>',
			'item_begin' => '<span>',
			'item_end' => '</span><br />',
			'array' => 'error_view',
		))?>
		<tr>
			<td class="left" valign="top">
				<?=get_block('ActiveTable', array(
					'title' => get_text('nav_title_' . $this->viewName),
					'checkable' => $can_delete,
					'clicklink' => $redirect_page_url . 'edit/',
					'popuped' => 'no',
				), $this->viewName)?>
				<?php if($can_delete) : ?>
					<?php if(!$_active_table_empty) : ?>
						<div style="float: left" id="delete_<?=$this->viewName?>">
							<?=get_block('input', array(
								'type' => 'button',
								'name' => 'delete',
								'value' => get_text('button_delete'),
								'confirm' => get_text('conf_sure_delete'),
								'class' => 'butt inact',
								'onmouseover' => "this.className='butt act';",
								'onmouseout' => "this.className='butt inact';",
							))?>&nbsp;
						</div>
					<?php endif; ?>
				<?php endif; ?>
				<?php if($can_add) : ?>
					<div  style="float: right" id="add_<?=$this->viewName?>">
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
				<?php endif; ?>
		        </td>
		</tr>
	</table>
<?=get_block('form', array('end' => 'true'))?>