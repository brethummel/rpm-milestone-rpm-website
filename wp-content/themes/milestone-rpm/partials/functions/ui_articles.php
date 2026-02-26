<?php

$version = '1.0.0';

// ======================================= //
//           REGISTER POST TYPE            //
// ======================================= // 

if ($usearticles == true) {
    
    add_action( 'init', function() use ($peopleposts) {
        $labels = [
            "name" => "Articles",
            "singular_name" => "Article",
        ];
        $args = [
            "label" => "Articles",
            "labels" => $labels,
            "description" => "",
            "public" => true,
            "publicly_queryable" => true,
            "show_ui" => true,
            "show_in_rest" => true,
            "rest_base" => "",
            "rest_controller_class" => "WP_REST_Posts_Controller",
            "has_archive" => false,
            "show_in_menu" => true,
            "show_in_nav_menus" => true,
            "delete_with_user" => false,
            "exclude_from_search" => false,
            "capability_type" => "post",
            "map_meta_cap" => true,
            "hierarchical" => false,
            "rewrite" => [ "slug" => "article", "with_front" => true ],
            "query_var" => true,
            "menu_icon" => "dashicons-index-card",
            "supports" => [ "title", "editor", "thumbnail", "revisions" ],
            "taxonomies" => [ "category", "post_tag" ],
            "show_in_graphql" => false,
        ];
        register_post_type( "article", $args );
    });
    
}


// ======================================= //
//           FLUSH REWRITE RULES           //
// ======================================= // 

if ($usearticles) {

    // first time an article is saved, set a flag to flush the rewrite rules
    function spr_first_article_save( Int $post_id = null, \WP_Post $post_object = null ) {
        if (!$post_id || !$post_object) { 
            return false; 
        }
        if ($post_object->post_type != 'article') { 
            return false; 
        } // only do this for articles
        if (!get_option('first-article-rewrite-flush')) {  // if option doesn't exist, set it to 1
            update_option('first-article-rewrite-flush', 1);
        }
        return true;
    }
    add_action('save_post', 'spr_first_article_save', 10, 2);

    // if the flag is set, flush the rewrite rules and set flag to 2 (done)
    function spr_first_article_flush() {
        if (!$option = get_option('first-article-rewrite-flush')) {  // option doesn't exist yet 
            return false; 
        }
        if ($option == 1) {
            flush_rewrite_rules( false );
            update_option('first-article-rewrite-flush', 2); // record flush as having happened
        }
        return true;
    }
    add_action('init', 'spr_first_article_flush', 9999999);

}


?>