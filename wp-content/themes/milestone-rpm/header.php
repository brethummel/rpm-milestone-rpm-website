<?php 
    global $post;
	if (isset($post)) {
    	$slug = $post->post_name;
	} else {
		$slug = '';
		$section = 'error-404';
	}
	$ancestors = get_post_ancestors($post);
	if (count($ancestors) > 0) {
		$section = get_post_field('post_name', $ancestors[count($ancestors)-1]) . ' ';
	} else {
		if (!isset($section)) {
			if (get_post_type() != 'page') {
				$section = get_post_type() . ' ';
			} else {
				$section = '';
			}
		}
	}
	if ($GLOBALS['leadattribution']) {
		require(get_template_directory() . '/partials/admin/lead_attribution.php');
		$attribution = ' data-campaign="' . $campaign . '"';
		$attribution .= ' data-source="' . $source . '"';
		$attribution .= ' data-medium="' . $medium . '"';
		$attribution .= ' data-attrsrc="' . $attrsrc . '"';
	}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
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
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset='<?php bloginfo('charset'); ?>' />
    <title><?php wp_title(''); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="apple-touch-icon" sizes="57x57" href="<?php bloginfo('stylesheet_directory'); ?>/favicon/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="<?php bloginfo('stylesheet_directory'); ?>/favicon/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="<?php bloginfo('stylesheet_directory'); ?>/favicon/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="<?php bloginfo('stylesheet_directory'); ?>/favicon/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="<?php bloginfo('stylesheet_directory'); ?>/favicon/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="<?php bloginfo('stylesheet_directory'); ?>/favicon/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="<?php bloginfo('stylesheet_directory'); ?>/favicon/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="<?php bloginfo('stylesheet_directory'); ?>/favicon/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="<?php bloginfo('stylesheet_directory'); ?>/favicon/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="<?php bloginfo('stylesheet_directory'); ?>/favicon/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php bloginfo('stylesheet_directory'); ?>/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="<?php bloginfo('stylesheet_directory'); ?>/favicon/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="<?php bloginfo('stylesheet_directory'); ?>/favicon/favicon-16x16.png">
	<link rel="manifest" href="<?php bloginfo('stylesheet_directory'); ?>/favicon/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="<?php bloginfo('stylesheet_directory'); ?>/favicon/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">
    <?php wp_head(); ?>
</head>
<body class="<?php echo($section); ?><?php echo($slug); ?><?php if ($dev) { ?> dev-env<?php } ?><?php if (is_user_logged_in()) { ?> logged-in<?php } ?>" data-slug="<?php echo($slug); ?>">
	<?php if ($GLOBALS['leadattribution']) { ?>
		<div class="attribution">
			<div class="data"<?php echo($attribution); ?><?php echo($attr_settings); ?>>
				<div class="visits" style="display:none;">
					<?php echo(trim($pagevisits)); ?>
				</div>
			</div>
		</div>
	<?php } ?>
    <?php wp_body_open(); ?>
    <?php $data = array(
    	'block0type' => $block0type,
    	'block0bkgnd' => $block0bkgnd,
    	'section' => $section,
    	'slug' => $slug
    ); ?>
    <?php get_template_part('header-content', null, $data); ?>
    <div class="content-container">
        <div class="content">