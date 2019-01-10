$(document).ready(function() {
	showSend = function (){
		$('div.post-msg form input').first().before('<input type="hidden" value="1" name="contactsPopup">')
	};

	showErrors = function ($message){
		if($('div.post-msg > label').length == 0)
		{
			$('div.post-msg form').before('<label></label>');
		}

		$('#txt_captcha').val('');
		$('div.l-capcha img').attr("src","/captcha.php?time='.time().'");
		
		$('div.post-msg > label').attr('class', 'error').html($message);
	};

	showSuccess = function ($message){
		if($('div.post-msg > label').length == 0)
		{
			$('div.post-msg form').before('<label></label>');
		}

		$('#txt_name').val('');
		$('#txt_email').val('');
		$('#txt_message').val('');
		$('#txt_captcha').val('');
		$('div.l-capcha img').attr("src","/captcha.php?time='.time().'");

		$('div.post-msg > label').attr('class', 'info').html($message);
	};
});