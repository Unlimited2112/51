$(document).ready(function(){
	$('.topMenu .submenu').parent('li').addClass('sub');
	
	$('.switch').click(function(){
		$('body').toggleClass('white');
		if($('body').is('.white')) {
			$.cookie('day_type', 'day', {path: "/"});
		}
		else {
			$.cookie('day_type', 'night', {path: "/"});
		}
	})
	
	$('.intro .slider').click(function(){
		$(this).parents('.intro').toggleClass('open');
		$(this).parents('.intro').find('.body').slideToggle(200);
	})
	
	$('.tabs a').click(function(){
		if(!$(this).is('.current')){
			var tabIndex = $(this).index();
			$(this).siblings().removeClass('current');
			$(this).addClass('current');
			$('.tabContent.active').removeClass('active');
			$($('.tabContent').get(tabIndex)).addClass('active');
			if($(this).is('.moscowTab')) {
				showMoscowMap();
			}
			else {
				showMinskMap();
			}
		}
		return false;
	});	
	
	$('.jcarousel-skin-gallery').jcarousel();
	$('.jcarousel-skin-fav').jcarousel({
		scroll: 1,
		wrap: 'circular'
	});
	
	$('.bg').cycle({
		fx: "fade",
		timeout: 0,
		prev: ".intro a.prev",
		next: ".intro a.next"
	})
	
	var overalCount = $('img', $('.preview .photo')).length;
	$('.pager .overal').html(overalCount);
	
	$('.preview .photo').cycle({
		fx: "fade",
		timeout: 0,
		speed: 500,
		prev: ".pager a.prev",
		next: ".pager a.next",
		pager: ".pager .current"
	})
	
	$('.contacts .city span').click(function(){
		if(!$(this).is('.current')){
			var tabIndex = $(this).index();
			$(this).siblings().removeClass('current');
			$(this).addClass('current');
			$('.contacts').find('.cont.active').hide().removeClass('active');
			$($('.contacts').find('.cont').get(tabIndex)).fadeIn(500,function(){
				$(this).addClass('active');
			})
		}
		return false;
	});
		
});