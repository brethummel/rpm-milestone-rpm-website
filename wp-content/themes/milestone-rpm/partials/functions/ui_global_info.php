<?php

$version = '1.0.0';

// ======================================= //
//       ADD GLOBAL INFO OPTIONS PAGE      //
// ======================================= // 

// Add Contact Info options page
function spr_add_global_info_page() {
	if (function_exists('acf_add_options_page')){
		acf_add_options_page(array(
			'page_title' => 'Global Info',
			'icon_url' => 'dashicons-admin-site-alt3',
			'position' => 6
		));
	}
}
add_action('acf/init', 'spr_add_global_info_page', 10);


// ======================================= //
//         ADD SITE BANNER FIELDSET        //
// ======================================= // 

function spr_add_site_banner_fields($sitebanner) {
	if(function_exists('acf_add_local_field_group')):

		// Site Banner
		if ($sitebanner) {
			acf_add_local_field_group(array(
				'key' => 'group_62561146520fe',
				'title' => 'Site Banner',
				'fields' => array(
					array(
						'key' => 'field_6256115ad9756',
						'label' => 'Settings',
						'name' => 'sitebanner_settings',
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
								'key' => 'field_62561175d9757',
								'label' => 'Display',
								'name' => 'display',
								'type' => 'true_false',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array(
									'width' => '',
									'class' => '',
									'id' => '',
								),
								'message' => '',
								'default_value' => 0,
								'ui' => 1,
								'ui_on_text' => '',
								'ui_off_text' => '',
							),
							array(
								'key' => 'field_62561295d975b',
								'label' => 'Link Text',
								'name' => 'text',
								'type' => 'text',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => array(
									array(
										array(
											'field' => 'field_62561175d9757',
											'operator' => '==',
											'value' => '1',
										),
									),
								),
								'wrapper' => array(
									'width' => '',
									'class' => '',
									'id' => '',
								),
								'default_value' => 'Learn More',
								'placeholder' => '',
								'prepend' => '',
								'append' => '',
								'maxlength' => '',
							),
							array(
								'key' => 'field_62561226d975a',
								'label' => 'Type',
								'name' => 'type',
								'type' => 'select',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => array(
									array(
										array(
											'field' => 'field_62561175d9757',
											'operator' => '==',
											'value' => '1',
										),
										array(
											'field' => 'field_62561295d975b',
											'operator' => '!=empty',
										),
									),
								),
								'wrapper' => array(
									'width' => '',
									'class' => '',
									'id' => '',
								),
								'choices' => array(
									'internal' => 'Internal Page',
									'external' => 'External URL',
									'email' => 'Email Address',
								),
								'default_value' => false,
								'allow_null' => 0,
								'multiple' => 0,
								'ui' => 0,
								'return_format' => 'value',
								'ajax' => 0,
								'placeholder' => '',
							),
							array(
								'key' => 'field_62561315d975c',
								'label' => 'Page',
								'name' => 'page',
								'type' => 'page_link',
								'instructions' => '',
								'required' => 1,
								'conditional_logic' => array(
									array(
										array(
											'field' => 'field_62561226d975a',
											'operator' => '==',
											'value' => 'internal',
										),
									),
								),
								'wrapper' => array(
									'width' => '',
									'class' => '',
									'id' => '',
								),
								'post_type' => array(
									0 => 'post',
									1 => 'page',
								),
								'taxonomy' => '',
								'allow_null' => 0,
								'allow_archives' => 0,
								'multiple' => 0,
							),
							array(
								'key' => 'field_6256133bd975d',
								'label' => 'URL',
								'name' => 'url',
								'type' => 'url',
								'instructions' => '',
								'required' => 1,
								'conditional_logic' => array(
									array(
										array(
											'field' => 'field_62561226d975a',
											'operator' => '==',
											'value' => 'external',
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
							),
							array(
								'key' => 'field_6256134dd975e',
								'label' => 'Email',
								'name' => 'email',
								'type' => 'email',
								'instructions' => '',
								'required' => 1,
								'conditional_logic' => array(
									array(
										array(
											'field' => 'field_62561226d975a',
											'operator' => '==',
											'value' => 'email',
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
							),
							array(
								'key' => 'field_6256136bd975f',
								'label' => 'New Tab?',
								'name' => 'new_tab',
								'type' => 'true_false',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => array(
									array(
										array(
											'field' => 'field_62561226d975a',
											'operator' => '!=',
											'value' => 'email',
										),
										array(
											'field' => 'field_62561175d9757',
											'operator' => '==',
											'value' => '1',
										),
										array(
											'field' => 'field_62561295d975b',
											'operator' => '!=empty',
										),
									),
								),
								'wrapper' => array(
									'width' => '',
									'class' => '',
									'id' => '',
								),
								'message' => '',
								'default_value' => 0,
								'ui' => 1,
								'ui_on_text' => '',
								'ui_off_text' => '',
							),
						),
					),
					array(
						'key' => 'field_62561194d9758',
						'label' => 'Text',
						'name' => 'sitebanner_text',
						'type' => 'wysiwyg',
						'instructions' => '(75 characters or less)',
						'required' => 0,
						'conditional_logic' => array(
							array(
								array(
									'field' => 'field_62561175d9757',
									'operator' => '==',
									'value' => '1',
								),
							),
						),
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'tabs' => 'all',
						'toolbar' => 'basic',
						'media_upload' => 0,
						'delay' => 0,
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
				'menu_order' => 0,
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

	endif;
}
add_action('acf/init', function () use ($sitebanner) { spr_add_site_banner_fields($sitebanner); }, 10);


// ======================================= //
//        ADD CONTACT INFO FIELDSET        //
// ======================================= // 

function spr_add_contact_fields() {
	if( function_exists('acf_add_local_field_group') ):

		$contactfieldset = array(
			'key' => 'group_5fc94ae7a614c',
			'title' => 'Contact Info',
			'fields' => array(
	            array(
	                'key' => 'field_5fc94af2eb73a',
	                'label' => 'Company',
	                'name' => 'company',
	                'type' => 'text',
	                'instructions' => '',
	                'required' => 1,
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
	                'key' => 'field_5fc94c23eb741',
	                'label' => 'Address',
	                'name' => 'address',
	                'type' => 'group',
	                'instructions' => '',
	                'required' => 0,
	                'conditional_logic' => 0,
	                'wrapper' => array(
	                    'width' => '',
	                    'class' => '',
	                    'id' => '',
	                ),
	                'layout' => 'block',
	                'sub_fields' => array(
	                    array(
	                        'key' => 'field_5fc94b11eb73b',
	                        'label' => 'Address Line 1',
	                        'name' => 'street_address',
	                        'type' => 'text',
	                        'instructions' => '',
	                        'required' => 1,
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
	                        'key' => 'field_5fc94b33eb73c',
	                        'label' => 'Address Line 2',
	                        'name' => 'street_address2',
	                        'type' => 'text',
	                        'instructions' => '',
	                        'required' => 0,
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
	                        'key' => 'field_5fc94b4ceb73d',
	                        'label' => 'City/State/Zip',
	                        'name' => 'city',
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
	                                'key' => 'field_5fc94b95eb73e',
	                                'label' => 'City',
	                                'name' => 'city',
	                                'type' => 'text',
	                                'instructions' => '',
	                                'required' => 1,
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
	                                'key' => 'field_5fc94ba5eb73f',
	                                'label' => 'State',
	                                'name' => 'state',
	                                'type' => 'text',
	                                'instructions' => '',
	                                'required' => 1,
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
	                                'key' => 'field_5fc94babeb740',
	                                'label' => 'Zip',
	                                'name' => 'zip',
	                                'type' => 'text',
	                                'instructions' => '',
	                                'required' => 1,
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
	                        ),
	                    ),
	                ),
	            ),
	            array(
	                'key' => 'field_5fc94c78eb743',
	                'label' => 'Contact',
	                'name' => 'contact',
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
	                        'key' => 'field_5fc94c9beb744',
	                        'label' => 'Phone',
	                        'name' => 'phone',
	                        'type' => 'text',
	                        'instructions' => '',
	                        'required' => 1,
	                        'conditional_logic' => 0,
	                        'wrapper' => array(
	                            'width' => '30',
	                            'class' => '',
	                            'id' => '',
	                        ),
	                        'default_value' => '952.853.1400',
	                        'placeholder' => '',
	                        'prepend' => '',
	                        'append' => '',
	                        'maxlength' => '',
	                    ),
						array(
							'key' => 'field_618c14244452d',
							'label' => 'Fax',
							'name' => 'fax',
							'type' => 'text',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '30',
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
	                        'key' => 'field_5fc94cc7eb745',
	                        'label' => 'Email',
	                        'name' => 'email',
	                        'type' => 'email',
	                        'instructions' => '',
	                        'required' => 0,
	                        'conditional_logic' => 0,
	                        'wrapper' => array(
	                            'width' => '70',
	                            'class' => '',
	                            'id' => '',
	                        ),
	                        'default_value' => '',
	                        'placeholder' => '',
	                        'prepend' => '',
	                        'append' => '',
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
			'menu_order' => 5,
			'position' => 'normal',
			'style' => 'default',
			'label_placement' => 'left',
			'instruction_placement' => 'label',
			'hide_on_screen' => '',
			'active' => true,
			'description' => '',
			'show_in_rest' => 0,
		);

		if ($multilocs) {
			$contactfieldset['fields'] = array(
				array(
					'key' => 'field_5fc94af2eb73a',
					'label' => 'Company',
					'name' => 'company',
					'type' => 'text',
					'instructions' => '',
					'required' => 1,
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
					'key' => 'field_618c116acb2cd',
					'label' => 'Locations',
					'name' => 'locations',
					'type' => 'repeater',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'collapsed' => 'field_618c135331bf3',
					'min' => 1,
					'max' => 0,
					'layout' => 'row',
					'button_label' => 'Add Location',
					'sub_fields' => array(
						array(
							'key' => 'field_618c135331bf3',
							'label' => 'Location Name',
							'name' => 'name',
							'type' => 'text',
							'instructions' => '',
							'required' => 1,
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
							'key' => 'field_5fc94c23eb741',
							'label' => 'Address',
							'name' => 'address',
							'type' => 'group',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'layout' => 'block',
							'sub_fields' => array(
								array(
									'key' => 'field_5fc94b11eb73b',
									'label' => 'Address Line 1',
									'name' => 'street_address',
									'type' => 'text',
									'instructions' => '',
									'required' => 1,
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
									'key' => 'field_5fc94b33eb73c',
									'label' => 'Address Line 2',
									'name' => 'street_address2',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
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
									'key' => 'field_5fc94b4ceb73d',
									'label' => 'City/State/Zip',
									'name' => 'city',
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
											'key' => 'field_5fc94b95eb73e',
											'label' => 'City',
											'name' => 'city',
											'type' => 'text',
											'instructions' => '',
											'required' => 1,
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
											'key' => 'field_5fc94ba5eb73f',
											'label' => 'State',
											'name' => 'state',
											'type' => 'text',
											'instructions' => '',
											'required' => 1,
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
											'key' => 'field_5fc94babeb740',
											'label' => 'Zip',
											'name' => 'zip',
											'type' => 'text',
											'instructions' => '',
											'required' => 1,
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
									),
								),
							),
						),
						array(
							'key' => 'field_5fc94c78eb743',
							'label' => 'Contact',
							'name' => 'contact',
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
									'key' => 'field_5fc94c9beb744',
									'label' => 'Phone',
									'name' => 'phone',
									'type' => 'text',
									'instructions' => '',
									'required' => 1,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '30',
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
									'key' => 'field_618c14244452d',
									'label' => 'Fax',
									'name' => 'fax',
									'type' => 'text',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '30',
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
									'key' => 'field_5fc94cc7eb745',
									'label' => 'Email',
									'name' => 'email',
									'type' => 'email',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array(
										'width' => '40',
										'class' => '',
										'id' => '',
									),
									'default_value' => '',
									'placeholder' => '',
									'prepend' => '',
									'append' => '',
								),
							),
						),
					),
				),
			);
		}

		acf_add_local_field_group($contactfieldset);

	endif;
}
add_action('acf/init', 'spr_add_contact_fields', 10);


// ======================================= //
//           ADD SOCIAL FIELDSET           //
// ======================================= // 

function spr_add_social_fields() {
	if( function_exists('acf_add_local_field_group') ):

	    acf_add_local_field_group(array(
	        'key' => 'group_5c2fec214fbcb',
	        'title' => 'Social',
	        'fields' => array(
	            array(
	                'key' => 'field_5c2fedddafd87',
	                'label' => 'Social',
	                'name' => 'social',
	                'type' => 'group',
	                'instructions' => '',
	                'required' => 0,
	                'conditional_logic' => 0,
	                'wrapper' => array(
	                    'width' => '',
	                    'class' => '',
	                    'id' => '',
	                ),
	                'layout' => 'row',
	                'sub_fields' => array(
	                    array(
	                        'key' => 'field_5c2ff7e78c7a5',
	                        'label' => 'Display',
	                        'name' => 'social_display',
	                        'type' => 'true_false',
	                        'instructions' => '',
	                        'required' => 0,
	                        'conditional_logic' => 0,
	                        'wrapper' => array(
	                            'width' => '',
	                            'class' => '',
	                            'id' => '',
	                        ),
	                        'message' => '',
	                        'default_value' => 1,
	                        'ui' => 1,
	                        'ui_on_text' => '',
	                        'ui_off_text' => '',
	                    ),
	                    array(
	                        'key' => 'field_5c2ff1b269210',
	                        'label' => 'LinkedIn',
	                        'name' => 'linkedin',
	                        'type' => 'group',
	                        'instructions' => '',
	                        'required' => 0,
	                        'conditional_logic' => array(
	                            array(
	                                array(
	                                    'field' => 'field_5c2ff7e78c7a5',
	                                    'operator' => '==',
	                                    'value' => '1',
	                                ),
	                            ),
	                        ),
	                        'wrapper' => array(
	                            'width' => '',
	                            'class' => '',
	                            'id' => '',
	                        ),
	                        'layout' => 'table',
	                        'sub_fields' => array(
	                            array(
	                                'key' => 'field_5c2ff1f269211',
	                                'label' => 'Include?',
	                                'name' => 'linkedin_include',
	                                'type' => 'true_false',
	                                'instructions' => '',
	                                'required' => 0,
	                                'conditional_logic' => 0,
	                                'wrapper' => array(
	                                    'width' => '20',
	                                    'class' => '',
	                                    'id' => '',
	                                ),
	                                'message' => '',
	                                'default_value' => 0,
	                                'ui' => 1,
	                                'ui_on_text' => '',
	                                'ui_off_text' => '',
	                            ),
	                            array(
	                                'key' => 'field_5c2ff22169212',
	                                'label' => 'LinkedIn',
	                                'name' => 'linkedin_url',
	                                'type' => 'url',
	                                'instructions' => '',
	                                'required' => 1,
	                                'conditional_logic' => array(
	                                    array(
	                                        array(
	                                            'field' => 'field_5c2ff1f269211',
	                                            'operator' => '==',
	                                            'value' => '1',
	                                        ),
	                                    ),
	                                ),
	                                'wrapper' => array(
	                                    'width' => '80',
	                                    'class' => '',
	                                    'id' => '',
	                                ),
	                                'default_value' => 'http://www.linkedin.com/',
	                                'placeholder' => '',
	                            ),
	                        ),
	                    ),
	                    array(
	                        'key' => 'field_5c2ff2811e8ab',
	                        'label' => 'Facebook',
	                        'name' => 'facebook',
	                        'type' => 'group',
	                        'instructions' => '',
	                        'required' => 0,
	                        'conditional_logic' => array(
	                            array(
	                                array(
	                                    'field' => 'field_5c2ff7e78c7a5',
	                                    'operator' => '==',
	                                    'value' => '1',
	                                ),
	                            ),
	                        ),
	                        'wrapper' => array(
	                            'width' => '',
	                            'class' => '',
	                            'id' => '',
	                        ),
	                        'layout' => 'table',
	                        'sub_fields' => array(
	                            array(
	                                'key' => 'field_5c2ff2811e8ac',
	                                'label' => 'Include?',
	                                'name' => 'facebook_include',
	                                'type' => 'true_false',
	                                'instructions' => '',
	                                'required' => 0,
	                                'conditional_logic' => 0,
	                                'wrapper' => array(
	                                    'width' => '20',
	                                    'class' => '',
	                                    'id' => '',
	                                ),
	                                'message' => '',
	                                'default_value' => 0,
	                                'ui' => 1,
	                                'ui_on_text' => '',
	                                'ui_off_text' => '',
	                            ),
	                            array(
	                                'key' => 'field_5c2ff2811e8ad',
	                                'label' => 'Facebook',
	                                'name' => 'facebook_url',
	                                'type' => 'url',
	                                'instructions' => '',
	                                'required' => 1,
	                                'conditional_logic' => array(
	                                    array(
	                                        array(
	                                            'field' => 'field_5c2ff2811e8ac',
	                                            'operator' => '==',
	                                            'value' => '1',
	                                        ),
	                                    ),
	                                ),
	                                'wrapper' => array(
	                                    'width' => '80',
	                                    'class' => '',
	                                    'id' => '',
	                                ),
	                                'default_value' => 'http://www.facebook.com/',
	                                'placeholder' => '',
	                            ),
	                        ),
	                    ),
	                    array(
	                        'key' => 'field_5c2ff3109409e',
	                        'label' => 'Instagram',
	                        'name' => 'instagram',
	                        'type' => 'group',
	                        'instructions' => '',
	                        'required' => 0,
	                        'conditional_logic' => array(
	                            array(
	                                array(
	                                    'field' => 'field_5c2ff7e78c7a5',
	                                    'operator' => '==',
	                                    'value' => '1',
	                                ),
	                            ),
	                        ),
	                        'wrapper' => array(
	                            'width' => '',
	                            'class' => '',
	                            'id' => '',
	                        ),
	                        'layout' => 'table',
	                        'sub_fields' => array(
	                            array(
	                                'key' => 'field_5c2ff3109409f',
	                                'label' => 'Include?',
	                                'name' => 'instagram_include',
	                                'type' => 'true_false',
	                                'instructions' => '',
	                                'required' => 0,
	                                'conditional_logic' => 0,
	                                'wrapper' => array(
	                                    'width' => '20',
	                                    'class' => '',
	                                    'id' => '',
	                                ),
	                                'message' => '',
	                                'default_value' => 0,
	                                'ui' => 1,
	                                'ui_on_text' => '',
	                                'ui_off_text' => '',
	                            ),
	                            array(
	                                'key' => 'field_5c2ff310940a0',
	                                'label' => 'Instagram',
	                                'name' => 'instagram_url',
	                                'type' => 'url',
	                                'instructions' => '',
	                                'required' => 1,
	                                'conditional_logic' => array(
	                                    array(
	                                        array(
	                                            'field' => 'field_5c2ff3109409f',
	                                            'operator' => '==',
	                                            'value' => '1',
	                                        ),
	                                    ),
	                                ),
	                                'wrapper' => array(
	                                    'width' => '80',
	                                    'class' => '',
	                                    'id' => '',
	                                ),
	                                'default_value' => 'http://www.instagram.com/',
	                                'placeholder' => '',
	                            ),
	                        ),
	                    ),
	                    array(
	                        'key' => 'field_5c2ff331940a1',
	                        'label' => 'Twitter',
	                        'name' => 'twitter',
	                        'type' => 'group',
	                        'instructions' => '',
	                        'required' => 0,
	                        'conditional_logic' => array(
	                            array(
	                                array(
	                                    'field' => 'field_5c2ff7e78c7a5',
	                                    'operator' => '==',
	                                    'value' => '1',
	                                ),
	                            ),
	                        ),
	                        'wrapper' => array(
	                            'width' => '',
	                            'class' => '',
	                            'id' => '',
	                        ),
	                        'layout' => 'table',
	                        'sub_fields' => array(
	                            array(
	                                'key' => 'field_5c2ff332940a2',
	                                'label' => 'Include?',
	                                'name' => 'twitter_include',
	                                'type' => 'true_false',
	                                'instructions' => '',
	                                'required' => 0,
	                                'conditional_logic' => 0,
	                                'wrapper' => array(
	                                    'width' => '20',
	                                    'class' => '',
	                                    'id' => '',
	                                ),
	                                'message' => '',
	                                'default_value' => 0,
	                                'ui' => 1,
	                                'ui_on_text' => '',
	                                'ui_off_text' => '',
	                            ),
	                            array(
	                                'key' => 'field_5c2ff332940a3',
	                                'label' => 'Twitter',
	                                'name' => 'twitter_url',
	                                'type' => 'url',
	                                'instructions' => '',
	                                'required' => 1,
	                                'conditional_logic' => array(
	                                    array(
	                                        array(
	                                            'field' => 'field_5c2ff332940a2',
	                                            'operator' => '==',
	                                            'value' => '1',
	                                        ),
	                                    ),
	                                ),
	                                'wrapper' => array(
	                                    'width' => '80',
	                                    'class' => '',
	                                    'id' => '',
	                                ),
	                                'default_value' => 'http://www.twitter.com/',
	                                'placeholder' => '',
	                            ),
	                        ),
	                    ),
	                    array(
	                        'key' => 'field_60662ce35d0a9',
	                        'label' => 'YouTube',
	                        'name' => 'youtube',
	                        'type' => 'group',
	                        'instructions' => '',
	                        'required' => 0,
	                        'conditional_logic' => array(
	                            array(
	                                array(
	                                    'field' => 'field_5c2ff7e78c7a5',
	                                    'operator' => '==',
	                                    'value' => '1',
	                                ),
	                            ),
	                        ),
	                        'wrapper' => array(
	                            'width' => '',
	                            'class' => '',
	                            'id' => '',
	                        ),
	                        'layout' => 'table',
	                        'sub_fields' => array(
	                            array(
	                                'key' => 'field_60662ce35d0aa',
	                                'label' => 'Include?',
	                                'name' => 'youtube_include',
	                                'type' => 'true_false',
	                                'instructions' => '',
	                                'required' => 0,
	                                'conditional_logic' => 0,
	                                'wrapper' => array(
	                                    'width' => '20',
	                                    'class' => '',
	                                    'id' => '',
	                                ),
	                                'message' => '',
	                                'default_value' => 0,
	                                'ui' => 1,
	                                'ui_on_text' => '',
	                                'ui_off_text' => '',
	                            ),
	                            array(
	                                'key' => 'field_60662ce35d0ab',
	                                'label' => 'YouTube',
	                                'name' => 'youtube_url',
	                                'type' => 'url',
	                                'instructions' => '',
	                                'required' => 1,
	                                'conditional_logic' => array(
	                                    array(
	                                        array(
	                                            'field' => 'field_60662ce35d0aa',
	                                            'operator' => '==',
	                                            'value' => '1',
	                                        ),
	                                    ),
	                                ),
	                                'wrapper' => array(
	                                    'width' => '80',
	                                    'class' => '',
	                                    'id' => '',
	                                ),
	                                'default_value' => 'http://www.youtube.com/',
	                                'placeholder' => '',
	                            ),
	                        ),
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
	        'menu_order' => 20,
	        'position' => 'acf_after_title',
	        'style' => 'default',
	        'label_placement' => 'left',
	        'instruction_placement' => 'label',
	        'hide_on_screen' => array(
	            0 => 'permalink',
	            1 => 'the_content',
	            2 => 'excerpt',
	            3 => 'discussion',
	            4 => 'comments',
	            5 => 'revisions',
	            6 => 'slug',
	            7 => 'author',
	            8 => 'format',
	            9 => 'page_attributes',
	            10 => 'featured_image',
	            11 => 'categories',
	            12 => 'tags',
	            13 => 'send-trackbacks',
	        ),
	        'active' => true,
	        'description' => '',
	    ));

	endif;
}
add_action('acf/init', 'spr_add_social_fields', 10);

?>