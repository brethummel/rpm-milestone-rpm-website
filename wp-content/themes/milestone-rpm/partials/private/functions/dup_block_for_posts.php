<?php

// ======================================= //
//     DUPLICATE storysidebar BLOCK ON POSTS     //
// ======================================= // 

function spr_conditionally_override_content_blocks($value, $post_id, $field) {
	$foundstorysidebar = false;
	if (is_single() && get_post_type($post_id) === 'post') {
		foreach ($value as $b => $block) {
			if ($block['acf_fc_layout'] == 'block_storysidebar') {
				$storysidebarblock = $block;
				$storysidebarblock['field_oGWmj7sT17BRb_field_68753b4171a1b']['field_68753b4fcab70_field_6178670b4ade0'] = 'padded bkgnd-light light';
				$foundstorysidebar = true;
			}
			if ($block['acf_fc_layout'] == 'block_section' && $block['field_f8nsj1wt4c6tb_field_643609e8323b7']['field_643609eb08e37_field_6178670b4ade0'] == 'post-cta') {
				$firstchunkend = $b - 1;
				$ctablock = $block;
				$lastchunkstart = $b + 1;
			}
		}
		if ($foundstorysidebar) {
			$firstchunk = array_slice($value, 0, $firstchunkend + 1);
			$lastchunk = array_slice($value, $lastchunkstart);
			$value = array_merge(
				$firstchunk,
				isset($storysidebarblock) ? [$storysidebarblock] : [],
				isset($ctablock) ? [$ctablock] : [],
				$lastchunk
			);
		}
	}
	return $value;
}
add_filter('acf/load_value/name=content_blocks', 'spr_conditionally_override_content_blocks', 10, 3);

?>