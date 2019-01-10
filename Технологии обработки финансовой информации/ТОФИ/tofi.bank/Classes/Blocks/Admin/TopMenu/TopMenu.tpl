<div class="navigation" style="padding-right:0px;">
	<ul class="nolist cfix nav">
		<?php for($i = 0; $i < $modulecnt; $i++) : ?>
			<?php if($modulesel[$i]) : ?>
				<li><strong class="hr"><?=$modulename[$i]?></strong></li>
			<?php else : ?>
				<li><a href="<?=$redirect_page_url?><?=$moduleurl[$i]?>" title="<?=$modulename[$i]?>"><?=$modulename[$i]?></a></li>
			<?php endif; ?>
		<?php endfor; ?>
		<li class="showRight show-auto" title="<?=get_text('exit_suite')?>">
			<?=get_block('form', array(
				'begin' => 'true',
				'name' => 'logout_form',
				'action' => $redirect_page_url,
			))?>
				<a href="JavaScript: if (confirm('<?=get_text('logout_question')?>')) document.forms.logout_form.submit();"><?=get_text('exit_suite')?></a>
			<?=get_block('form', array('end' => 'true'))?>
		</li>
		<?php if($current_page_url == 'admin/profile/') : ?>
			<li class="showRight show-login"><strong class="hr"><?=htmlspecialchars($logged_user_login)?></strong></li>
		<?php else : ?>
			<li class="showRight show-profile" title="<?=htmlspecialchars($logged_user_login)?>"><a href="<?=$redirect_page_url?>profile/"><?=htmlspecialchars($logged_user_login)?></a></li>
		<?php endif; ?>
		<li class="showRight show-auto">
			<?php if($admin_area_langs) : ?>
				<?=get_block('form', array(
					'begin' => 'true',
					'name' => 'admin_area_lang_form',
					'action' => $HTTP . $current_page_url,
				))?>
					<script type="text/javascript">
					<!-- <![CDATA[
						var admin_area_id_lang_index=parseInt('<?=$admin_area_id_lang_selected?>');
						
						function admin_area_id_lang_return(){
							if((o=document.forms['admin_area_lang_form']) && typeof(o) !='undefined'){
								if(confirm('<?=get_text('conf_sure_leave')?>')){
									o.submit();
								}else{
									for(i =0; i<o.elements['admin_area_id_lang'].options.length; i++){
										if(o.elements['admin_area_id_lang'].options[i].value == admin_area_id_lang_index){
											o.elements['admin_area_id_lang'].selectedIndex =i;
											break;
										}
									}
								}
							}
						}
					// ]]>-->
					</script>
					<strong style="line-height:42px; padding-top:20px;"><?=get_block('input', array(
						'type' => 'select',
						'name' => 'admin_area_id_lang',
						'class' => 'inp',
						'onchange' => 'admin_area_id_lang_return();',
						'priority' => 'template,post,get',
					))?></strong>
				<?=get_block('form', array('end' => 'true'))?>
			<?php endif; ?>
		</li>
	</ul>
</div>