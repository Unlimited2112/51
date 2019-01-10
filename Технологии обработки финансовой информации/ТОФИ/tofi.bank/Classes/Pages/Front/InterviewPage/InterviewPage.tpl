<div class="wrap">
	<h2>Интервью</h2>
	<style type="text/css">
	.php-dev-ol {
		list-style-type: decimal;
		margin-left: 30px;
		line-height: 1.9em;
	}
	</style>
	<table border="0">
		<tr>
			<td nowrap="nowrap" valign="top" style="border: 0; border-right: 1px solid #87898A; padding-right: 10px;">
				<strong>План интервью:</strong>
				<ol class="plan-list">
				<?php $i=0; $was=false; foreach($block_list as $block_class => $block_item) : ?>
					<?php if($block_class == $current_step) : $was=true; ?>
					<li style="color: #BB0F5C; font-weight: bold;"><?php echo ++$i . '. ' . $block_item; ?></li>
					<?php elseif(!$was): ?>
					<li style="font-style: italic;"><?php echo ++$i . '. ' . $block_item; ?></li>
					<?php else: ?>
					<li><?php echo ++$i . '. ' . $block_item; ?></li>
					<?php endif; ?>
				<?php endforeach; ?>
				</ol>
			</td>
			<td valign="top" style="border: 0; padding-left: 10px;"><?=get_block('InterviewItem')?></td>
		</tr>
	</table>
</div>