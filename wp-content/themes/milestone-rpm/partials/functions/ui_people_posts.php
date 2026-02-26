<?php

$version = '1.0.0';

// ======================================= //
//           REGISTER POST TYPE            //
// ======================================= // 

if ($peopleposts['use'] == true) {
    
    add_action( 'init', function() use ($peopleposts) {
        $labels = [
            "name" => $peopleposts['plural'],
            "singular_name" => $peopleposts['singular'],
        ];
        $args = [
            "label" => $peopleposts['plural'],
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
            "rewrite" => [ "slug" => $peopleposts['slug'], "with_front" => true ],
            "query_var" => true,
            "menu_icon" => $peopleposts['icon'],
            "supports" => [ "title", "editor", "thumbnail" ],
            "taxonomies" => [ "category", "post_tag" ],
            "show_in_graphql" => false,
        ];
        register_post_type( $peopleposts['slug'], $args );
    });
    
}


// ======================================= //
//            CREATE FIELDSET              //
// ======================================= //

if ($peopleposts['use'] == true) {
    
    // // add fieldset
    // if( function_exists('acf_add_local_field_group') ):

    // acf_add_local_field_group(array(
    //  'key' => 'group_61c22b88f0f9a',
    //  'title' => $peopleposts['singular'],
    //  'fields' => array(
    //      array(
    //          'key' => 'field_61c22c5c3c0f9',
    //          'label' => 'Image',
    //          'name' => 'people_image',
    //          'type' => 'image',
    //          'instructions' => '',
    //          'required' => 1,
    //          'conditional_logic' => 0,
    //          'wrapper' => array(
    //              'width' => '',
    //              'class' => '',
    //              'id' => '',
    //          ),
    //          'return_format' => 'array',
    //          'preview_size' => 'thumbnail',
    //          'library' => 'all',
    //          'min_width' => '',
    //          'min_height' => '',
    //          'min_size' => '',
    //          'max_width' => '',
    //          'max_height' => '',
    //          'max_size' => '',
    //          'mime_types' => '',
    //      ),
    //      array(
    //          'key' => 'field_61c22b913c0f6',
    //          'label' => 'Person',
    //          'name' => 'people_person',
    //          'type' => 'group',
    //          'instructions' => '',
    //          'required' => 0,
    //          'conditional_logic' => 0,
    //          'wrapper' => array(
    //              'width' => '',
    //              'class' => '',
    //              'id' => '',
    //          ),
    //          'layout' => 'table',
    //          'sub_fields' => array(
    //              array(
    //                  'key' => 'field_61c22ba63c0f7',
    //                  'label' => 'Full Name',
    //                  'name' => 'name',
    //                  'type' => 'text',
    //                  'instructions' => '',
    //                  'required' => 1,
    //                  'conditional_logic' => 0,
    //                  'wrapper' => array(
    //                      'width' => '',
    //                      'class' => '',
    //                      'id' => '',
    //                  ),
    //                  'default_value' => '',
    //                  'placeholder' => '',
    //                  'prepend' => '',
    //                  'append' => '',
    //                  'maxlength' => '',
    //              ),
    //              array(
    //                  'key' => 'field_61c22c4e3c0f8',
    //                  'label' => 'Title',
    //                  'name' => 'title',
    //                  'type' => 'text',
    //                  'instructions' => '',
    //                  'required' => 1,
    //                  'conditional_logic' => 0,
    //                  'wrapper' => array(
    //                      'width' => '',
    //                      'class' => '',
    //                      'id' => '',
    //                  ),
    //                  'default_value' => '',
    //                  'placeholder' => '',
    //                  'prepend' => '',
    //                  'append' => '',
    //                  'maxlength' => '',
    //              ),
    //          ),
    //      ),
    //      array(
    //          'key' => 'field_61c22c7e3c0fa',
    //          'label' => 'Bio',
    //          'name' => 'people_bio',
    //          'type' => 'wysiwyg',
    //          'instructions' => '',
    //          'required' => 1,
    //          'conditional_logic' => 0,
    //          'wrapper' => array(
    //              'width' => '',
    //              'class' => '',
    //              'id' => '',
    //          ),
    //          'default_value' => '',
    //          'tabs' => 'all',
    //          'toolbar' => 'full',
    //          'media_upload' => 0,
    //          'delay' => 0,
    //      ),
    //  ),
    //  'location' => array(
    //      array(
    //          array(
    //              'param' => 'post_type',
    //              'operator' => '==',
    //              'value' => $peopleposts['slug'],
    //          ),
    //      ),
    //  ),
    //  'menu_order' => 0,
    //  'position' => 'acf_after_title',
    //  'style' => 'default',
    //  'label_placement' => 'top',
    //  'instruction_placement' => 'label',
    //  'hide_on_screen' => array(
    //      0 => 'the_content',
    //      1 => 'excerpt',
    //      2 => 'discussion',
    //      3 => 'comments',
    //      4 => 'author',
    //      5 => 'format',
    //      6 => 'featured_image',
    //      7 => 'send-trackbacks',
    //  ),
    //  'active' => true,
    //  'description' => '',
    //  'show_in_rest' => 0,
    // ));

    // endif;

}


// ======================================= //
//           FLUSH REWRITE RULES           //
// ======================================= // 

if ($peopleposts['use'] == true) {
    
    // first time a peoplepost is saved, set a flag to flush the rewrite rules
    add_action('save_post', function($post_id, $post_object) use ($peopleposts) {
        if (!$post_id || !$post_object) { 
            return false; 
        }
        if ($post_object->post_type != $peopleposts['slug']) { 
            return false; 
        } // only do this for the peoplepost post type
        if (!get_option('first-peoplepost-rewrite-flush')) {  // if option doesn't exist, set it to 1
            update_option('first-peoplepost-rewrite-flush', 1);
        }
        return true;
    }, 10, 2);
    
    // if the flag is set, flush the rewrite rules and set flag to 2 (done)
    function spr_first_peoplepost_flush() {
        if (!$option = get_option('first-peoplepost-rewrite-flush')) {  // option doesn't exist yet 
            return false; 
        }
        if ($option == 1) {
            flush_rewrite_rules( false );
            update_option('first-peoplepost-rewrite-flush', 2); // record flush as having happened
        }
        return true;
    }
    add_action('init', 'spr_first_peoplepost_flush', 9999999);
    
}


?>