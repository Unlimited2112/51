<?php if(!$is_empty) : ?>
<?php if($js_sort) : ?>
<script type="text/javascript">
<!--
	$(document).ready(function() {

		$('#grid_<?=$objectId?>').tablesorter({
			widgets: ['cookie']
		}).tablesorterPager({
			container: $('#nav_<?=$objectId?> .pager'),
			positionFixed: false,
			size: 40
		});

	});
//-->
</script>
<?php endif; ?>
<?php endif; ?>
       <table border="0" cellspacing="8" cellpadding="0" class="grid-area">
          <tr id="nav_<?=$objectId?>">
            <td colspan="2">
            <?php if($is_empty) : ?>
				<table border="0" cellspacing="0" cellpadding="0" class="navig">
					<tr>
						<td width="100%" nowrap="nowrap"><?=$empty_message?></td>
					</tr>
				</table>
			<?php else : ?>
	            		<table border="0" cellspacing="0" cellpadding="0" class="navig">
					<tr>
						<td width="100%" class="hdr1" nowrap="nowrap"><?=htmlspecialchars($title)?> (<?=htmlspecialchars($row_count)?> <?=get_text('total')?>)</td>
					</tr>
				</table>
	         		<div><img src="<?=$IMAGES?>cms/spacer.gif" alt="" width="1" height="14" title="" /></div>
	
				<table width="100%" border="0" id="grid_<?=$objectId?>" cellspacing="1" cellpadding="0" class="grid">
				<?php if($columns_count > 0) : ?>
					<thead>
				         	<tr class="head">
				         	<?php if($checkable) : ?>
								<th class="check" width="1%"><input type="checkbox" class="check" name="checkboxesAll" onclick="chbCheckAll(this.form,'checkboxes',this.checked);" /> </th>
							<?php endif; ?>
							 <? if(!$hide_number) : ?>
							<th>#</th>
							<? endif; ?>
							<?php for($i = 0; $i < $columns_count; $i++) : ?>
								<?php if($column_have_width[$i]) : ?>
									<th align="left" style="width: <?=$column_width[$i]?>" nowrap="nowrap">
								<?php else : ?>
									<th align="left">
								<?php endif; ?>
								
									<?=$column_name[$i]?>
								</th>
							<?php endfor; ?>
						</tr>
					</thead>
				<?php endif; ?>
				
				<?php if($row_count > 0) : ?>
					<tbody>
						<?php for($i = 0; $i < $row_count; $i++) : ?>
							<tr class="<?=$row_class[$i]?>" onmouseover="$(this).attr('class','active');" onmouseout="$(this).attr('class','<?=$row_class[$i]?>');">
							<?php if($checkable) : ?>
								<td>
								<input type="hidden" name="checkboxes2[]" value="<?=$row_check_val[$i]?>" />
								<input type="checkbox" class="check" name="checkboxes[]" value="<?=$row_check_val[$i]?>" onclick="chbExamAll(this.form,'checkboxes','checkboxesAll');"<?=$row_check_disabled[$i]?> />
								</td>
							<?php endif; ?>
							<? if(!$hide_number) : ?>
							<td class="hand" onclick="<?=$row_click[$i]?>"><?=($i+1)?></td>
							<? endif; ?>
							<?php for($j = 0; $j < $columns_count; $j++) : ?>
								<?php if($field_click[$i][$j] != '') : ?>
									<td align="left" class="hand" onclick="<?=$field_click[$i][$j]?>"<?=$field_nowrap[$i][$j]?>>
								<?php else : ?>
									<td align="left"<?=$field_nowrap[$i][$j]?>>
								<?php endif; ?>
									<?=$field_val[$i][$j]?>
								</td>
							<?php endfor; ?>
							</tr>
						<?php endfor; ?>
					</tbody>			
				<?php endif; ?>	
				</table>
				<?php if(!$is_empty) : ?>
					<?php if($js_sort) : ?>
						<div class="pager">
							<a href="#" class="first" ><span>First</span></a>
							<a href="#" class="prev" ><span>Previous</span></a>
							<span class="pagedisplay">&nbsp;</span>
							<a href="#" class="next" ><span>Next</span></a>
							<a href="#" class="last" ><span>Last</span></a>
							
							<select class="pagesize">
								<option value="10">10</option>
								<option value="20" selected="selected">20</option>
								<option value="30">30</option>
								<option value="40">40</option>
							</select>
						</div>
					<?php endif; ?>
				<?php endif; ?>
			<?php endif; ?>
		</td>
	</tr>
</table>		

<div><img src="<?=$IMAGES?>cms/spacer.gif" alt="" width="1" height="7" title="" /></div>