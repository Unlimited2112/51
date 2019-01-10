<td valign="top" class="leftcol" width="1%">
	<?=get_block('ItemView')?>
</td>
<td valign="top" class="rightcol" width="99%">
	<?php if($show_tabs) : ?>
		<table width="1%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td>
	<?php endif; ?>
	<?php if($operation == 'view') : ?>
		&nbsp;
	<?php endif; ?>
	<?php if($show_tabs) : ?>
				<div class="back-tab">
					<table border="0" cellspacing="0" cellpadding="0">
						<tr>
						<?php for ($i = 0; $i<$tabs; $i++) : ?>
							<?php if($tab_current[$i]) : ?>
								<td class="tab-act" nowrap="nowrap" onclick="gotoURL('<?=$add_url?><?=$tab_operation[$i]?>')">
							<?php else : ?>
								<td class="tab" nowrap="nowrap" onclick="gotoURL('<?=$add_url?><?=$tab_operation[$i]?>')">
							<?php endif; ?>
									<?=$tab_name[$i]?>
								</td>
						<?php endfor; ?>
						</tr>
					</table>
				</div>		
			</td>
		</tr>
		<tr>
			<td class="form_brd">
				<br clear="all" />
	<?php endif; ?>
	<?php if($operation == 'add' or $operation == 'edit') : ?>
		<?=get_block('ItemEdit')?>
	<?php endif; ?>
	<?php if($show_tabs) : ?>
				</td>
			</tr> 
		</table>
	<?php endif; ?>
</td>