<?php


// ======================================= //
//        PATH TO SPRINGBOARD FOLDER       //
// ======================================= //

// This should be the path to the primary springboard folder

$GLOBALS['springboard_path'] = '/partials';


// ======================================= //
//              QUICK CONFIG               //
// ======================================= //

$usemenus = false; // enables admin menu management (not fully implemented)
$multilocs = false; // true modifies Contact Info fields to allow multiple locations
$sitebanner = true; // true adds Site Banner fields to Contact Info page
$leadattribution = false; // true enables lead attribution: see section below
$allowsvgs = true; // true enables upload of svg files into media library
$layerslider = false; // true enables layerslider type in block_hero
$mcestyles = true; // show custom styles dropdown in WYSIWYG editor

define('scs_google_maps_api_key', '');
define('scs_google_geocoding_api_key', '');

$GLOBALS['produrl'] = 'milestoneconsultinggroup.com';
$GLOBALS['stageurl'] = 'milestoneconsultinggroupcom.stage.site';
$GLOBALS['stageuser'] = 'staging_qdfsm6';
$GLOBALS['stagepass'] = 'Yhui27KbRxin';


// ======================================= //
//             ADD THEME FONTS             //
// ======================================= // 

function spr_add_theme_fonts($themeurl) {
    wp_enqueue_style('adobe-fonts', 'https://use.typekit.net/ven4mez.css');
    wp_enqueue_style('typicons', $themeurl . '/fonts/Typicons/typicons.min.css');
    wp_enqueue_style('microns', $themeurl . '/fonts/Microns/microns.css');
}


// ======================================= //
//            DEFINE POST TYPES            //
// ======================================= // 

$GLOBALS['posttypes'] = array(
	// 'page' => array('blocks', array('image', 'title', 'excerpt'), 'relationships', 'taxonomy'), // excerpt = manually entered
	// 'page' => array('blocks', array('image', 'title', 'snippet'), 'relationships', 'taxonomy'), // snippet = first xx chars of text
	// 'page' => array('blocks', 'post_title', 'relationships', 'taxonomy'), // uses the existing post title
	'page' => array('blocks', 'post_title', 'relationships'),
	'post' => array('blocks', array('image', 'title', 'excerpt'), 'relationships'),
	// 'article' => array('blocks', array('image', 'title', 'excerpt'), 'relationships'),
	// 'news' => array(array('image', 'title', 'excerpt'), 'relationships')
	// add additional post types here
);


// ======================================= //
//             CONTENT BLOCKS              //
// ======================================= //

// This should be the field key of the site's main variable content field
// that contains the content blocks used to build out site pages. If this is
// a legacy site, change this to the site's main content blocks field,
// otherwise leave this alone.

$GLOBALS['content_blocks'] = 'field_610ac7050d4ff';


// ======================================= //
//                USE BLOCKS               //
// ======================================= //

$useblocks = array(

	// ----------- MILD BLOCKS ----------- //
	
	'block_accordion' => true,
	'block_anchor' => true,
	'block_bio' => true,
	'block_buttons' => true,
	'block_contactform' => true,
	'block_fullimage' => true,
	'block_hero' => true,
	'block_legal' => true,
	'block_peoplegrid' => true,
	'block_posts' => true,
	'block_published' => false,
	'block_pullquote' => true,
	'block_rule' => false,
	'block_share' => false,
	'block_strip' => true,
	'block_tabs' => false,
	'block_testimonials' => true,
	'block_text' => true,
	'block_textimage' => true,
	'block_tiles' => true,

	// ---------- MEDIUM BLOCKS ---------- //

	'block_audioplayer' => false,
	'block_gallery' => true,
	'block_layerslider' => false,
	'block_logogrid' => false,
	'block_photostrip' => false,
	'block_resources' => true,
	'block_ticker' => false,
	
	// ---------- SPICY BLOCKS ----------- //

	'block_map' => false,
	'block_related' => true,

);

