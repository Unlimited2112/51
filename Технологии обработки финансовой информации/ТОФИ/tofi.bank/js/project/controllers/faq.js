$(document).ready(function() {
	$('.questions > ul > li > div').hide();
	$('.questions > ul > li > p > a').click(function() {
		$(this).parent().next('div').slideToggle(400);
		$(this).parents('li').toggleClass('active')
		return false;
	});
});
