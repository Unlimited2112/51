<?php if($sub_menu_cnt>1) : ?>
    <div class="sub-menu-container">
	<div class="sub-menu-title"><strong><?=get_text('sub_menu_title')?>:</strong></div>
	<ul  class="nolist cfix sub-menu">
		<?php for($i = 0; $i < $sub_menu_cnt; $i++) : ?>
			<?php if($sub_menu_selected[$i]) : ?>
				<li style="white-space: nowrap;"><?=$sub_menu_name[$i]?></li>
			<?php else : ?>
				<li style="white-space: nowrap;"><a href="<?=$redirect_page_url?><?=$sub_menu_url[$i]?>" title="<?=$sub_menu_name[$i]?>"><?=$sub_menu_name[$i]?></a></li>
			<?php endif; ?>
		<?php endfor; ?>
	</ul>
<br clear="all" />
        </div>
<?php endif; ?>