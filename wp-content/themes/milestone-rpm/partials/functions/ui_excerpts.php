<?php

$version = '1.0.0';

// ======================================= //
//              USE EXCERPTS               //
// ======================================= // 

if (!isset($useexcerpts)) {
    $useexcerpts = false; // backwards compatibility 
}

if (array_key_exists('page', $GLOBALS['posttypes'])) { // new method
    $excerptlocations = [];
    foreach ($GLOBALS['posttypes'] as $t => $type) {
        if (in_array('excerpts', $type)) { // backwards compatability
            $useexcerpts = true;
            $excerptlocations[] = $t;
        } elseif (in_array('post_title', $type)) {
            $useexcerpts = true;
            $excerptlocations[] = $t;
        } else {
            foreach ($type as $term) {
                // echo('<pre>'); echo('seeing if ' . $term . ' is an array...'); echo('</pre>');
                if (gettype($term) == 'array') {
                    if (in_array('image', $term) || in_array('title', $term) || in_array('excerpt', $term)) {
                        $useexcerpts = true;
                        $excerptlocations[] = $t;
                    }
                }
            }
        }
    }
} else { // backwards compatability
    $excerptlocations = $GLOBALS['posttypes'];
    if ($useexcerpts || $useposts || $usearticles) {
        $useexcerpts = true;
    }
}

// ======================================= //
//            SET SIDEBAR ORDER            //
// ======================================= // 

// echo('<pre>'); print_r($excerptlocations); echo('</pre>');
$GLOBALS['excerptlocations'] = $excerptlocations;


if ($useexcerpts) { // if we're using excerpts
        
    // set order of sidebar meta boxes if using excerpts
    function spr_custom_order_sidebars($sidebar) {

        global $post;
        global $wp_meta_boxes;

        $posttype = $post->post_type;
        // echo('<pre>'); echo($posttype); echo('</pre>');

        if (in_array($posttype, $GLOBALS['excerptlocations'])) {

            // echo('<pre>'); echo("I think I'm in excerptlocations!"); echo('</pre>');
            $sidebar = $wp_meta_boxes[$posttype]['side']['core'];

            $submitdiv = $sidebar['submitdiv'];
            unset($sidebar['submitdiv']);
            $sortedmetas = array($submitdiv);
            
            if (isset($sidebar['acf-group_5ea063c4b5bda'])) {
                $excerpt = $sidebar['acf-group_5ea063c4b5bda'];
                unset($sidebar['acf-group_5ea063c4b5bda']);
                $sortedmetas[] = $excerpt;
            }

            foreach($sidebar as $meta) {
                array_push($sortedmetas, $meta);
            }

            $wp_meta_boxes[$posttype]['side']['core'] = $sortedmetas;

        }

    //  echo "<pre>";
    //  var_dump($wp_meta_boxes);
    //  echo "</pre>";  

    }
    add_action('acf/add_meta_boxes', 'spr_custom_order_sidebars');  
    
}


// ======================================= //
//         HIDE SIDEBAR METABOXES          //
// ======================================= // 

// make sure categories and tags metaboxes aren't hidden
function spr_set_hidden_meta_boxes( $hidden, $screen ) {
    if ($hidden != false) {
        if(array_search('tagsdiv-post_tag', $hidden) != false) {
            unset($hidden[array_search('tagsdiv-post_tag', $hidden)]);
        }
        if(array_search('categorydiv', $hidden) != false) {
            unset($hidden[array_search('categorydiv', $hidden)]);
        }
    }
    return $hidden;
}
add_filter( 'get_user_option_metaboxhidden_page', 'spr_set_hidden_meta_boxes', 10, 2 );
add_filter( 'get_user_option_metaboxhidden_post', 'spr_set_hidden_meta_boxes', 10, 2 );




if (!isset($usearticles)) {
    $usearticles = false; // backwards compatibility 
}
if (array_key_exists('article', $GLOBALS['posttypes'])) {
    $usearticles = true;
}
if ($usearticles) { // If the site uses articles
    add_filter( 'get_user_option_metaboxhidden_article', 'spr_set_hidden_meta_boxes', 10, 2 );
}

// close some meta boxes by default
function spr_closed_meta_boxes($closed) {
    if ( false === $closed ) {
        $closed = array( 'categorydiv', 'tagsdiv-post_tag', 'rocket_post_exclude', 'wpseo_meta', 'revisionsdiv' );
    }
    return $closed;
}
add_filter( 'get_user_option_closedpostboxes_page', 'spr_closed_meta_boxes', 10, 2 );
add_filter( 'get_user_option_closedpostboxes_post', 'spr_closed_meta_boxes', 10, 2 );

if ($usearticles) { // If the site uses articles
    add_filter( 'get_user_option_closedpostboxes_article', 'spr_closed_meta_boxes', 10, 2 );
}


