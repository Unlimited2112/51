<?=get_block('SubMenu')?>

<div class="grid-area" style="padding:10px 0 10px 20px;">
	<div id="nav_structure" style="width: 480px;">   
	
	<script type="text/javascript">
	// <![CDATA[
		var jq_tree_button_up = '<img src="/images/cms/tree/list_up.gif" style="padding-right: 5px;" alt="<?=get_text('v_tree_up')?>" title="<?=get_text('v_tree_up')?>" />';
		var jq_tree_button_up_inact = '<img src="/images/cms/tree/list_up_inact.gif" style="padding-right: 5px;" alt="<?=get_text('v_tree_up_inact')?>" title="<?=get_text('v_tree_up_inact')?>" />';
		var jq_tree_button_down = '<img src="/images/cms/tree/list_down.gif" style="padding-right: 5px;" alt="<?=get_text('v_tree_down')?>" title="<?=get_text('v_tree_down')?>" />';
		var jq_tree_button_down_inact = '<img src="/images/cms/tree/list_down_inact.gif" style="padding-right: 5px;" alt="<?=get_text('v_tree_down_inact')?>" title="<?=get_text('v_tree_down_inact')?>" />';
	
		var jq_tree_button_delete = '<img src="/images/cms/tree/list_del.gif" style="padding-right: 5px;" alt="<?=get_text('v_tree_delete')?>" title="<?=get_text('v_tree_delete')?>" />';
		var jq_tree_button_delete_inact = '<img src="/images/cms/tree/list_del_inact.gif" style="padding-right: 5px;" alt="<?=get_text('v_tree_delete_inact')?>" title="<?=get_text('v_tree_delete_inact')?>" />';
		var jq_tree_button_add = '<img src="/images/cms/tree/list_add.gif" style="padding-right: 5px;" alt="<?=get_text('v_tree_add')?>" title="<?=get_text('v_tree_add')?>" />';
		var jq_tree_button_add_inact = '<img src="/images/cms/tree/list_add_inact.gif" style="padding-right: 5px;" alt="<?=get_text('v_tree_add_inact')?>" title="<?=get_text('v_tree_add_inact')?>" />';
		var jq_tree_button_edit = '<img src="/images/cms/tree/list_edit2.gif" style="padding-right: 5px;" alt="<?=get_text('v_tree_edit')?>" title="<?=get_text('v_tree_edit')?>" />';
		var jq_tree_button_edit_inact = '<img src="/images/cms/tree/list_edit2_inact.gif" style="padding-right: 5px;" alt="<?=get_text('v_tree_edit_inact')?>" title="<?=get_text('v_tree_edit_inact')?>" />';
	//]]>
	</script>
		
	<form name="tree_form" action="." method="post">
		<input type="hidden" name="action" value="" />
		<input type="hidden" name="page_id" value="" />
		<div class="treetable" id="treetable"></div>
	</form>
	<script type="text/javascript">
	// <![CDATA[
		<?php if($operation != 'view') : ?>
			var jq_tree_selected_item = <?=$v_sel_item?>;
		<?php endif; ?>
		
		var jq_tree_add_url = '<?=$HTTP?>admin/<?=$current_page_lang_str?><?=$v_item_add_url?>';
		var jq_tree_edit_url = '<?=$HTTP?>admin/<?=$current_page_lang_str?><?=$v_item_edit_url?>';
		var jq_tree_content_url = '<?=$HTTP?>admin/<?=$current_page_lang_str?><?=@$v_item_content_url?>';
		var jq_tree_ajax_url = '<?=$HTTP?>admin/<?=$current_page_lang_str?><?=$v_get_structure_url?>';
		
		var jq_tree_confirm_text = '<?=get_text('conf_sure_delete')?>';
		
		jq_tree_get_tree();
	//]]>
	</script>
	</div>
</div>