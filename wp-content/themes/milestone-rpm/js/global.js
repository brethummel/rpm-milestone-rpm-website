// JavaScript Document


jQuery(window).load(function($) {

		
	function countUp(element) {
		mystart = parseFloat(element.text());
		myend = parseFloat(element.data('value'));
		myinc = parseFloat(element.data('inc'));
		if (myinc == 1) {
			jQuery({ countNum: element.text()}).animate({
				countNum: myend
			},
			{
				duration: 1500,
				easing: 'swing',
				step: function() {
					element.text(Math.floor(this.countNum));
				},
				complete: function() {
					element.text(this.countNum);
				}
			});
		}
		if (myinc == 0.1) {
			jQuery({ countNum: element.text()}).animate({
				countNum: myend
			},
			{
				duration: 1500,
				easing: 'swing',
				step: function() {
					element.text(parseFloat(this.countNum).toFixed(1));
				},
				complete: function() {
					element.text(this.countNum);
				}
			});
		}
	}
	
	// ****************** FOR STATS COUNT-UP ****************** //

	jQuery.fn.isInViewport = function() {
	    var elementTop = jQuery(this).offset().top;
	    var elementBottom = elementTop + jQuery(this).outerHeight();

	    var viewportTop = jQuery(window).scrollTop();
	    var viewportBottom = viewportTop + jQuery(window).height();

	    return elementBottom > viewportTop && elementTop < viewportBottom;
	};
	
	if (jQuery('.count-up').length > 0) {
		var myval;
		jQuery('span.count-up').each(function(event) {
			myval = jQuery(this).text();
			mystart = '0';
			mydist = jQuery(this).offset().top;
			mythresh = mydist - (jQuery(window).height() * .80);
			jQuery(this).attr('data-value', myval);
			jQuery(this).attr('data-dist', mydist);
			jQuery(this).attr('data-thresh', mythresh);
			jQuery(this).text(mystart);
		});
		jQuery('span.count-up').each(function(event) {
			// console.log(jQuery(this).isInViewport());
			if (jQuery(this).isInViewport() && !jQuery(this).hasClass('counted')) {
				jQuery(this).addClass('counted');
				countUp(jQuery(this));
			}
		});
	}
	
	// ****************** END STATS COUNT-UP ****************** //
			
});

jQuery(document).ready(function($) {
	
	// console.log('global.js loaded successfully!');

	// ****************** FOR STATS COUNT-UP ****************** //

	$.fn.isInViewport = function() {
	    var elementTop = $(this).offset().top;
	    var elementBottom = elementTop + $(this).outerHeight();

	    var viewportTop = $(window).scrollTop();
	    var viewportBottom = viewportTop + $(window).height();

	    return elementBottom > viewportTop && elementTop < viewportBottom;
	};
	
	if ($('.count-up').length > 0) {
		$(window).scroll(function(event) {
			// console.log('scrollTop: ' + $(window).scrollTop());
			$('span.count-up').each(function(event) {
				mythresh = parseInt($(this).data('thresh'));
				// console.log($(this).parent().attr('class') + ': ' + mythresh);
				if ($(window).scrollTop() >= mythresh && !$(this).hasClass('counted')) {
					$(this).addClass('counted');
					countUp($(this));
				}
			});
		});
	}
	
	// To count up a stat, you must surround the number with a <span></span> tag,
	// making sure to include class="count-up" and then a data attribute for the
	// increment desired (either "1" or "0.1") as such: data-inc="1". Example:
	// <span class="count-up" data-inc="1">62</span>. If there is a "units" (like,
	// "62k"), also surround the unit with a <span class="label">k</span>
		
	function countUp(element) {
		mystart = parseFloat(element.text());
		myend = parseFloat(element.data('value'));
		myinc = parseFloat(element.data('inc'));

		var formatter = new Intl.NumberFormat("en-US");

		if (myinc == 1) {
			$({ countNum: element.text()}).animate({
				countNum: myend
			},
			{
				duration: 1500,
				easing: 'swing',
				step: function() {
					element.text(formatter.format(Math.floor(this.countNum)));
				},
				complete: function() {
					element.text(formatter.format(this.countNum));
				}
			});
		}
		if (myinc == 0.1) {
			$({ countNum: element.text()}).animate({
				countNum: myend
			},
			{
				duration: 1500,
				easing: 'swing',
				step: function() {
					element.text(formatter.format(parseFloat(this.countNum).toFixed(1)));
				},
				complete: function() {
					element.text(formatter.format(this.countNum));
				}
			});
		}
	}
	
	// ****************** END STATS COUNT-UP ****************** //



    setTimeout(function() {
    	// console.log('boop!');
		$('.support .block-testimonials.common-questions.slider-1').find('.slides').slick('slickSetOption', {
			slidesToShow: 2,
			slidesToScroll: 1,
			responsive: [
				{
					breakpoint: 768,
					settings: {
						slidesToShow: 1,
						slidesToScroll: 1,
					}
				}
			]
		}, true);
		$('.support .block-testimonials.common-questions.slider-1').find('.slick-slide').height('auto');
		var slideheight = $('.support .block-testimonials.common-questions.slider-1').find('.slick-track').height();
		$('.support .block-testimonials.common-questions.slider-1').find('.slick-slide').css('height', slideheight + 'px');
    }, 250);

	
	$('.block-logogrid.trusted-by').find('.row.justify-content-center').slick({
		autoplay: true,
		autoplaySpeed: 1300,
		adaptiveHeight: false,
		pauseOnFocus: false,
		pauseOnHover: false,
		variableWidth: true,
  		centerMode: true,
  		slidesToShow: 3,
		dots: false,
		arrows: false,
		appendDots: $('.home .block-logogrid.trusted-by').find('.container')
	});


	// ****************** SUPPORT PAGE POP-UP ****************** //

	if ($('body').data('slug') == 'support') {
		console.log('gonna pop up!');
		var content = '<h2>Need to transition away from Project Online?</h2>';
		content += '<p><strong>Microsoft Project Online</strong> will officially retire on <strong>September 30, 2026</strong>. We\’re here to help.</p>';
		content += '<div class="buttons"><a class="button medium primary" href="../contact/"><span class="button-text">Connect with us</span></a></div>';
		console.log(content);
		$('.pop-overlay .pop-modal .pop-content').html(content);
		$('.pop-overlay').css('display', 'flex');
	}


	
});
