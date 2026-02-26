<?php 
	require_once("../../../wp-load.php");
	header("Access-Control-Allow-Origin: *");
    $section = 'training';
    $slug = $section;
?>
<?php 
	$dev = false;
	if (isset($GLOBALS['produrl'])) {
		$produrl = $GLOBALS['produrl']; 
		$server = $_SERVER['SERVER_NAME'];
		if (strpos($server, $produrl) === false) {
			$dev = true;
		}
	}
	$blocks = get_field('content_blocks');
	if ($blocks) {
		$block0type = $blocks[0]['acf_fc_layout'];
		$block0bkgnd = str_replace('|', ' ', $blocks[0][str_replace('block_', '', $blocks[0]['acf_fc_layout']) . '_settings']['background']);
	} else {
		$block0type = 'block_text';
		$block0bkgnd = 'bkgnd-white light';
	}
?>
<?php $data = array(
	'block0type' => $block0type,
	'block0bkgnd' => $block0bkgnd,
	'section' => $section,
	'slug' => $slug
); ?>
<?php get_template_part('header-content', null, $data); ?>
<?php get_template_part('footer-content'); ?>