<?php

$version = '1.0.0';

$themedir = get_template_directory();
$themeurl = get_template_directory_uri();
$sprpath = $GLOBALS['springboard_path'];


// ======================================= //
//          LOAD COPY BLOCKS API           //
// ======================================= // 

require($themedir . $sprpath . '/admin/api_copy_blocks.php'); // loads api file


// ======================================= //
//         ADD COPY BLOCKS BUTTON          //
// ======================================= // 

// add "Copy Blocks" button to "content_blocks" field
function spr_copy_blocks_button($field) {
	$button = '</a><a class="acf-button button button-secondary" href="#" data-name="copy-layouts">Copy Blocks';
	$field['button_label'] = $field['button_label'] . $button;
	return $field;
}
add_filter('acf/prepare_field/key=field_610ac7050d4ff', 'spr_copy_blocks_button');


// ======================================= //
//          FIX NO VALUE MESSAGE           //
// ======================================= // 

function spr_no_value_message($no_value_message) {
	$no_value_message = 'Click the "Add Block" button below to start creating your layout';	
	return $no_value_message;
}
add_filter('acf/fields/flexible_content/no_value_message', 'spr_no_value_message');


// ======================================= //
//           SELECT POST POPUP             //
// ======================================= // 

// creates popup to choose the post the user wants to copy blocks from
function spr_copy_blocks_post_selector() {
	$screen = get_current_screen();
	$posttypes = $GLOBALS['posttypes'];
	$addcopyblocks = false;
	if (array_key_exists('page', $posttypes)) {
		if (array_key_exists($screen->post_type, $posttypes) && in_array('blocks', $posttypes[$screen->post_type])) {
			$addcopyblocks = true;
			$blockposttypes = [];
			foreach ($posttypes as $t => $type) {
				if (in_array('blocks', $type)) {
					$blockposttypes[] = $t;
				}
			}
		}
	} else { // backwards compatability
		if (in_array($screen->post_type, $posttypes)) {
			$addcopyblocks = true;
			$blockposttypes = $posttypes;
		}
	}
	if ($addcopyblocks) {
		$postoptions = [];
		$selectorHTML = '<div class="spr-select-source">';
		$selectorHTML .= '<div class="tinter"></div>';
		$selectorHTML .= '<div class="select-container post">';
		$selectorHTML .= '<div class="select-post">';
		$selectorHTML .= '<div class="postbox-header">';
		$selectorHTML .= '<h2>Select Source</h2>';
		$selectorHTML .= '<div class="select-type"><label>Post Type:</label><select data-filter="post_type">';
		$selectorHTML .= '<option value="">Select</option>';
		foreach ($blockposttypes as $posttype) {
			$selectorHTML .= '<option value="'. $posttype . '">' . ucfirst($posttype) . '</option>';
		}
		$selectorHTML .= '</select></div>'; // select-type
		$selectorHTML .= '</div>'; // postbox-header
		$selectorHTML .= '<div class="posts-search"><input type="text" placeholder="Search..." data-filter="s"></div>';
		$selectorHTML .= '<div class="posts-list">';
		$selectorHTML .= '<div class="unsaved-changes">';
		$selectorHTML .= '<h2>There are unsaved changes!</h2>';
		$selectorHTML .= '<p>Save your changes, then try again.</p>';
		$selectorHTML .= '<input type="submit" name="save" id="publish" class="button button-primary button-large" value="Update">';
		$selectorHTML .= '</div>'; // unsaved-changes
		$selectorHTML .= '<ul class="select-list">';
		foreach ($blockposttypes as $posttype) {
			$selectorHTML .= '<li class="post-type-label" data-posttype="' . $posttype . '">' . ucfirst($posttype) . '</li>';
			$postlist = spr_get_hierarchical_posts(0, $posttype, 0);
			$selectorHTML .= $postlist;
		}
		$selectorHTML .= '</ul>';
		$selectorHTML .= '</div>'; // posts-list
		$selectorHTML .= '<div class="posts-actions">';
		$selectorHTML .= '<a class="acf-button button button-secondary" href="#" data-name="posts-cancel">Cancel</a>';
		$selectorHTML .= '<a class="acf-button button button-primary disabled" href="#" data-name="posts-next">Set Source</a>';
		$selectorHTML .= '</div>'; // posts-actions
		$selectorHTML .= '</div>'; // select-post
		$selectorHTML .= '<div class="select-blocks">';
		$selectorHTML .= '<div class="postbox-header">';
		$selectorHTML .= '<h2>Select Blocks</h2>';
		$selectorHTML .= '</div>'; // postbox-header
		$themeurl = get_template_directory_uri();
		$themedir = get_template_directory();
		$sprpath = $GLOBALS['springboard_path'];
		$selectorHTML .= '<div class="acf-field blocks-list" data-name="content_blocks" data-themeurl="' . $themeurl . '" data-sprpath="' . $sprpath . '">';
		$selectorHTML .= '<div class="loading"><p><img src="' . $themeurl . $sprpath . '/admin/images/spinner_admin.gif" alt="loading..."/></p><p class="message">Loading blocks...</p></div>';
		$selectorHTML .= '</div>'; // blocks-list
		$selectorHTML .= '<div class="blocks-actions">';
		$selectorHTML .= '<a class="back" href="#" data-name="blocks-back">< back</a>';
		$selectorHTML .= '<a class="acf-button button button-secondary" href="#" data-name="blocks-cancel">Cancel</a>';
		// $selectorHTML .= '<input type="submit" name="save" id="publish" class="button button-primary disabled" value="Copy Blocks">';
		$selectorHTML .= '<a class="acf-button button button-primary disabled" href="#" data-name="blocks-copy">Copy Blocks</a>';
		$selectorHTML .= '</div>'; // blocks-actions
		$selectorHTML .= '</div>'; // select-blocks
		$selectorHTML .= '</div>'; // select-container
		$selectorHTML .= '</div>'; // spr-select-source
		echo($selectorHTML);
	}
}
add_action('edit_form_after_editor', 'spr_copy_blocks_post_selector');


// ======================================= //
//         GET HEIRARCHICAL POSTS          //
// ======================================= // 


function spr_get_hierarchical_posts($post_id, $posttype, $currentlevel) {
	
	$postlist = '';
	
	$args = array(
        'post_type' => $posttype,
		'post_status' => array('publish', 'draft', 'future'),
		'post_parent' => $post_id,
        'numberposts' => -1,
        'order_by' => 'menu_order',
        'order' => 'ASC'
	);
	
    $children = get_posts($args);
    if (empty($children)) {
		return;
	}
	
    foreach ($children as $child) {
        $postlist .= '<li class="level-' . $currentlevel . '" data-postid="' . $child->ID . '" data-posttype="' . $posttype . '">' . $child->post_title . '</li>';
        $postlist .= spr_get_hierarchical_posts($child->ID, $posttype, $currentlevel+1); // call same function for child of this child
	}
	
	return $postlist;

}

?>