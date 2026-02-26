<?php
/*
Partial Name: block_storysidebar
*/
?>

<!-- BEGIN STORY SIDEBAR -->
<?php $block = $args; 
	$settings = $block['storysidebar_settings'];
    $classes = '';
    if ($block['storysidebar_display']['custom_class'] !== null) { 
        $class = $block['storysidebar_display']['custom_class'];
		if (strlen($class) > 0) {
			$classes .= ' ' . $class;
		}
	}
    if (isset($settings['include_button']) && $settings['include_button']) {
        $buttons = $block['storysidebar_cta']['buttons'];
    }
	$displayblock = true;
	if (isset($block['storysidebar_display']['display_block']) && $block['storysidebar_display']['display_block'] != true) {
		$displayblock = false;
	}
?>
<?php if ($displayblock) { ?>
<div class="block-storysidebar block<?php echo($classes); ?>">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-12">
				<?php echo($block['storysidebar_content']); ?>
				<?php if (isset($block['storysidebar_cta']['cta']) && $block['storysidebar_cta']['cta'] != '') { ?>
					<p class="cta"><?php echo($block['storysidebar_cta']['cta']); ?></p>
				<?php } ?>
				<?php if (isset($buttons)) { ?>
					<div class="buttons">
						<?php get_template_part('partials/custom/master_fields/buttons', null, $buttons); ?>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<?php } ?>
<!-- END STORY SIDEBAR --> 