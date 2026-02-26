<?php

$version = '1.1.2';

/*
Partial Name: block_section
*/
?>

<!-- BEGIN SECTION -->
<?php $block = $args; 
	$sectioncount = $GLOBALS['sectioncount'];
    $settings = $block['section_settings'];
    $classes = '';
    if ($block['section_display']['custom_class'] !== null) { 
        $class = $block['section_display']['custom_class'];
		if (strlen($class) > 0) {
			$classes .= ' ' . $class;
		}
	}
	if (isset($settings['background'])) { 
		if ($settings['background'] == 'image') {
			$classes .= ' bkgnd-image';
			$bkgndimage = $block['section_images']['background'];
		} else {
			$background = explode("|", $settings['background']);
			foreach ($background as $value) {
				$classes .= ' ' . $value;
			}
		}
	}
	$displayblock = true;
	if (isset($block['section_display']['display_block']) && empty($block['section_display']['display_block'])) {
		$displayblock = false;
	}
	$sectioncount++;
	$GLOBALS['sectioncount'] = $sectioncount;
?>
<?php if ($displayblock) { ?>
<?php if ($sectioncount % 2 != 0) { // odd = opener ?>
	<div class="block-section block padded<?php echo($classes); ?>"<?php if ($settings['background'] == 'image') { ?> style="background-image: url(<?php echo($bkgndimage['url']); ?>)"<?php } ?>>
<?php } else { // even = closer 

	global $post;
	$blocks = get_field('content_blocks', $post->ID);
	$output_closing = true;

	// Find our current index in the repeater
	$current_index = $block['__index'];

	// Search backward for the last 'block_section' and check its display_block setting
	for ($i = $current_index - 1; $i >= 0; $i--) {
		if (!isset($blocks[$i])) continue;
		if ($blocks[$i]['acf_fc_layout'] === 'block_section') {
			if (empty($blocks[$i]['section_display']['display_block'])) {
				$output_closing = false;
			}
			break;
		}
	}
	if ($output_closing) { ?>
		</div class="close">
	<?php } ?>
<?php } ?>
<?php } ?>
<!-- END SECTION --> 