$GLOBALS['useblocks'] = $useblocks;


// ======================================= //
//            PRIVATE FUNCTIONS            //
// ======================================= //

// If you need to write custom functionality that would normally go in functions.php,
// instead, create a functions file inside /partials/custom/functions and require
// it here.

add_action('acf/init', function() {
	$sprpath = $GLOBALS['springboard_path'];
	$themedir = get_template_directory();
	require($themedir . $sprpath . '/private/functions/dup_block_for_posts.php');
});


// ======================================= //
//         STYLE FORMATS DROPDOWN          //
// ======================================= // 

$styleformats = array(
	array(  
		'title' => 'Black',  
		'inline' => 'span',  
		'classes' => 'black',
		'wrapper' => true,
	),
	// array(  
	// 	'title' => 'Blue',  
	// 	'inline' => 'span',  
	// 	'classes' => 'blue',
	// 	'wrapper' => true,
	// ),
	// array(  
	// 	'title' => 'White',  
	// 	'inline' => 'span',
	// 	'classes' => 'white',
	// 	'wrapper' => true,
	// ),
	// array(  
	// 	'title' => 'Intro Text',  
	// 	'inline' => 'span',  
	// 	'classes' => 'intro',
	// 	'wrapper' => true,
	// ),
);
$GLOBALS['styleformats'] = $styleformats;


// ======================================= //
//          ADD ADDTL JS PACKAGES          //
// ======================================= // 

function spr_add_js_packages($themeurl) {
	
	// lottie-player.js
    // wp_register_script('lottie-player.js', 'https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js', array('jquery'), '0.4.0', true);
    // wp_enqueue_script('lottie-player.js');
	
	// lottie-interactivity.js
	// wp_register_script('lottie-interactivity.js', 'https://unpkg.com/@lottiefiles/lottie-interactivity@latest/dist/lottie-interactivity.min.js', array('jquery'), '0.4.0', true);
    // wp_enqueue_script('lottie-interactivity.js');
	
	// lottie-interactive.js
    // wp_enqueue_script('lottie-interactive.js');
	// wp_register_script('lottie-interactive.js', 'https://unpkg.com/lottie-interactive@latest/dist/lottie-interactive.js', array('jquery'), '0.4.0', true);
	
	// skrollr.js
	// wp_register_script('skrollr.js', 'https://cdnjs.cloudflare.com/ajax/libs/skrollr/0.6.30/skrollr.min.js', array('jquery'), '0.4.0', true);
    // wp_enqueue_script('skrollr.js');
	
	// lax.js
	// wp_register_script('lax.js', 'https://cdn.jsdelivr.net/npm/lax.js', array('jquery'), '2.0.3', true);
    // wp_enqueue_script('lax.js');
	
	// rolly.js
	// wp_register_script('rolly.js', 'https://unpkg.com/rolly.js@0.4.0/dist/rolly.min.js', array('jquery'), '0.4.0', true);
    // wp_enqueue_script('rolly.js');
	// wp_enqueue_style('rolly', 'https://unpkg.com/rolly.js@0.4.0/css/style.css');
}


// ======================================= //
//        PRIORITY METABOXES ORDER         //
// ======================================= // 

// each item in this array should have a post type as it's key,
// then in the child array, add the id of each meta box in the
// order you want them to appear after the Publish meta box.
$priority_metaboxes = array(
// 	'providers' => array (
// 		'acf-group_65f9e6e917912',
// 		'acf-group_65f36d141596e',
// 		'acf-group_65f4c8201cd49',
// 		'acf-group_610ac6ffac0e4',
// 	)
);
$GLOBALS['priority_metaboxes'] = $priority_metaboxes;


// ======================================= //
//             SIDEBAR ORDER               //
// ======================================= // 

// each item in this array should have a post type as it's key,
// then in the child array, add the id of each meta box in the
// order you want them to appear after the Publish meta box.

