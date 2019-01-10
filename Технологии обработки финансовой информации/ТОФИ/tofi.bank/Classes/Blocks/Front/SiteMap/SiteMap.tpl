<?php if($sm_items_count>0) : ?>
    <ul>
    	<?php if($sm_parent == 0) : ?>
			<li><ul><li><a href="<?=$HTTP?>">Главная</a></li></ul></li>
		<?php endif; ?>
		<?php for($i = 0; $i < $sm_items_count; $i++) : ?>
			<?php if($sm_parent == 0) : ?>
				<li>
					<ul>
						<li>
			<?php else : ?>
				<li>
			<?php endif; ?>
				<a href="<?=$HTTP?><?=$sm_uri[$i]?>"><?=$sm_title[$i]?></a>
				<?php if($sm_current_level <= 1) : ?>
					<?=get_block('SiteMap', array('parent' => $sm_id[$i], 'level' => $sm_get_level[$i]))?>
				<?php endif; ?>
			<?php if($sm_parent == 0) : ?>
						</li>
					</ul>
				</li>
			<?php else : ?>
				</li>
			<?php endif; ?>
        <?php endfor; ?>
    </ul>
<?php endif; ?>