<footer>
    <div class="footer-container">
        <div class="content">
        	<div class="container">
        		<div class="row align-items-center">
					<?php  
						$locations = get_field('locations', 'options');
						$company = get_field('company', 'options');
					
						global $post;
						if (isset($post)) {
							$slug = $post->post_name;
						} else {
							$slug = '';
							$section = 'error-404';
						}
						$ancestors = get_post_ancestors($post);
						if (count($ancestors) > 0) {
							$section = get_post_field('post_name', $ancestors[count($ancestors)-1]);
						} else {
							$section = '';
						}
					?>
	                <div class="logo col-12 col-lg-3">
	                	<div class="logo-block">
	                    	<a href="<?php bloginfo('url'); ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/milestone_footer_logo.svg" alt="<?php echo(get_field('company', 'options')); ?>"/></a>
	                	</div>
	                </div>
					<div class="navigation col-12 col-lg-7">
						<div class="nav-container">
							<ul>
								<li class="<?php $me = 'contact'; echo($me);?>"><a href="<?php bloginfo('url'); ?>/for-patients/<?php echo($me); ?>">Contact</a></li>
								<li class="<?php $me = 'careers'; echo($me);?>"><a href="<?php bloginfo('url'); ?>/<?php echo($me); ?>">Careers</a></li>
								<li class="<?php $me = 'privacy'; echo($me);?>"><a href="<?php bloginfo('url'); ?>/<?php echo($me); ?>">Privacy</a></li>
								<li class="copyright-container d-none d-md-inline-block">&copy;<?php echo date("Y"); ?> <?php echo($company); ?></li>
							</ul>
						</div>
					</div>
					<?php  
						$social = get_field('social', 'options');
						if (isset($social)) {
							$linkedin = $social['linkedin'];
							$facebook = $social['facebook'];
							$instagram = $social['instagram'];
							$twitter = $social['twitter'];
							$youtube = $social['youtube'];
						}
					?>
					<?php if (isset($social) && $social['social_display']) { ?>
					<div class="social col-12 col-lg-2">
						<ul>
							<?php if ($facebook['facebook_include']) { ?>
							<li>
								<a href="<?php echo $facebook['facebook_url']; ?>" target="_blank">
									<svg width="20" height="20" viewBox="0 0 22 22"><path d="M19.5,0H2.5C1.1,0,0,1.1,0,2.5v16.9C0,20.9,1.1,22,2.5,22h9.3v-8.5H8.9v-3.3h2.9V7.7c0-2.9,1.7-4.4,4.3-4.4 c0.9,0,1.7,0,2.6,0.1v3h-1.8c-1.4,0-1.7,0.7-1.7,1.6v2.1h3.3L18,13.5h-2.9V22h4.3c1.4,0,2.5-1.1,2.5-2.5V2.5C22,1.1,20.9,0,19.5,0z"/></svg>
								</a>
							</li>
							<?php } ?>
							<?php if ($twitter['twitter_include']) { ?>
							<li>
								<a href="<?php echo $twitter['twitter_url']; ?>" target="_blank">
									<!-- new -->
									<svg width="19.5" height="20" viewBox="0 0 1200 1227"><path d="M714.163 519.284L1160.89 0H1055.03L667.137 450.887L357.328 0H0L468.492 681.821L0 1226.37H105.866L515.491 750.218L842.672 1226.37H1200L714.137 519.284H714.163ZM569.165 687.828L521.697 619.934L144.011 79.6944H306.615L611.412 515.685L658.88 583.579L1055.08 1150.3H892.476L569.165 687.854V687.828Z"></path></svg>
									<!-- old -->
									<!-- <svg width="22" height="18" viewBox="0 0 22 18"><path d="M382.27,121a9,9,0,0,1-2.6.72,4.51,4.51,0,0,0,2-2.52,9.16,9.16,0,0,1-2.87,1.11,4.45,4.45,0,0,0-3.29-1.44,4.53,4.53,0,0,0-4.52,4.55,4.4,4.4,0,0,0,.12,1,12.77,12.77,0,0,1-9.3-4.75,4.58,4.58,0,0,0,1.39,6.07,4.42,4.42,0,0,1-2-.57v.06a4.55,4.55,0,0,0,3.62,4.46,4.68,4.68,0,0,1-1.19.15,4,4,0,0,1-.85-.08,4.53,4.53,0,0,0,4.22,3.16,9,9,0,0,1-5.61,1.94,9.46,9.46,0,0,1-1.07-.06A12.82,12.82,0,0,0,380,123.9c0-.19,0-.39,0-.58A9.39,9.39,0,0,0,382.27,121Z" transform="translate(-360.27 -118.83)"/></svg>-->
								</a>
							</li>
							<?php } ?>
							<?php if ($youtube['youtube_include']) { ?>
							<li>
								<a href="<?php echo $youtube['youtube_url']; ?>" target="_blank">
									<svg width="28" height="20" viewBox="0 0 28 20"><path d="M27.43,3.77c-0.24-2.02-0.72-2.61-1.06-2.88c-0.55-0.43-1.54-0.58-2.87-0.67C21.36,0.09,17.75,0,13.84,0C9.93,0,6.32,0.09,4.19,0.23C2.86,0.32,1.87,0.47,1.32,0.9C0.98,1.17,0.49,1.76,0.26,3.77c-0.34,2.91-0.34,9.54,0,12.45c0.24,2.02,0.72,2.61,1.06,2.87c0.55,0.43,1.54,0.58,2.87,0.67C6.32,19.91,9.93,20,13.84,20c3.91,0,7.52-0.09,9.65-0.23c1.33-0.09,2.32-0.24,2.87-0.67c0.34-0.27,0.83-0.86,1.06-2.87C27.77,13.32,27.77,6.68,27.43,3.77z M10.5,14.35V5.65L18.85,10L10.5,14.35z"/></svg>
								</a>
							</li>
							<?php } ?>
							<?php if ($linkedin['linkedin_include']) { ?>
							<li>
								<a href="<?php echo $linkedin['linkedin_url']; ?>" target="_blank">
									<svg width="20" height="20" viewBox="0 0 22 22"><path d="M232.1,118.28h4.56V133H232.1Zm2.28-7.3a2.65,2.65,0,1,1-2.65,2.65,2.65,2.65,0,0,1,2.65-2.65" transform="translate(-231.73 -110.98)"/><path d="M239.52,118.28h4.37v2H244a4.81,4.81,0,0,1,4.32-2.37c4.61,0,5.46,3,5.46,7V133h-4.55v-7.15c0-1.7,0-3.89-2.37-3.89s-2.74,1.85-2.74,3.77V133h-4.55Z" transform="translate(-231.73 -110.98)"/></svg>
								</a>
							</li>
							<?php } ?>
							<?php if ($instagram['instagram_include']) { ?>
							<li>
								<a href="<?php echo $instagram['instagram_url']; ?>" target="_blank">
									<svg width="20px" height="20px" viewBox="0 0 20 20"><g><path d="M10,4.8c-2.8,0-5.2,2.3-5.2,5.2s2.3,5.2,5.2,5.2s5.2-2.3,5.2-5.2S12.8,4.8,10,4.8z M10,13.3c-1.8,0-3.3-1.5-3.3-3.3 S8.2,6.7,10,6.7s3.3,1.5,3.3,3.3S11.8,13.3,10,13.3z"/><circle cx="15.4" cy="4.7" r="1.2"/><path d="M18.4,1.7c-1-1.1-2.5-1.7-4.2-1.7H5.8C2.3,0,0,2.3,0,5.8v8.3c0,1.7,0.6,3.2,1.7,4.3c1.1,1,2.5,1.6,4.2,1.6h8.2 c1.7,0,3.2-0.6,4.2-1.6c1.1-1,1.7-2.5,1.7-4.3V5.8C20,4.2,19.4,2.7,18.4,1.7z M18.2,14.2c0,1.3-0.4,2.3-1.2,2.9 c-0.7,0.7-1.7,1-2.9,1H5.9c-1.2,0-2.2-0.4-2.9-1c-0.7-0.7-1.1-1.7-1.1-3V5.8c0-1.2,0.4-2.2,1.1-2.9c0.7-0.7,1.7-1,2.9-1h8.3 c1.2,0,2.2,0.4,2.9,1.1c0.7,0.7,1.1,1.7,1.1,2.9V14.2L18.2,14.2z"/></g></svg>
								</a>
							</li>
							<?php } ?>
						</ul>
					</div>
					<div class="mobile-copyright col-12 d-md-none">
						<p>&copy;<?php echo date("Y"); ?> <?php echo($company); ?></p>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</footer>