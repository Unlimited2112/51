<h1><?=htmlspecialchars($path_title)?></h1>
<div class="leftside">
	<?php if($im_items_count) : ?>
	<ul class="leftMenu">
		<?php for($i = 0; $i < $im_items_count; $i++) : ?>
			<?php if($im_current[$i] == 0) : ?>
				<li><a href="<?=$HTTP?><?=$im_uri[$i]?>"><?=htmlspecialchars($im_title[$i])?></a></li>
			<?php else : ?>
				<li class="current"><a href="<?=$HTTP?><?=$im_uri[$i]?>"><?=htmlspecialchars($im_title[$i])?></a></li>
			<?php endif; ?>
		<?php endfor; ?>
	</ul>
	<?php endif; ?>
	
	<?php if($is_direction) : ?>
		<?=get_block('ProductsBlock', array('path' => $pscv_id))?>
		
		<?php if($is_direction_page && !empty($fav_count)) : ?>
		<div class="favTitle"><?=get_text('choosen', 'Избранное')?></div>
		<ul class="jcarousel-skin-fav">
			<?php for($i = 0; $i < $fav_count; $i++) : ?>
			<li><a href="<?=$pscv_link?><?=$fav_uri[$i]?>/"><?=get_block('Thumbnail', array(
				'method' => 'adaptiveResize',
				'image' => $fav_image[$i],
				'width' => '183',
				'height' => '138',
				'alt' => $fav_title[$i],
			))?></a></li>
			<?php endfor; ?>
		</ul>
		<?php endif; ?>
		
		<?php if(!$is_direction_page) : ?>
		<div class="article">
			<?php if(!$pscv_subheader) : ?>
			<h4><a href="<?=$pscv_link?>"><?=htmlspecialchars($pscv_subheader)?></a></h4>
			<?php endif; ?>
			<?php if(!$pscv_annonce) : ?>
			<p><?=htmlspecialchars($pscv_annonce)?></p>
			<?php endif; ?>
			<?php if($pscv_subheader || $pscv_annonce) : ?>
			<p><a href="<?=$pscv_link?>"><?=get_text('read_more', 'Читать полностью')?></a></p>
			<?php endif; ?>
		</div>
		<?php endif; ?>
	<?php endif; ?>
</div>