<tr>
	<td><strong><?=get_text('user_permissions')?></strong>:<br />
		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding-left: 20px">
			<tr>
				<td colspan="2"><b><?=get_text('admin_area')?></b></td>
			</tr>
		<?php for($i = 0; $i < $permissions; $i++) : ?>
			<tr>
				<td width="1%" nowrap="nowrap"><?=$permission_title[$i]?> &nbsp;</td>
				<td width="99%"><?=get_block('input', array(
					'type' => 'checkbox',
					'name' => $permission[$i],
					'value' => "1",
					'priority' => "template,post,get",
				))?></td>
			</tr>
		<?php endfor; ?>
		</table>		
	</td>
</tr>