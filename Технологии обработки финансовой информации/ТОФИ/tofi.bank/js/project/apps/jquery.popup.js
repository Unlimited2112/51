$(document).ready(function() {
	$pelena = $('.pelena');
	$loader = $('.loader');
	$popup = $('.popup');
	$popupCache = new Array();
	
	$effectTime = 300;
		
	getPopup = function (options){
		if(options.type == 'gallery')
		{
			$galleryCurrentItem = options.itemIndex;
		}
		
		$('.pelena').height($('.wrap').height());
		$pelena.fadeIn($effectTime, function(){
			loaderHeight = $(document).scrollTop() + (($(window).height() - $loader.height()-40) * 0.5);
			loaderWidth	= $(document).scrollLeft() + (($(window).width() - $loader.width()) * 0.5);
			$loader.css({"top":loaderHeight, "left":loaderWidth});
			$loader.show();

			if($popupCache[options.type] == null){
				$popup.load(document.location.href, options, function() {
					$popupCache[options.type] = $popup.html();
					showPopup();
				});
			}
			else
			{
				$popup.html($popupCache[options.type]);
				showPopup();
			}
		});
	}
	
	showPopup = function (){
		$popup.css({minWidth: 0, minHeight: 0});
		popupHeight	= $(document).scrollTop() + (($(window).height() - $popup.height()-25) * 0.5);
		popupWidth	= $(document).scrollLeft() + (($(window).width() - $popup.width()) * 0.5);
		if ($.browser.opera)
		{
			$("h1,.bg,.bg-2").addClass('remove-outline');
		}
		if(popupHeight < 5)	{
			popupHeight = 5;
		}
		$popup.css({"top":popupHeight, "left":popupWidth});

		$popup.find('a.close').click(function(){
			closePelena();
			return false;
		});

		if($popup.find('div.slider').length > 0)
		{
			$popup.addClass('popup-slider');
			showGallery();
		}
		else
		{
			showSend();
		}
		
		$popup.fadeIn($effectTime);
		$loader.hide();
	}
	
	closePelena = function (){
		if($pelena.css('display') != 'none'){
			$loader.hide();
			$popup.fadeOut($effectTime, function(){
				$popup.html('').removeClass('popup-slider');
				$pelena.fadeOut($effectTime);
			});
		}
	}

	$(document).keyup(function(e){
		if (e.keyCode == 27){
			closePelena();
		}
	});
	
	$pelena.click(function(){
		closePelena();
		return false;
	});
	
	$loader.click(function(){
		closePelena();
		return false;
	});
});