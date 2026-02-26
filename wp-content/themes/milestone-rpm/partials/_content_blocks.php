<?php

// uncomment if testing the file directly
// $rootdir = substr(dirname(__FILE__), 0, strpos(dirname(__FILE__), 'wp-content'));
// require_once($rootdir . '/wp-load.php');

$contentblocks = $GLOBALS['content_blocks'];

$themedir = get_template_directory();
$sprpath = $GLOBALS['springboard_path'];

// inventory core blocks
$coredir = new RecursiveDirectoryIterator($themedir . $sprpath . '/core/');
$blocks = [];
foreach(new RecursiveIteratorIterator($coredir) as $file) {
    if ($file->isDir()) {
		$path = $file->getPathname();
		if (strpos($path, 'block_') != false) {
			$catstart = strpos($path, 'core') + 5;
			$catend = strpos($path, '/', intval($catstart)) - $catstart;
			$blockstart = strpos($path, 'block_');
			$blockend = strpos($path, '/', intval($blockstart)) - $blockstart;
			$cat = substr($path, $catstart, $catend);
			$block = substr($path, $blockstart, $blockend);
			// echo('cat: ' . $cat . ', block: ' . $block . '<br/>');
			$found = false;
			foreach ($blocks as $id) {
				if ($id['id'] == $block) { $found = true; }
			}
			if (!$found) {
				$blocks[] = array(
					'id' => $block, 
					'cat' => $cat
				);
			}
		}
    }
}

// echo('<pre>'); print_r('$blocks before custom blocks?'); echo('</pre>');
// echo('<pre>'); print_r($blocks); echo('</pre>');

// inventory customized versions of core blocks
$customdir = new RecursiveDirectoryIterator($themedir . $sprpath . '/custom/');
foreach(new RecursiveIteratorIterator($customdir) as $file) {
    if ($file->isDir()) {
		$path = $file->getPathname();
		if (strpos($path, 'block_') != false) {
			$blockstart = strpos($path, 'block_');
			$blockend = strpos($path, '/', intval($blockstart)) - $blockstart;
			$cat = 'custom';
			$block = substr($path, $blockstart, $blockend);
			// echo('cat: ' . $cat . ', block: ' . $block . '<br/>');
			$found = false;
			$index = '';

			// if the block was already inventoried above but it also a custom block,
			// it means we found a core block that was customized for the client, and
			// we should load the custom version instead of the core version.

			foreach ($blocks as $i => $id) {
				if ($id['id'] == $block) { 
					$found = true; 
					$index = $i;
				}
			}

			// all /custom/ blocks (i.e. core and not) have to be loaded here, or
			// they get loaded too late to be registered for use in the field. That said, 
			// _custom_blocks.acf still handles inventorying their .scss files

			if ($found) { 
				$blocks[$index] = array(
					'id' => $block, 
					'cat' => $cat,
					'override' => true
				);
			} else {
				$cblocks[$block] = $block;
			}
		}
    }
}

foreach ($cblocks as $block) {
	$blocks[] = array(
		'id' => $block, 
		'cat' => 'custom',
	);
}


// extrapolate key fields from blocks .acf file
function spr_get_val($val, $blockacf, $endind) {
	$startpos = strpos($blockacf, $val) + strlen($val);
	$endpos = strpos($blockacf, $endind, intval($startpos)) - $startpos;
	$result = substr($blockacf, $startpos, $endpos);
	return $result;
}


// note that we do not inventory private blocks, and that's because we're
// only inventorying layouts that can be added by the user to the content_blocks
// field, whereas private blocks have specific applications.

// grabs group and field IDs out of the block's .acf file and adds to $blocks
foreach ($blocks as $i => $block) {
	$blockid = $block['id']; // this is the folder name
	if ($block['cat'] != 'custom') {
		$blockpath = $themedir . $sprpath . '/core/' . $block['cat'] . '/' . $blockid; // this is the path to the block's folder
	} else {
		$blockpath = $themedir . $sprpath . '/custom/' . $blockid; // this is the path to the block's folder
	}
	$blockacf = file_get_contents($blockpath . '/' . $blockid . '.acf');
	$blockacf = preg_replace('/\s+/', ' ', $blockacf);
	$label = spr_get_val('Name: ', $blockacf, ' ID:');
	$name = spr_get_val('ID: ', $blockacf, ' ');
	// echo('<pre>'); print_r($label); echo('</pre>');
	$status = spr_get_val('Status: ', $blockacf, ' ');
	$layoutid = spr_get_val('Layout ID: ', $blockacf, ' ');
	$fieldid = spr_get_val('Subfield ID: ', $blockacf, ' ');
	$groupid = spr_get_val('Group ID: ', $blockacf, ' ');
	$block['name'] = $label;
	if ($status != '') {
		$block['status'] = $status;
	} else {
		$block['status'] = 'public';
	}
	$block['layoutid'] = $layoutid;
	$block['fieldid'] = $fieldid;
	$block['groupid'] = $groupid;
	$blocks[$i] = $block;
}

