<?php

$version = '1.0.2';

// ======================================= //
//          "ADD BUTTON" BUTTON            //
// ======================================= // 

// add "add button" button to tinymce
function spr_addbutton_init() {
	//abort early if the user will never see tinymce
	if (!current_user_can('edit_posts') && !current_user_can('edit_pages') && get_user_option('rich_editing') != 'true') {
		return;
	}
	add_filter("mce_external_plugins", "spr_register_tinymce_plugin"); // add callback to register our tinymce plugin
	add_filter('mce_buttons', 'spr_add_tinymce_button'); // add a callback to add our button to the tinymce toolbar
}
add_action('admin_init', 'spr_addbutton_init');

// this callback registers our plug-in
function spr_register_tinymce_plugin($plugin_array) {
    $sprpath = $GLOBALS['springboard_path'];
    $themeurl = get_template_directory_uri();
    $plugin_array['spr_addbutton_button'] = $themeurl . $sprpath . '/admin/js/admin.js';
    return $plugin_array;
}

// this callback adds our button to the toolbar
function spr_add_tinymce_button($buttons) {
    $buttons[] = "spr_addbutton_button"; // add the button ID to the $button array
    return $buttons;
}


// ======================================= //
//            FORMATS DROPDOWN             //
// ======================================= // 

// add formats dropdown to WYSIWYG editor
if ($mcestyles) {
	function spr_mce_buttons($buttons) {
		array_splice($buttons, 1, 0, 'styleselect');
		return $buttons;
	}
	add_filter('mce_buttons', 'spr_mce_buttons');
	
	function spr_tiny_mce_before_init($settings) {  
		// echo('Running tiny_mce_before_init');
		$settings['style_formats'] = json_encode($GLOBALS['styleformats']);
		return $settings;
	}
	add_filter('tiny_mce_before_init', 'spr_tiny_mce_before_init'); 
	
}


?>