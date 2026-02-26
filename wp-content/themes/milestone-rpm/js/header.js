// JavaScript Document

jQuery(document).ready(function($) { 	
	
	console.log('header.js loaded successfully!');
	
	if ($(document).scrollTop() > 20) {
		$('header').addClass('scrolled');
	}
	
    $(window).scroll(function() {
        if ($(document).scrollTop() > 20) {
            $('header').addClass('scrolled');
        }
        else {
            $('header').removeClass('scrolled');
        }
    });
	
	let resetmenu = null;
	// submenu rollover (desktop)
    $(document).on('mouseenter', 'header .menu > ul > li > a', function(event) {
	// $('header .menu > ul > li > a').on('mouseenter', function(event) {
		// console.log('hi there!');
		event.preventDefault();
        if (resetmenu != null) {
        	clearTimeout(resetmenu);
        	resetmenu = null;
        }
        // console.log($(this).css('display'));
		if (!$(this).closest('.menu').hasClass('locked') && $(this).closest('li').css('display') != 'block' && $(this).closest('li').find('.drop').length !== 0) {
			$(this).closest('.menu').addClass('locked');
			$('header .menu').find('.drop').slideUp(350);
			$('header .menu > ul > li').removeClass('open');
			if ($(this).closest('li').find('.drop').length !== 0 && $(this).closest('li').css('display') != 'block') {
				$(this).closest('li').addClass('open');
				$(this).closest('li').children('.drop').slideDown(350, function(event) {
					$(this).closest('.menu').removeClass('locked');
				});
			}
		}
	});

	// desktop
    $(document).on('click', 'header .menu > ul > li.has-submenu > a', function(event) {
	// $('header .menu > ul > li.has-submenu > a').on('click', function(event) {
		if ($(this).closest('li').css('display') != 'block') { // desktop only
			event.preventDefault();
			event.stopPropagation();
			var target = $(this).closest('.has-submenu').children('a').attr('href');
			window.location.href = target;
		}
	});

    $(document).on('click', 'header .menu > ul > li.has-submenu', function(event) {
	// $('header .menu > ul > li.has-submenu').on('click', function(event) {
		// console.log('li click tracked!');
		if (!$(this).hasClass('open')) {
			$(this).trigger('mouseenter');
		} else {
			$(this).trigger('mouseleave');
		}
	});

    $(document).on('click', 'header .menu > ul > li > .mega.drop .mouse-hit', function(event) {
	// $('header .menu > ul > li > .mega.drop .mouse-hit').on('click', function(event) {
		event.stopPropagation();
		// console.log('mouse-hit action!');
		var target = $(this).closest('.has-submenu').children('a').attr('href');
		window.location.href = target;
		// console.log(target);
	});
	
    $(document).on('mouseleave', 'header .menu > ul > li', function(event) {
	// $('header .menu > ul > li').on('mouseleave', function(event) {
		if (!$(this).closest('.menu').hasClass('locked')) {
			if ($(this).find('.drop').length !== 0 && $(this).css('display') != 'block') {
				$(this).removeClass('open');
				$(this).find('.drop').slideUp(350, function(event) {
					$(this).closest('.menu').removeClass('locked');
					// console.log('removed lock on .main-menu');
				});
			}
		}
	});

	$(document).mousemove(function(){
        if ($("header:hover").length == 0 && $('header .main-menu').css('display') != 'block') {
        	// console.log($('.menu >ul >li').css('display'));
            if (resetmenu == null) {
				resetmenu = setTimeout(function() {
					$('.menu ul li').removeClass('open').find('.drop').slideUp(350).closest('.menu').removeClass('locked');
					resetNav();
				}, 1000);
            }
        }
    });
	
	// window.closeMenu = function() {
	// 	$('header .main-menu').addClass('locked');
	// 	$('header .main-menu').slideUp(400, function() {
	// 		// $('header .hamburger img').attr("src", imageSrc);
	// 		$('header .main-menu').removeClass('locked open');
	// 		// $('.main-menu li.open .drop').slideUp(300);
	// 		// $('.main-menu li.open').removeClass('open');
	// 		// $('body').removeClass('no-scroll');
	// 		$('header').removeClass('menu-open scrolled');
	// 		if ($(document).scrollTop() < 20) {
	// 			$('header').removeClass('scrolled');
	// 		}
	// 		//resetNav();
	// 	});
	// }
	
    // $(document).on("click", function(event){
    //     var $trigger = $('.menu > ul > li');
    //     if ($trigger !== event.target && !$trigger.has(event.target).length) {
    //     	if ($('header .header-bottom').css('display') != 'block') { // desktop
	// 			$('header .main-menu .drop').slideUp(200, function(event) {
	// 				$('header .main-menu').removeClass('locked');
	// 				$(this).parent().removeClass('open');
	// 			});
    //     	} else { // mobile
    //     		// console.log('evaluating what to do for mobile!');
    //     		// if (event.target !== $('header .hamburger .hamburger-container') && !$trigger.has(event.target).length) {
    //     		// 	// console.log('the click was not on the hamburger!');
    //     		// 	$('header .hamburger .hamburger-container').trigger('click');
    //     		// }
    //     	}
    //     }            
    // });
	
    $(document).on('click', 'header .hamburger .hamburger-container', function(event) {
    // $('header .hamburger .hamburger-container').on('click', function(event) {
        if (!$('header').hasClass('locked')) {
            if ($('header .main-menu').hasClass('open')) {
				$('header .main-menu').removeClass('transition-ready');
                closeMenu();
            } else {
                $('header .main-menu').addClass('locked');
                // $('header .hamburger img').attr("src", imageSrc);
				$('header').addClass('scrolled menu-open');
                $('header .main-menu').slideDown(400, function() {
                    $('header .main-menu').removeClass('locked');
                    $('header .main-menu').addClass('open');
                    // $('body').addClass('no-scroll');
	                setTimeout(function() {
						$('header main-menu').addClass('transition-ready');
					}, 500);
                });
            }
        }
	});
	
    $(document).on('click', 'header .menu > ul > li.has-submenu.open', function(event) {
    	if ($(event.target).hasClass('mobile-toggle')) {
			event.preventDefault();
			resetNav();
    	}
    });

	// submenu click (mobile)
    $(document).on('click', 'header .menu > ul > li.has-submenu', function(event) {
	// $('header .menu > ul > li.has-submenu').click(function(event) {
		if ($(this).css('display') == 'block') { // mobile only
			if (!$(this).hasClass('open')) {
				// console.log('menu item is not open!');
				event.preventDefault();
				$('header .main-menu.menu').addClass('slide-collapse');
				$('header .menu-reset').fadeIn(350);
				$('.menu li.open').removeClass('open');
				$(this).addClass('opening');
				$('header .menu > ul > li:not(.opening)').slideUp(100, function(event) {
					$('header .menu > ul > li.opening').find('.mega.drop').slideDown(250, function(event) {
						$(this).parent().addClass('open');
						$(this).parent().removeClass('opening');
					})
				});
				if ($(this).children('.mega').length) {
					$('header .main-menu').addClass('mega-open');
				}
			} else {
				// event.preventDefault();
				// var child = $(this).find('a:first');
				// console.log(child);
			}
		}
	});

    $(document).on('click', 'header .menu-reset', function(event) {
	// $('header .menu-reset').on('click', function(event) {
		resetNav();
	});
	
	window.closeMenu = function() {
		if ($('header .menu > ul > li').css('display') != 'inline-block') { // mobile only
			$('header .menu-reset').fadeOut(350);
			$('header .main-menu').addClass('locked');
			$('header .main-menu').slideUp(400, function() {
				// $('header .hamburger img').attr("src", imageSrc);
				$('header .main-menu').removeClass('locked open');
				// $('.main-menu li.open .drop').slideUp(300);
				// $('.main-menu li.open').removeClass('open');
				// $('body').removeClass('no-scroll');
				$('header').removeClass('menu-open scrolled');
				if ($(document).scrollTop() < 20) {
					$('header').removeClass('scrolled');
				}
				$('header .main-menu').removeClass('mega-open');
				resetNav();
			});
		}
	}
	
	window.resetNav = function() {
		$('header .main-menu li.has-submenu.open .mega.drop').slideUp(200, function(event) {
			$(this).parent().removeClass('open');
			$('header .main-menu.menu').removeClass('slide-collapse');
			$('header .main-menu').removeClass('mega-open');
			$('header .menu > ul > li').slideDown(200);
		});
		$('header .menu-reset').fadeOut(350);
	}
    
/*    $('header .hamburger img').on('click', function(event) {
        if (!$('header .main-menu').hasClass('locked')) {
            imageSrc = $('header .hamburger img').attr("src");
            if ($('header .main-menu').hasClass('open')) {
                $('header .main-menu').addClass('locked');
                $('header .main-menu').slideUp(250, function() {
                    imageSrc = imageSrc.replace("menu_close.png", "menu.png");
                    $('header .hamburger img').attr("src", imageSrc);
                    $('header .main-menu').removeClass('locked');
                    $('header .main-menu').removeClass('open');
                    $('body').removeClass('no-scroll');
                });
            } else {
                $('header .main-menu').addClass('locked');
                imageSrc = imageSrc.replace("menu.png", "menu_close.png");
                $('header .hamburger img').attr("src", imageSrc);
                $('header .main-menu').slideDown(250, function() {
                    $('header .main-menu').removeClass('locked');
                    $('header .main-menu').addClass('open');
                    $('body').addClass('no-scroll');
                });
            }
        }
	});*/
	
});