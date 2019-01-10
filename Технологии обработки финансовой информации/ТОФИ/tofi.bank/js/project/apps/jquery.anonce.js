jQuery.fn.extend({
	anonceBlock: function () {
		if (this) {
			var me = jQuery(this),
				items = me.find('.anons-item'),
				pages = me.find('.a-pages a'),
				numElmts = items.length,

				nextElement = 1,
				currentElement = 0,

				animateShowSpeed = 300,
				animateHideSpeed = 300,

				isAnimating = false,
				autorotate = null,
				autorotateDelay = 5000;

			var initAutorotate = function () {
				autorotate = setInterval(function () {
					scrollNext();
				}, autorotateDelay)
			}

			var stopAutorotate = function () {
				clearInterval(autorotate);
			}

			var scrollNext = function () {
				if (!isAnimating) {
					if (nextElement >= numElmts) {
						nextElement = 1;
					}
					else {
						nextElement++;
					}

					updateElement();
				}
			}

			var scrollTo = function (pageNum) {
				if (!isAnimating) {
					nextElement = pageNum;
					updateElement();
				}
			}

			var updateElement = function () {
				isAnimating = true;
				hideElement();
			}

			var hideElement = function () {
				jQuery(items[currentElement-1]).fadeOut(animateHideSpeed, function(){showElement();});
			}

			var showElement = function () {
				currentElement = nextElement;
				pages.removeClass('active');
				jQuery(pages[currentElement-1]).addClass('active');
				jQuery(items[nextElement-1]).fadeIn(animateShowSpeed, function(){isAnimating = false;});
			}

			var init = function () {
				pages.each(function (idx, el) {
					$(el).click(function () {
						scrollTo(idx + 1);
						return false;
					});
				});

				me.mouseover(function(){stopAutorotate();}).mouseout(function(){initAutorotate();});
				
				items.each(function (idx, el) {
					$(el).hide();
				});

				initAutorotate();

				showElement();
			}

			init();
		}
	}
})