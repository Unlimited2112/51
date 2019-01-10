<a href="<?=$HTTPl?>">Главная</a>
&nbsp; &raquo; &nbsp;
<?php if($bc_menu_length) : ?>
	<?php for($i = 0; $i < $bc_menu_length; $i++) : ?>
		<?php if(!$bc_current) : ?>
			<a href="<?=$HTTPl?><?=$bc_item_uri[$i]?>"><?=$bc_item_title[$i]?></a>
			 &nbsp; &raquo; &nbsp;
		<?php else : ?>
			<span><?=$bc_item_title[$i]?></span>
		<?php endif; ?>
	<?php endfor; ?>
<?php endif; ?>