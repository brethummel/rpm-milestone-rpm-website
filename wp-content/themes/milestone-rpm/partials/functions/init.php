<?php

$version = '1.0.0';

// ======================================= //
//        WRITE ADMIN.SCSS TO SASS         //
// ======================================= // 

$sprpath = $GLOBALS['springboard_path'];

// create admin.scss if it does not already exist
if (!file_exists($themedir . '/sass/stylesheets/admin.scss')) {
    $asass = PHP_EOL . "// admin blocks" . PHP_EOL . "@import '../.." . $sprpath . "/_blocks_admin.scss';";
    $adminscssfile = $themedir . '/sass/stylesheets/admin.scss';
    file_put_contents($adminscssfile, $asass);
}


// ======================================= //
//             ACF GOOGLE MAPS             //
// ======================================= // 

if (scs_google_maps_api_key != '') {
	function spr_acf_setting_init() {
		if (function_exists( 'acf_update_setting')) {
			acf_update_setting( 'google_api_key', scs_google_maps_api_key );
		}
	}
	add_action('acf/init', 'spr_acf_setting_init');
}


?>