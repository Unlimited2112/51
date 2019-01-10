<?php if($pt_menu_length) : ?>
	<?php for($i = 0; $i < $pt_menu_length; $i++) : ?>
		<?php if(!$pt_current[$i]) : ?>
			<?=htmlspecialchars($pt_item_title[$i])?> -
		<?php else : ?>
			<?=htmlspecialchars($pt_item_title[$i])?>
		<?php endif; ?>
	<?php endfor; ?>
	|
<?php endif; ?>
<?=$site_title?>