<div class="wrap">
	<h2><?=htmlspecialchars($current_page_title)?></h2>
	<?=@$scv_contents?>
	
  	<div class="form-container">
		<form id="login_form" class="wrapform" method="post" name="login_form">
			<p>Для работы в системе необходимо авторизоваться</p>
			
			<div class="error">
				<?=get_block('ArrayOutput', array(
					'block_begin' => '',
					'block_end' => '',
					'item_begin' => '<label class=&quot;error&quot;>',
					'item_end' => '</label>',
					'array' => 'login_form_errors',
				))?>
			</div>
			<input type="hidden" value="login_form" name="send_form_name" />
			<input id="login_form_form_action" type="hidden" value="" name="form_action" />

			<input type="hidden" name="post_login_action" value="hola!" />
			<input type="hidden" name="post_login_url" value="/<?=$current_page_url?>" />

			<label><?=get_text('login_form_name')?>	<?=get_block('input', array('type' => 'text', 'name' => 'login_form_name', 'id' => 'login_form_name', 'class' => 'input-width'))?></label>
			<br />
			<br />
			<label><?=get_text('login_form_password')?>	<?=get_block('input', array('type' => 'password', 'name' => 'login_form_password', 'id' => 'login_form_password', 'class' => 'input-width'))?></label>
			<div class="form-button"><a style="cursor: pointer;" onclick="$('#login_form').submit();" class="button"><span>Войти</span></a></div>
		</form>
    </div>
</div>