$priority_sidebars = array(
	'page' => array(
		'acf-group_663bbd8a7942a',
	),
	'post' => array(
		'acf-group_683dc8509ac6e',
		'acf-group_5ea063c4b5bda'
	)
);
$GLOBALS['priority_sidebars'] = $priority_sidebars;


// ======================================= //
//            LEAD ATTRIBUTION             //
// ======================================= //

// To complete the loop on lead attribution, you must have a lead form that has a source
// and campaign field in it, then specify the element IDs of their corresponding input
// fieds using $attribution_fieldmap below.

$attribution_fieldmap = array(
	'campaign_field' => 'field[154]',  // specify form field ID for campaign field
	'source_field' => 'field[155]',  // specify form field ID for source field
	'attrsrc_field' => 'field[156]',  // specify form field ID for source field
	'visits_field' => array(
		'field' => 'field[157]',
		'delimiter' => ' | ',
	),  // specify form field ID for page visits field
);
$GLOBALS['attribution_fieldmap'] = $attribution_fieldmap;
    
// how long until attribution cookie should expire?
//                 (yy * ddd * hh * mm * ss)
$cookie_lifespan = (10 * 365 * 24 * 60 * 60);
$GLOBALS['cookie_lifespan'] = $cookie_lifespan;


// ======================================= //
//        PEOPLE POSTS CUSTOMIZATION       //
// ======================================= // 

$peopleposts = array( // add post type for people and add functionality to block_peoplegrid to populate from them
	'use' => false,
	'department' => false, // adds additional field after Title
	'department_title' => 'Department', // field title for department field
	'slug' => 'provider',
	'singular' => 'Provider',
	'plural' => 'Providers',
	'icon' => 'dashicons-businessman'
);
$GLOBALS['peopleposts'] = $peopleposts;


// ======================================= //
//        PEOPLE POSTS CUSTOM FIELDS       //
// ======================================= //

function spr_add_custom_bio_fields() { // add field
	if( function_exists('acf_add_local_field_group') ):
		acf_add_local_field(array(
			'key' => 'field_662047d80aa9e2',
			'label' => 'Certifications',
			'name' => 'certifications',
			'type' => 'textarea',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'rows' => 3,
			'placeholder' => '',
			'parent' => 'field_662047cb0aa9d',
			'new_lines' => 'wpautop',
		));

	endif;
}
// add_action('acf/init', 'spr_add_custom_bio_fields', 10);


function spr_sort_bio_fields($field) {
	$subfields = $field['sub_fields'];
	$certifications = $subfields[0];
	unset($subfields[0]);
	array_push($subfields, $certifications);
	$field['sub_fields'] = $subfields;
	return $field;
}
// add_filter('acf/prepare_field/key=field_662047cb0aa9d', 'spr_sort_bio_fields');


// ======================================= //
//          ACF ADD CUSTOM SOCIAL          //
// ======================================= //

