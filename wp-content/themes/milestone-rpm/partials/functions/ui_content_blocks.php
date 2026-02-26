<?php

$version = '1.0.0';

// ======================================= //
//            CONTENT BLOCKS UI            //
// ======================================= // 


// remove author meta from shared  links
if (function_exists('wpseo_init')) {
	add_filter( 'wpseo_meta_author', '__return_false' );
	add_filter('wpseo_enhanced_slack_data', '__return_false' );
}


// add icons to Springboard blocks
function spr_block_titles($title, $field, $layout, $i) {
    $blocktype = $layout['name'];
    if (is_array(get_sub_field(str_replace('block_', '', $blocktype) . '_display'))) {
        $blockname = get_sub_field(str_replace('block_', '', $blocktype) . '_display')['block_name'];
    }
    if (!isset($blockname) || $blockname == '') {
        $title = '<span class="icon ' . $blocktype . '">' . $layout['label'] . '</span>';
    } else {
        $title = '<span class="icon ' . $blocktype . '">' . $blockname . ' </span><span class="block-type">(' . $layout['label'] . ')</span>';
    }
    return $title;
}
add_filter('acf/fields/flexible_content/layout_title/name=content_blocks', 'spr_block_titles', 10, 4);



?>