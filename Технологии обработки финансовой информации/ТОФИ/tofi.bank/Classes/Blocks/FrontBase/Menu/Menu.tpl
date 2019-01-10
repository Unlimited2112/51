<?php if($m_items_count) : ?>
	<ul>
		<?php for($i = 0; $i < $m_items_count; $i++) : ?>
			<?php if($m_current[$i] == 0) : ?>
				<li><a href="<?=$HTTPl?><?=$m_uri[$i]?>"><?=$m_title[$i]?></a></li>
			<?php endif; ?>
			<?php if($m_current[$i] >= 1) : ?>
				<li class="current"><a href="<?=$HTTPl?><?=$m_uri[$i]?>"><?=$m_title[$i]?></a></li>
			<?php endif; ?>
		<?php endfor; ?>
	</ul>
<?php endif; ?>