function spr_add_custom_social($field) {
	
	// ------------------- PINTEREST ------------------- //

	// $field['sub_fields'][] = array(
	// 	'key' => 'field_mql3suabwviq1',
	// 	'label' => 'Pinterest',
	// 	'name' => 'pinterest',
	// 	'type' => 'group',
	// 	'instructions' => '',
	// 	'required' => 0,
	// 	'id' => '',
	// 	'class' => '',
	// 	'conditional_logic' => array(
	// 		array(
	// 			array(
	// 				'field' => 'field_5c2ff7e78c7a5',
	// 				'operator' => '==',
	// 				'value' => '1',
	// 			),
	// 		),
	// 	),
	// 	'wrapper' => array(
	// 		'width' => '',
	// 		'class' => '',
	// 		'id' => '',
	// 	),
	// 	'layout' => 'table',
	// 	'sub_fields' => array(
	// 		array(
	// 			'key' => 'field_mql3suabwviq1a',
	// 			'label' => 'Include?',
	// 			'name' => 'pinterest_include',
	// 			'type' => 'true_false',
	// 			'instructions' => '',
	// 			'required' => 0,
	// 			'id' => '',
	// 			'class' => '',
	// 			'conditional_logic' => 0,
	// 			'wrapper' => array(
	// 				'width' => '20',
	// 				'class' => '',
	// 				'id' => '',
	// 			),
	// 			'message' => '',
	// 			'default_value' => 0,
	// 			'ui' => 1,
	// 			'ui_on_text' => '',
	// 			'ui_off_text' => '',
	// 			'_name' => 'pinterest_include',
	// 			'_valid' => 1
	// 		),
	// 		array(
	// 			'key' => 'field_mql3suabwviq1b',
	// 			'label' => 'Pinterest',
	// 			'name' => 'pinterest_url',
	// 			'type' => 'url',
	// 			'instructions' => '',
	// 			'required' => 1,
	// 			'id' => '',
	// 			'class' => '',
	// 			'conditional_logic' => array(
	// 				array(
	// 					array(
	// 						'field' => 'field_mql3suabwviq1a',
	// 						'operator' => '==',
	// 						'value' => '1',
	// 					),
	// 				),
	// 			),
	// 			'wrapper' => array(
	// 				'width' => '80',
	// 				'class' => '',
	// 				'id' => '',
	// 			),
	// 			'default_value' => 'http://www.pinterest.com/',
	// 			'placeholder' => '',
	// 			'_name' => 'pinterest_url',
	// 			'_valid' => 1
	// 		),
	// 	),
	// 	'_name' => 'pinterest',
	// 	'_valid' => 1
	// );

	// --------------------- HOUZZ --------------------- //
	
	// $field['sub_fields'][] = array(
	// 	'key' => 'field_mql3suabwviq2',
	// 	'label' => 'Houzz',
	// 	'name' => 'houzz',
	// 	'type' => 'group',
	// 	'instructions' => '',
	// 	'required' => 0,
	// 	'id' => '',
	// 	'class' => '',
	// 	'conditional_logic' => array(
	// 		array(
	// 			array(
	// 				'field' => 'field_5c2ff7e78c7a5',
	// 				'operator' => '==',
	// 				'value' => '1',
	// 			),
	// 		),
	// 	),
	// 	'wrapper' => array(
	// 		'width' => '',
	// 		'class' => '',
	// 		'id' => '',
	// 	),
	// 	'layout' => 'table',
	// 	'sub_fields' => array(
	// 		array(
	// 			'key' => 'field_mql3suabwviq2a',
	// 			'label' => 'Include?',
	// 			'name' => 'houzz_include',
	// 			'type' => 'true_false',
	// 			'instructions' => '',
	// 			'required' => 0,
	// 			'id' => '',
	// 			'class' => '',
	// 			'conditional_logic' => 0,
	// 			'wrapper' => array(
	// 				'width' => '20',
	// 				'class' => '',
	// 				'id' => '',
	// 			),
	// 			'message' => '',
	// 			'default_value' => 0,
	// 			'ui' => 1,
	// 			'ui_on_text' => '',
	// 			'ui_off_text' => '',
	// 			'_name' => 'houzz_include',
	// 			'_valid' => 1
	// 		),
	// 		array(
	// 			'key' => 'field_mql3suabwviq2b',
	// 			'label' => 'Houzz',
	// 			'name' => 'houzz_url',
	// 			'type' => 'url',
	// 			'instructions' => '',
	// 			'required' => 1,
	// 			'id' => '',
	// 			'class' => '',
	// 			'conditional_logic' => array(
	// 				array(
	// 					array(
	// 						'field' => 'field_mql3suabwviq2a',
	// 						'operator' => '==',
	// 						'value' => '1',
	// 					),
	// 				),
	// 			),
	// 			'wrapper' => array(
	// 				'width' => '80',
	// 				'class' => '',
	// 				'id' => '',
	// 			),
	// 			'default_value' => 'http://www.houzz.com/',
	// 			'placeholder' => '',
	// 			'_name' => 'houzz_url',
	// 			'_valid' => 1
	// 		),
	// 	),
	// 	'_name' => 'houzz',
	// 	'_valid' => 1
	// );
	
	// --------------------- VIMEO --------------------- //
	
	// $field['sub_fields'][] = array(
	// 	'key' => 'field_mql3suabwviq3',
	// 	'label' => 'Vimeo',
	// 	'name' => 'vimeo',
	// 	'type' => 'group',
	// 	'instructions' => '',
	// 	'required' => 0,
	// 	'id' => '',
	// 	'class' => '',
	// 	'conditional_logic' => array(
	// 		array(
	// 			array(
	// 				'field' => 'field_5c2ff7e78c7a5',
	// 				'operator' => '==',
	// 				'value' => '1',
	// 			),
	// 		),
	// 	),
	// 	'wrapper' => array(
	// 		'width' => '',
	// 		'class' => '',
	// 		'id' => '',
	// 	),
	// 	'layout' => 'table',
	// 	'sub_fields' => array(
	// 		array(
	// 			'key' => 'field_mql3suabwviq3a',
	// 			'label' => 'Include?',
	// 			'name' => 'vimeo_include',
	// 			'type' => 'true_false',
	// 			'instructions' => '',
	// 			'required' => 0,
	// 			'id' => '',
	// 			'class' => '',
	// 			'conditional_logic' => 0,
	// 			'wrapper' => array(
	// 				'width' => '20',
	// 				'class' => '',
	// 				'id' => '',
	// 			),
	// 			'message' => '',
	// 			'default_value' => 0,
	// 			'ui' => 1,
	// 			'ui_on_text' => '',
	// 			'ui_off_text' => '',
	// 			'_name' => 'vimeo_include',
	// 			'_valid' => 1
	// 		),
	// 		array(
	// 			'key' => 'field_mql3suabwviq3b',
	// 			'label' => 'Vimeo',
	// 			'name' => 'vimeo_url',
	// 			'type' => 'url',
	// 			'instructions' => '',
	// 			'required' => 1,
	// 			'id' => '',
	// 			'class' => '',
	// 			'conditional_logic' => array(
	// 				array(
	// 					array(
	// 						'field' => 'field_mql3suabwviq3a',
	// 						'operator' => '==',
	// 						'value' => '1',
	// 					),
	// 				),
	// 			),
	// 			'wrapper' => array(
	// 				'width' => '80',
	// 				'class' => '',
	// 				'id' => '',
	// 			),
	// 			'default_value' => 'http://www.vimeo.com/',
	// 			'placeholder' => '',
	// 			'_name' => 'vimeo_url',
	// 			'_valid' => 1
	// 		),
	// 	),
	// 	'_name' => 'vimeo',
	// 	'_valid' => 1
	// );
	
	return $field;
	
}
// add_filter('acf/load_field/key=field_5c2fedddafd87', 'spr_add_custom_social', 10, 4);


// ======================================= //
//    GLOBAL INFO CUSTOM CONTACT FIELDS    //
// ======================================= //

function spr_add_custom_contact_fields() {
	if( function_exists('acf_add_local_field_group') ):
		acf_add_local_field(array(
			'key' => 'field_3ohxl2fg7ulh',
			'label' => 'Hours',
			'name' => 'hours',
			'type' => 'textarea',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'rows' => 3,
			'placeholder' => '',
			'parent' => 'group_5fc94ae7a614c',
			'new_lines' => 'wpautop',
		), 1);
		acf_add_local_field(array(
			'key' => 'field_sfx8uayw58oz',
			'label' => 'Appointments Number',
			'name' => 'appointments_number',
			'type' => 'text',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'parent' => 'group_5fc94ae7a614c',
			'maxlength' => '',
		), 1);
	endif;
}
// add_action('acf/init', 'spr_add_custom_contact_fields', 10);


?>