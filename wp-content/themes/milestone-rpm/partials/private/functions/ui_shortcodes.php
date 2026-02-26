<?php

// ======================================= //
//            CUSTOM SHORTCODES            //
// ======================================= // 

function spr_shortcode_init() {
	add_shortcode('site-url', 'spr_insert_site_url');
}
add_action('init', 'spr_shortcode_init');

function spr_insert_site_url() {
	return get_site_url();
}

?>