// echo('<pre>'); print_r('$blocks after info added:'); echo('</pre>');
// echo('<pre>'); print_r($blocks); echo('</pre>');

// sorts blocks alphabetically
$names = array_column($blocks, 'id');
// echo('<pre>'); print_r($names); echo('</pre>');
array_multisort($names, SORT_ASC, $blocks);

// build $layouts
$layouts = [];
foreach ($blocks as $block) {
	$layouts['layout_' . $block['layoutid']] = array(
		'key' => 'layout_' . $block['layoutid'],
		'name' => $block['id'],
		'spr_cat' => $block['cat'],
		'label' => $block['name'],
		'display' => 'block',
		'sub_fields' => array(
			array(
				'key' => 'field_' . $block['fieldid'],
				'label' => $block['name'],
				'name' => substr($block['id'], strpos($block['id'], '_') + 1, strlen($block['id'])) . '_fields',
				'type' => 'clone',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'clone' => array(
					0 => 'group_' . $block['groupid'],
				),
				'display' => 'seamless',
				'layout' => 'block',
				'prefix_label' => 0,
				'prefix_name' => 0,
			),
		),
		'min' => '',
		'max' => '',
	);
	if ($block['override']) {
		$layouts['layout_' . $block['layoutid']]['override'] = true;
	}
}
// echo('<pre>'); print_r($blocks); echo('</pre>');
// echo('<pre>'); print_r($layouts); echo('</pre>');
$GLOBALS['layouts'] = $layouts;


// start $locations
$locations = array(
	array(
		array(
			'param' => 'page_template',
			'operator' => '==',
			'value' => 'default',
		),
	)
);

$posttypes = $GLOBALS['posttypes'];
if (array_key_exists('page', $posttypes)) {
	foreach ($posttypes as $t => $type) {
		// echo('<pre>'); echo($t . '<br/>'); print_r($type); echo('</pre>');
		if (in_array('blocks', $type)) {
			$posttype = array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => $t,
				)
			);
			$locations[] = $posttype;
		}
	}
} else { // backwards compatability
	foreach ($posttypes as $type) {
		$posttype = array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => $type,
			)
		);
		$locations[] = $posttype;
	}
}
// echo('<pre>'); print_r($locations); echo('</pre>');
// echo('<pre>'); print_r($layouts); echo('</pre>');


// attach fieldset
if ($contentblocks == 'field_610ac7050d4ff') {  // i.e. SpringBoard's native content_blocks field

	if( function_exists('acf_add_local_field_group') ):

		acf_add_local_field_group(array(
			'key' => 'group_610ac6ffac0e4',
			'title' => 'Content Blocks',
			'fields' => array(
				array(
					'key' => 'field_610ac7050d4ff',
					'label' => 'Content Blocks',
					'name' => 'content_blocks',
					'type' => 'flexible_content',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'layouts' => $layouts,
					'button_label' => 'Add Block',
					'min' => '',
					'max' => '',
				),
			),
			'location' => $locations,
			'menu_order' => 0,
			'position' => 'acf_after_title',
			'style' => 'seamless',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => array(
				0 => 'the_content',
				1 => 'excerpt',
				2 => 'discussion',
				3 => 'comments',
				4 => 'author',
				5 => 'format',
				6 => 'featured_image',
				7 => 'send-trackbacks',
			),
			'active' => true,
			'description' => '',
			'show_in_rest' => 1,
		));

	endif;

} else {

	function spr_add_layouts($field) {
		$layouts = $GLOBALS['layouts'];
		foreach($layouts as $key => $layout) {
			$groupid = $layout['sub_fields'][0]['clone'][0];
			$group = acf_get_fields($groupid);
			$field['layouts'][$key] = $layout;
			$field['layouts'][$key]['sub_fields'] = $group;
		}
		// $group = acf_get_fields('group_6757a351a833c'); echo('<pre>'); print_r('group_6757a351a833c'); echo('</pre>'); // BLOCK: Events
		// $group = acf_get_field_group('group_6757a351a833c'); echo('<pre>'); print_r('group_6757a351a833c'); echo('</pre>'); // BLOCK: Events
		// $group = acf_get_field_group('group_5e988ddfbe90c'); echo('<pre>'); print_r('group_5e988ddfbe90c'); echo('</pre>'); 
		// echo('<pre>'); print_r($group); echo('</pre>');
		return $field;
	}
	add_filter('acf/load_field/key=' . $contentblocks, 'spr_add_layouts');

}



?>