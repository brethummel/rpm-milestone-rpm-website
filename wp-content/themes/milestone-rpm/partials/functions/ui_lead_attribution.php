<?php

$version = '1.0.0';

$GLOBALS['leadattribution'] = $leadattribution;

// ======================================= //
//            LEAD ATTRIBUTION             //
// ======================================= // 

// lead_attribution.js
function spr_add_lead_attribution_scripts() {
    $GLOBALS['leadattribution'] = $leadattribution;
    if ($leadattribution) {
        wp_register_script('lead_attribution.js', get_template_directory_uri() . '/partials/admin/lead_attribution.js', array('jquery'), '0.4.0', true);
        wp_enqueue_script('lead_attribution.js');
    }
}
add_action('wp_enqueue_scripts', 'spr_add_lead_attribution_scripts');


// ======================================= //
//             CREATE FIELDSET             //
// ======================================= // 

function spr_add_lead_attribution_fields() {
    if ($leadattribution && function_exists('acf_add_local_field_group')) {

        // LEAD ATTRIBUTION
        acf_add_local_field_group(array(
            'key' => 'group_62675f4b67bf2',
            'title' => 'Default Lead Attribution',
            'fields' => array(
                array(
                    'key' => 'field_626760773a0f2',
                    'label' => 'Note',
                    'name' => '',
                    'type' => 'message',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => 'Enter the campaign and source values that <strong>new, direct-traffic leads</strong> will be attributed to by default if no other values are specified elsewhere.',
                    'new_lines' => 'wpautop',
                    'esc_html' => 0,
                ),
                array(
                    'key' => 'field_62675f563a0ef',
                    'label' => 'Lead Attribution',
                    'name' => 'attribution',
                    'type' => 'group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'layout' => 'table',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_62695359ce7fd',
                            'label' => 'Set Campaign To',
                            'name' => 'campaign_setting',
                            'type' => 'select',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'choices' => array(
                                'slug' => 'Permalink',
                                'value' => 'Global Value',
                            ),
                            'default_value' => 'slug',
                            'allow_null' => 0,
                            'multiple' => 0,
                            'ui' => 0,
                            'return_format' => 'value',
                            'ajax' => 0,
                            'placeholder' => '',
                        ),
                        array(
                            'key' => 'field_62695574ce7fe',
                            'label' => 'Campaign',
                            'name' => 'campaign_permalink',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => array(
                                array(
                                    array(
                                        'field' => 'field_62695359ce7fd',
                                        'operator' => '==',
                                        'value' => 'slug',
                                    ),
                                ),
                            ),
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => 'Ex: ' . $GLOBALS['produrl'] . ' (home)',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'readonly' => 1,
                            'maxlength' => '',
                        ),
                        array(
                            'key' => 'field_6267605b3a0f0',
                            'label' => 'Campaign',
                            'name' => 'campaign',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 1,
                            'conditional_logic' => array(
                                array(
                                    array(
                                        'field' => 'field_62695359ce7fd',
                                        'operator' => '==',
                                        'value' => 'value',
                                    ),
                                ),
                            ),
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
                            'key' => 'field_626760693a0f1',
                            'label' => 'Source',
                            'name' => 'source',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 1,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => 'Website (direct traffic)',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                        array(
                            'key' => 'field_62676093a0pq3',
                            'label' => 'Medium',
                            'name' => 'medium',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 1,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => 'website',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                        array(
                            'key' => 'field_626ab4b7583ca',
                            'label' => 'Attribution Model',
                            'name' => 'model',
                            'type' => 'select',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'choices' => array(
                                'first' => 'First Source',
                                'last' => 'Last Source',
                                'last-non-direct' => 'Last Non-Direct Source',
                            ),
                            'default_value' => 'last-non-direct',
                            'allow_null' => 0,
                            'multiple' => 0,
                            'ui' => 0,
                            'return_format' => 'value',
                            'ajax' => 0,
                            'placeholder' => '',
                        ),
                    ),
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'options_page',
                        'operator' => '==',
                        'value' => 'acf-options-global-info',
                    ),
                ),
            ),
            'menu_order' => 25,
            'position' => 'acf_after_title',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
            'show_in_rest' => 0,
        ));
        
    }
}
add_action('acf/init', 'spr_add_lead_attribution_fields', 10);


?>