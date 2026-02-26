<?php
/*
Partial Name: block_training
*/
?>

<!-- BEGIN TRAINING -->
<?php $block = $args; 
    $settings = $block['training_settings'];
    $classes = '';
    if ($block['training_display']['custom_class'] !== null) { 
        $class = $block['training_display']['custom_class'];
		if (strlen($class) > 0) {
			$classes .= ' ' . $class;
		}
	}
	if (isset($settings['background'])) { 
		$background = explode("|", $settings['background']);
		foreach ($background as $value) {
			$classes .= ' ' . $value;
		}
	}
	$displayblock = true;
	if (isset($block['training_display']['display_block']) && $block['training_display']['display_block'] != true) {
		$displayblock = false;
	}
?>
<?php if ($displayblock) { ?>
<div class="block-training block padded<?php echo($classes); ?>">
	<div class="container">
		<div class="row justify-content-center">
			<?php $classes = $block['training_classes']; ?>
			<?php foreach ($classes as $class) { ?>
				<?php if ($class['offering']['info']['details']['level'] == 'custom' || $class['offering']['info']['details']['level'] == 'cta' || $class['offering']['info']['details']['level'] == 'support') { ?>
					<div class="training-class <?php echo($class['offering']['info']['details']['level']); ?> col-12">
						<div class="class-container">
							<?php if ($class['offering']['info']['details']['level'] != 'option' && $class['offering']['info']['details']['level'] != 'cta' && $class['offering']['info']['details']['level'] != 'support') { ?>
								<div class="level"><p><?php echo($class['offering']['info']['details']['level']); ?></p></div>
							<?php } ?>
							<div class="content-container">
								<?php if ($class['offering']['info']['details']['level'] == 'support' && $class['offering']['info']['eyebrow']['duration'] != " ") { ?>
									<h5 class="eyebrow"><?php echo($class['offering']['info']['eyebrow']['duration']); ?></h5>
								<?php } ?>
								<h4><?php echo($class['offering']['content']['title']); ?></h4>
								<?php echo($class['offering']['content']['description']); ?>
							</div>
							<?php $button = $class['button']; ?>
							<?php 
								// echo('<pre>'); print_r($button); echo('</pre>');
								if ($button['settings']['type'] == 'page') {
									$link = $button['destination']['page'];
								} else if ($button['settings']['type'] == 'url') {
									$link = $button['destination']['url'];
								}
								$newtab = $button['destination']['new_tab'];
							?>
							<a href="<?php echo($link); ?>"<?php if ($newtab) { ?> target="_blank"<?php } ?>></a>
							<?php $buttons['button'] = $button; ?>
							<div class="button-container">
                                <div class="buttons">
                                    <?php get_template_part('partials/custom/master_fields/buttons', null, $buttons); ?>
                                </div>
							</div>
						</div>
					</div>
				<?php } else { ?>
					<div class="training-class <?php echo($class['offering']['info']['details']['level']); ?> col-12 col-md-6 col-lg-4">
						<div class="class-container">
							<?php if ($class['offering']['info']['details']['level'] != 'custom' && $class['offering']['info']['details']['level'] != 'cta' && $class['offering']['info']['details']['level'] != 'option') { ?>
								<div class="level"><p><?php echo($class['offering']['info']['details']['level']); ?></p></div>
							<?php } ?>
							<div class="content-container">
								<?php
									$rawprice = $class['offering']['info']['eyebrow']['price'];
									if (preg_match('/^(\d+)(.*)$/', trim($rawprice), $matches)) {
										$number = number_format((int)$matches[1]);
										$suffix = $matches[2];
										$displayprice = $number . $suffix;
									} else {
										$displayprice = $rawprice;
									}
								?>
								<h5 class="eyebrow"><?php if ($class['offering']['info']['details']['level'] != 'cta' && $class['offering']['info']['details']['level'] != 'option') { ?><?php echo($class['offering']['info']['eyebrow']['duration']); ?>&nbsp;|&nbsp;<?php } ?>$<?php echo($displayprice); ?></h5>
								<h2><?php echo($class['offering']['content']['title']); ?></h2>
								<?php echo($class['offering']['content']['description']); ?>
								<?php if ($class['offering']['info']['details']['level'] != 'cta' && $class['offering']['info']['details']['level'] != 'support' && $class['offering']['info']['details']['level'] != 'option') { ?>
									<p>Available as:</p>
									<ul>
										<?php $availability = $class['offering']['info']['details']['available_as']; ?>
										<?php foreach ($availability as $where) { ?>
											<li><?php echo($where); ?></li>
										<?php } ?>
									</ul>
								<?php } ?>
							</div>
							<?php $button = $class['button']; ?>
							<?php 
								// echo('<pre>'); print_r($button); echo('</pre>');
								if ($button['settings']['type'] == 'page') {
									$link = $button['destination']['page'];
								} else if ($button['settings']['type'] == 'url') {
									$link = $button['destination']['url'];
								}
								$newtab = $button['destination']['new_tab'];
							?>
							<a href="<?php echo($link); ?>"<?php if ($newtab) { ?> target="_blank"<?php } ?>></a>
							<?php $buttons['button'] = $button; ?>
							<div class="button-container">
                                <div class="buttons">
                                    <?php get_template_part('partials/custom/master_fields/buttons', null, $buttons); ?>
                                </div>
							</div>
						</div>
					</div>
				<?php } ?>
			<?php } ?>
		</div>
	</div>
</div>
<?php } ?>
<!-- END TRAINING --> 