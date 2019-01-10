<?php if($pagination_count>1) : ?>
   	<div align="center">	
   		<ul class="menu-page">	
   			<? /*
            <?php if($pagination_prev_url != "") : ?>
                <span>&nbsp;<a href="<?=$pagination_prev_url?>">&laquo; </a></span><a href="<?=$pagination_prev_url?>" class="underline">Назад</a><span>
            <?php endif; ?>
            */ ?>
            <?php for($i = 0; $i < $pagination_count; $i++) : ?>
                <?php if($pagination_pages[$i] == $pagination_current) : ?>
                    <li><?=$pagination_pages[$i]?></li>
                <?php else : ?>
                	<li><a href="<?=$pagination_pages_url[$i]?>"><?=$pagination_pages[$i]?></a></li>
                <?php endif; ?>
            <?php endfor; ?>
            <? /*
            	<?php if($pagination_next_url != "") : ?>
                	<a href="<?=$pagination_next_url?>" class="underline">Вперед</a><span><a href="<?=$pagination_next_url?>"> &raquo;</a>&nbsp;</span>
                <?php endif; ?>
            */ ?>
   		</ul>
   	</div>
<?php endif; ?>