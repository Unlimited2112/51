$(document).ready(function() {
	$gallerySlider = null;
	$galleryAllItems = 0;
	$galleryCurrentItem = 1;
	$galleryAnimateBig = false;
	$galleryAnimateSmall = false;
	$galleryWidthBig = 612;
	$galleryWidthSmall = 156;
	$galleryOnLineSmall = 4;
	
	showGallery = function (){
		$gallerySlider = $('.slider');
		$galleryAllItems = $gallerySlider.find('.small-imgs a').size();
		$galleryAnimate = false;
		
		$gallerySlider.find('.big-imgs img').preload({
			placeholder:'/images/preloaders/gallery-popup-big-placeholder.png',
			notFound:'/images/preloaders/gallery-popup-big-notfound.png'
		});
		$gallerySlider.find('.small-imgs img').preload({
			placeholder:'/images/preloaders/gallery-popup-list-placeholder.png',
			notFound:'/images/preloaders/gallery-popup-list-notfound.png'
		});
			
		$gallerySlider.find('.big-imgs').width($galleryAllItems*$galleryWidthBig);
		$gallerySlider.find('.small-imgs').width($galleryAllItems*$galleryWidthSmall);
		
		$gallerySlider.find('a.arrow-left').click(function(){
			if(!$galleryAnimateBig && !$galleryAnimateSmall)
			{
				$galleryAnimateBig = true;
				$galleryAnimateSmall = true;
				
				if($galleryCurrentItem == 1)
				{
					$galleryCurrentItem = $galleryAllItems;
				}
				else
				{
					$galleryCurrentItem = $galleryCurrentItem-1;
				}
				
				animateGallery();
			}
			
			return false;
		});
		
		$gallerySlider.find('a.arrow-right, .big-imgs').click(function(){
			if(!$galleryAnimateBig && !$galleryAnimateSmall)
			{
				$galleryAnimateBig = true;
				$galleryAnimateSmall = true;
				
				if($galleryCurrentItem == $galleryAllItems)
				{
					$galleryCurrentItem = 1;
				}
				else
				{
					$galleryCurrentItem = $galleryCurrentItem+1;
				}
				
				animateGallery();
			}
			
			return false;
		});
		
		$gallerySlider.find('.small-imgs a').click(function(){
			if(!$galleryAnimateBig && !$galleryAnimateSmall)
			{
				$galleryAnimateBig = true;
				$galleryAnimateSmall = true;
				
				$galleryCurrentItem = $gallerySlider.find('.small-imgs a').index(this)+1;
					
				animateGallery();
			}
			
			return false;
		});

		animateGallery();
	}
	
	animateGallery  = function (){
		bigAnimate = ($galleryCurrentItem-1)*$galleryWidthBig;
		$gallerySlider.find('.big-imgs').animate({
			marginLeft: '-'+bigAnimate+'px'
		}, 600, function() {
			$galleryAnimateBig = false;
		});
		
		$gallerySlider.find('h2').fadeOut(300, function(){
			$(this).html($gallerySlider.find('.small-imgs img:eq('+($galleryCurrentItem-1)+')').attr('alt')).fadeIn(300);
		})

		$gallerySlider.find('.small-imgs img.active').removeClass('active');
		
		if(($galleryAllItems-$galleryCurrentItem)<($galleryOnLineSmall) && ($galleryAllItems > $galleryOnLineSmall))
		{
			smallAnimate = ($galleryAllItems-$galleryOnLineSmall)*$galleryWidthSmall;
		}
		else if($galleryAllItems > $galleryOnLineSmall)
		{
			smallAnimate = ($galleryCurrentItem-1)*$galleryWidthSmall;
		}
		else
		{
			smallAnimate = null;
		}

		if(smallAnimate != null)
		{
			$gallerySlider.find('.small-imgs').animate({
				marginLeft: '-'+smallAnimate+'px'
			}, 600, function() {
				$gallerySlider.find('.small-imgs img:eq('+($galleryCurrentItem-1)+')').addClass('active');
				$galleryAnimateSmall = false;
			});
		}
		else
		{
			$gallerySlider.find('.small-imgs img:eq('+($galleryCurrentItem-1)+')').addClass('active');
			$galleryAnimateSmall = false;
		}
	}
});