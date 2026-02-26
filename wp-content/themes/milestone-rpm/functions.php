<?php

error_reporting(E_ALL & ~E_WARNING & ~E_DEPRECATED & ~E_USER_DEPRECATED & ~E_NOTICE);


// ======================================= //
//            LOAD SPRINGBOARD             //
// ======================================= // 

require('partials/_springboard.php');


// ======================================= //
//        ADD THEME SCRIPTS + FONTS        //
// ======================================= // 

function spr_add_theme_scripts() {
	
    $themeurl = get_template_directory_uri();
	$themedir = get_template_directory();
	
    // compiled style.css
    wp_enqueue_style('style', $themeurl . '/style.css');
    
    // font styles
    wp_enqueue_style('bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css');
    spr_add_theme_fonts($themeurl); // adds fonts specified in config.php for specific implementation
    
    // bootstrap scripts
    wp_register_script('popper.js', 'https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js', array('jquery'), '1.16.0', true);
    wp_register_script('bootstrap.js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js', array('jquery'), '4.4.1', true);
    wp_register_script('respond.js', 'https://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js', array('jquery'), '1.4.2', true);
    
    // additional packages
    wp_register_script('slick.js', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js', array('jquery'), '1.9.0', true);
    spr_add_js_packages($themeurl); // adds js packages specified in config.php for specific implementation
    
    // custom header / footer scripts
    wp_register_script('header.js', $themeurl . '/js/header.js', array('jquery'), '1.0.0', false);
    wp_register_script('footer.js', $themeurl . '/js/footer.js', array('jquery'), '1.0.0', false);
	
    // drop all custom js for the implementation in global.js
    wp_register_script('global.js', $themeurl . '/js/global.js', array('jquery'), '1.0.0', false);
    
    wp_enqueue_script('jquery');
    wp_enqueue_script('slick.js');
    wp_enqueue_script('popper.js');
    wp_enqueue_script('bootstrap.js');
    wp_enqueue_script('respond.js');
    wp_enqueue_script('header.js');
    wp_enqueue_script('footer.js');
    wp_enqueue_script('global.js');
    
    $data = array('path' => get_stylesheet_directory_uri());
    wp_localize_script('global.js', 'theme', $data);
    
}
add_action( 'wp_enqueue_scripts', 'spr_add_theme_scripts' );


?>