// Add excerpt fields
if ($useexcerpts) { // already set above
    
    add_action('acf/include_fields', function() {
    
        if (!function_exists('acf_add_local_field_group')) {
            return;
        }
    
        $required = 0;
        $locations =  [];
        
        if (array_key_exists('page', $GLOBALS['posttypes'])) { // new method
            
            // echo('<pre>'); echo('using new excerpts method!'); echo('</pre>');
            
            foreach ($GLOBALS['excerptlocations'] as $t => $posttype) {

                // echo('<pre>'); print_r($GLOBALS['posttypes'][$posttype]); echo('</pre>');
                $showexcerptsmeta = false;
                if (in_array('excerpts', $GLOBALS['posttypes'][$posttype])) { // backwards compatability
                    $showexcerptsmeta = true;
                } else {
                    foreach ($GLOBALS['posttypes'][$posttype] as $term) {
                        if (gettype($term) == 'array') {
                            if (in_array('image', $term) || in_array('title', $term) || in_array('excerpt', $term)) {
                                $showexcerptsmeta = true;
                                if (!in_array('image', $term)) {
                                    add_filter('acf/prepare_field/key=field_5ea063dcc667f', 'spr_conditional_hide_field');
                                }
                                if (!in_array('title', $term)) {
                                    add_filter('acf/prepare_field/key=field_5ea063fcc6680', 'spr_conditional_hide_field');
                                }
                                if (!in_array('excerpt', $term)) {
                                    // echo('<pre>'); echo($posttype . '<br/>'); print_r($term); echo('</pre>');
                                    add_filter('acf/prepare_field/key=field_5ea0640ec6681', 'spr_conditional_hide_field');
                                }
                            }
                        }
                    }
                }
                if ($showexcerptsmeta) {
                    $required = 1;
                    $locations[] = array(
                        array(
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => $posttype,
                        )
                    );
                }
            }
            // echo('<pre>'); print_r($locations); echo('</pre>');
            
        } else { // backwards compatability

            // echo('<pre>'); echo('using old excerpts method!'); echo('</pre>');           
            if (!$GLOBALS['pageexcerpts']) {
                $locations[0][] =  array(
                    'param' => 'post_type',
                    'operator' => '!=',
                    'value' => 'page',
                );
            }
            foreach ($GLOBALS['posttypes'] as $posttype) {
                if ($posttype != 'page') {
                    $required = 1;
                    $locations[] = array(
                        array(
                            'param' => 'post_type',
                            'operator' => '==',
                                'value' => $posttype,
                        )
                    );
                }
            }
        }

        acf_add_local_field_group(array(
            'key' => 'group_5ea063c4b5bda',
            'title' => 'Excerpt',
            'fields' => array(
                array(
                    'key' => 'field_5ea063dcc667f',
                    'label' => 'Featured Image',
                    'name' => 'excerpt_image',
                    'type' => 'image',
                    'instructions' => 'Image template: <a href="https://updates.wp-springboard.com/templates/block_textimage.psd.zip">Text + Image</a>',
                    'required' => $required,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'return_format' => 'array',
                    'preview_size' => 'thumbnail',
                    'library' => 'all',
                    'min_width' => '',
                    'min_height' => '',
                    'min_size' => '',
                    'max_width' => '',
                    'max_height' => '',
                    'max_size' => '',
                    'mime_types' => '',
                ),
                array(
                    'key' => 'field_5ea063fcc6680',
                    'label' => 'Title',
                    'name' => 'excerpt_title',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => $required,
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
                    'maxlength' => '',
                ),
                array(
                    'key' => 'field_5ea0640ec6681',
                    'label' => 'Excerpt',
                    'name' => 'excerpt_excerpt',
                    'type' => 'textarea',
                    'instructions' => '',
                    'required' => $required,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'maxlength' => 175,
                    'rows' => 3,
                    'new_lines' => '',
                ),
            ),
            'location' => $locations,
            'menu_order' => 0,
            'position' => 'side',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => array(
                0 => 'the_content',
                1 => 'excerpt',
                2 => 'discussion',
                3 => 'comments',
                4 => 'format',
                5 => 'featured_image',
                6 => 'send-trackbacks',
            ),
            'active' => true,
            'description' => '',
            'show_in_rest' => 1,
        ));
        
    }, 9999999);
    
}


// ======================================= //
//                UTILITIES                //
// ======================================= // 

// conditionally hide acf field 
function spr_conditional_hide_field($field) {
    $curposttype = get_post_type();
    $fieldterm = strtolower($field['label']);
    foreach ($GLOBALS['posttypes'][$curposttype] as $term) {
        if (gettype($term) == 'array') {
            if (in_array($fieldterm, $term)) {
                return $field;
            } else {
                return false;
            }
        } else {

        }
    }
}


?>