<?php
/*
Partial Name: block_featuredpullquote
*/
?>

<!-- BEGIN STRIP -->
<?php $block = $args; 
    $settings = $block['featuredpullquote_settings'];
    $classes = '';
    if (isset($settings['background'])) { 
        $background = explode("|", $settings['background']);
        foreach ($background as $value) {
            $classes .= ' ' . $value;
        }
    }
    if ($block['featuredpullquote_display']['custom_class'] !== null) { 
        $class = $block['featuredpullquote_display']['custom_class'];
		if (strlen($class) > 0) {
			$classes .= ' ' . $class;
		}
	}
	$displayblock = true;
	if (isset($block['featuredpullquote_display']['display_block']) && $block['featuredpullquote_display']['display_block'] != true) {
		$displayblock = false;
	}

    $containerclasses = '';
    if (strlen($block['featuredpullquote_content']['photos']['logo']['url']) == 0) {
        $containerclasses .= ' no-logo';
    }
    if (strlen($block['featuredpullquote_content']['photos']['person']['url']) == 0) {
        $containerclasses .= ' no-person';
    }

?>
<?php if ($displayblock) { ?>
<div class="block-featuredpullquote block padded<?php echo($classes); ?>">
    <div class="container">
        <div class="row">
            <div class="col-12 content-container<?php echo($containerclasses); ?>">
                <?php if (strlen($block['featuredpullquote_content']['photos']['person']['url']) > 0) { ?>
                    <div class="image-container">
                        <div class="portrait-icon">
                            <img src="<?php echo($block['featuredpullquote_content']['photos']['person']['url']); ?>" alt="<?php echo($block['featuredpullquote_content']['photos']['person']['alt']); ?>"/>
                        </div>
                    </div>
                <?php } ?>
                <div class="quote-container">
                    <div class="quote">
                        <h3><?php echo($block['featuredpullquote_content']['quote']['quote']); ?></h3>
                        <?php if ($block['featuredpullquote_content']['quote']['attribution'] != '') { ?>
                            <p class="attribution"><?php echo($block['featuredpullquote_content']['quote']['attribution']); ?></p>
                        <?php } ?>
                    </div>
                    <?php if (strlen($block['featuredpullquote_content']['photos']['logo']['url']) > 0) { ?>
                        <div class="logo-container">
                            <img src="<?php echo($block['featuredpullquote_content']['photos']['logo']['url']); ?>" alt="<?php echo($block['featuredpullquote_content']['photos']['logo']['alt']); ?>"/>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<!-- END STRIP --> 
