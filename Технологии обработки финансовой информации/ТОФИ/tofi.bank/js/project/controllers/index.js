$('document').ready(function(){
	$('.gallery img').css({opacity: 0});
	$('.gallery img:first').addClass('top').css({opacity: 1.0});
	setInterval(function(){
		var current = $('.gallery img.top');
		var next = current.next().length ? current.next() : $('.gallery img:first');
		current.removeClass('top').css({opacity: 1}).animate({opacity: 0}, 1000);
		next.addClass('top').css({opacity: 0}).animate({opacity: 1.0}, 1000);
		setTimeout(function(){ current.css({opacity: 0}); }, 1000);
	}, 5000);
});