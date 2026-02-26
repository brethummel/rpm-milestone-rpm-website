<?php

$version = '1.0.0';

// ======================================= //
//                TAXONOMY                 //
// ======================================= // 

if (!isset($pagetaxonomy)) {
	$pagetaxonomy = false; // backwards compatibility 
}

if (array_key_exists('page', $GLOBALS['posttypes'])) {
	if (in_array('excerpts', $GLOBALS['posttypes']['page'])) {
		$pagetaxonomy = true;
	}
} else { // backwards compatability
	if (!isset($pagetaxonomy)) {
		$pagetaxonomy = false;
	}
}

if ($pagetaxonomy) { // If we want categories and tags on pages
	
	function spr_add_categories_to_pages() {
		register_taxonomy_for_object_type( 'category', 'page' );
	}
	add_action( 'init', 'spr_add_categories_to_pages' );
	
	function spr_add_tags_to_pages() {
		register_taxonomy_for_object_type( 'post_tag', 'page' );
	}
	add_action( 'init', 'spr_add_tags_to_pages');

}

?>