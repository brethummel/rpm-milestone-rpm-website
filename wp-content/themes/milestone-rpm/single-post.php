<?php get_header(); ?>

<!-------------------------------- BEGIN SINGLE CONTENT ------------------------------>
<?php

if (have_posts()) : the_post();

	// If you want to append blocks from another page, create a $blocks array with
	// a key for 'before' and 'after', with the page to pull from and the blocks you
	// want to include, index begins with 1, not 0 in this case

//	$blocks = array(
//		'before' => array(
//			'ID' => 100, // page ID
//			'blocks' => $afterblocks // which blocks (index begins with 1, not 0)
//		),
//		'after' => array(
//			'ID' => 100, // page ID
//			'blocks' => $afterblocks // which blocks (index begins with 1, not 0)
//		),
//	);

	$storytype = 'none';
	$storytype = get_field('post_storytype', $result->ID);
	if ($storytype == 'success' || $storytype == 'expert') {
		$name = get_field('post_client', $result->ID);
		if (strlen($name) < 1 || $name == '') {
			$name = false;
		}
		switch ($storytype) {
			case 'success':
				$storylabel = "Success Story";
				break;
			case 'expert':
				$storylabel = "Expert Insights";
				break;
		}

	}

	if ($storytype != 'none') { ?>
		<div class="success-story-flag block bkgnd-white light">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<div class="success-story<?php if ($name === false) { ?> no-client<?php } ?>">
							<h3><?php echo($storylabel); ?><?php if ($name) { ?>:<br><?php echo($name); ?><?php } ?></h3>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php }

	if ($post->post_status == 'publish' || current_user_can('edit_posts') || $_GET['preview'] == 'true') {
		if (isset($blocks['before']) || isset($blocks['after'])) {
			get_template_part('partials/_blocks', null, $blocks);
		} else {
			get_template_part('partials/_blocks', null, null);
		}
	} else {
		require('404.php');
	}

else :

	require('404.php');

endif;

?>
<!-------------------------------- END SINGLE CONTENT --------------------------------> 

<?php get_footer(); ?>