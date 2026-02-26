<?php


//
//    ____             _             ____                      _ 
//   / ___| _ __  _ __(_)_ __   __ _| __ )  ___   __ _ _ __ __| |
//   \___ \| '_ \| '__| | '_ \ / _` |  _ \ / _ \ / _` | '__/ _` |
//    ___) | |_) | |  | | | | | (_| | |_) | (_) | (_| | | | (_| |
//   |____/| .__/|_|  |_|_| |_|\__, |____/ \___/ \__,_|_|  \__,_|
//         |_|                 |___/                             
//
//   To incorporate springboard modularly into a site, remember to do the following:
//
//      1) Add config.php to the theme directory and...
//
//         - Update global site url variables at the top
//         - Update the field key for the site's Content Blocks variable content field
//         - Update the path to the springboard folder
//
//      2) Add the following line to style.scss, making sure to update the path accordingly
//
//         // springboard content blocks
//         @import '../../partials/_springboard/_blocks.scss';
//
//      3) Add a require statement at the very top of functions.php to load this file
//
//         require('partials/_springboard/_springboard.php');
//
//


$themedir = get_template_directory();
$themeurl = get_template_directory_uri();

// custom settings are stored in config.php
require($themedir . '/config.php');
$sprpath = $GLOBALS['springboard_path'];



// ======================================= //
//          SPRINGBOARD FUNCTIONS          //
// ======================================= // 

// load init functions
require($themedir . $sprpath . '/functions/init.php');

// load block functions
require($themedir . $sprpath . '/functions/blocks_copy.php');
require($themedir . $sprpath . '/functions/blocks_updater.php');

// load compiler functions
require($themedir . $sprpath . '/functions/compiler_css.php');

// load theme functions
require($themedir . $sprpath . '/functions/theme_updater.php');

// load UI functions
require($themedir . $sprpath . '/functions/ui_content_blocks.php');
require($themedir . $sprpath . '/functions/ui_excerpts.php');
require($themedir . $sprpath . '/functions/ui_global_info.php');
require($themedir . $sprpath . '/functions/ui_lead_attribution.php');
require($themedir . $sprpath . '/functions/ui_people_posts.php');
require($themedir . $sprpath . '/functions/ui_svg_support.php');
require($themedir . $sprpath . '/functions/ui_taxonomy.php');
require($themedir . $sprpath . '/functions/ui_tinymce.php');
require($themedir . $sprpath . '/functions/ui_utilities.php');
require($themedir . $sprpath . '/functions/ui_wp_menus.php');




// ======================================= //
//     SPRINGBOARD CONTENT BLOCK MGMT      //
// ======================================= // 

// Add content block fields
function spr_add_blocks_acf() {
    $sprpath = $GLOBALS['springboard_path'];
    $themedir = get_template_directory();
	if (function_exists('acf_add_local_field_group')):
		require($themedir . $sprpath . '/_blocks.acf');
        require($themedir . $sprpath . '/custom/_custom_colors.php');
	endif;
}
add_action('init', 'spr_add_blocks_acf', 9999999999);


// ======================================= //
//           ADD CONTENT BLOCKS            //
// ======================================= // 

require($themedir . $sprpath . '/_content_blocks.php');


// ======================================= //
//         REGISTER ADMIN JS & CSS         //
// ======================================= // 

// admin css stylesheet
function spr_load_admin_style() {
    $sprpath = $GLOBALS['springboard_path'];
    $themeurl = get_template_directory_uri();
    wp_enqueue_style('style', $themeurl . '/admin.css');
    wp_register_script('admin.js', $themeurl . $sprpath . '/admin/js/admin.js', array('jquery'), '1.0.0', false);
    wp_enqueue_script('admin.js');
}
add_action( 'admin_enqueue_scripts', 'spr_load_admin_style' );


?>