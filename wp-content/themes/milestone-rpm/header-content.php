<?php $data = $args; ?>
<?php
	$block0type = $data['block0type'];
	$block0bkgnd = $data['block0bkgnd'];
	$section = $data['section'];
	$slug = $data['slug'];
	$featureds = get_field('featured', 'options');
?>
<?php $sitebanner = false; ?>
<?php if (isset(get_field('sitebanner_settings', 'options')['display'])) { $sitebanner = get_field('sitebanner_settings', 'options')['display']; } ?>
<?php if ($sitebanner) { ?>
	<div class="top-bar" data-nosnippet>
		<div class="container">
			<div class="row">
				<div class="col-12 notice" data-nosnippet>
					<?php $type = get_field('sitebanner_settings', 'options')['type']; 
						if ($type == 'internal') {
							$dest = get_field('sitebanner_settings', 'options')['page'];
						} elseif ($type == 'external') {
							$dest = get_field('sitebanner_settings', 'options')['url'];
						} elseif ($type == 'email') {
							$dest = "mailto:" . get_field('sitebanner_settings', 'options')['email'];
						}
						if ($type != 'email' && get_field('sitebanner_settings', 'options')['new_tab']) {
							$target = "_blank";
						}
					?>
					<?php echo(get_field('sitebanner_text', 'options')); ?><?php if (isset(get_field('sitebanner_settings', 'options')['text']) && get_field('sitebanner_settings', 'options')['text'] != '') { ?><a class="more" <?php if(isset($target)) { ?>target=<?php echo($target); ?> <?php } ?>href="<?php echo($dest); ?>"><?php echo(get_field('sitebanner_settings', 'options')['text']); ?></a><?php } ?>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
<header class="<?php echo($block0type . ' ' . $block0bkgnd); ?>">
	<div class="container">
		<div class="row align-items-center">
			<div class="logo col-5 col-md-4 col-lg-3">
				<div class="logo-block">
					<?php if (trim($section)== 'training' || trim($slug) == 'training' || trim($section) == 'support' || trim($slug) == 'support') { ?>
						<a href="<?php bloginfo('url'); ?>"><img class="mastery" src="<?php bloginfo('stylesheet_directory'); ?>/images/milestone_mastery_logo.svg" alt="<?php echo(get_field('company', 'options')); ?>"/></a>
						<p class="tagline">Microsoft Project<br/>Training & Support</p>
					<?php } else { ?>
						<a href="<?php bloginfo('url'); ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/milestone_logo.svg" alt="<?php echo(get_field('company', 'options')); ?>"/></a>
					<?php } ?>
				</div>
			</div>
			<div class="hamburger d-lg-none">
				<div class="hamburger-container">
					<div class="line top"></div>
					<div class="line middle"></div>
					<div class="line bottom"></div>
				</div>
			</div>
			<div class="menu-reset"><a href="#">reset menu</a></div>
			<div class="menu main-menu col-md-8 col-lg-9">
				<ul>
					<li class="<?php $me = 'solutions'; echo($me); ?> has-submenu<?php if ($slug == $me || $me == $section) { ?> current<?php } ?>">
						<a class="mobile-toggle" href="<?php bloginfo('url'); ?>/<?php echo($me); ?>">Solutions</a>
						<div class="mega drop">
							<div class="container">
								<div class="row">
									<div class="mouse-hit"></div>
									<div class="col-12 col-lg-5 column services">
										<p>PPM Services</p>
										<ul class="nav-container">
											<li>
												<p><a href="<?php bloginfo('url'); ?>/solutions/ppm-services/microsoft-project-online-transition-support/">Project Online Transition Support</a></p>
												<p>Get customized recommendations for replacing Microsoft Project Online.</p>
											</li>
											<li>
												<p><a href="<?php bloginfo('url'); ?>/solutions/ppm-services/microsoft-ppm-implementations/">Microsoft PPM Implementations</a></p>
												<p>Everything you need to succeed—from strategy to customizations.</p>
											</li>
											<li>
												<p><a href="<?php bloginfo('url'); ?>/solutions/ppm-services/microsoft-ppm-integrations/">Microsoft PPM Integrations</a></p>
												<p>Connect your PPM tools to your business, the easy way.</p>
											</li>
											<li>
												<p><a href="<?php bloginfo('url'); ?>/solutions/ppm-services/business-intelligence-and-reporting/">BI and Reporting</a></p>
												<p>Get custom Power BI dashboards that keep leaders informed and in control.</p>
											</li>
											<li>
												<p><a href="<?php bloginfo('url'); ?>/solutions/ppm-services/microsoft-project-reports-power-bi-templates/">Plug & Play Report Packs</a></p>
												<p>Get instant visibility into project and timesheet status—no coding required.</p>
											</li>
											<li>
												<p><a href="<?php bloginfo('url'); ?>/solutions/ppm-services/milestone-fast-pass/">Milestone Fast Pass<sup>&trade;</sup></a></p>
												<p>Need quick help on a special PPM project? Let’s talk.</p>
											</li>
										</ul>
										<a class="desktop-only" href="<?php bloginfo('url'); ?>/<?php echo($me); ?>">All Solutions</a>
									</div>
									<div class="col-12 col-lg-5 column products">
										<p>PPM Extenders</p>
										<ul class="nav-container">
											<li>
												<p><a href="<?php bloginfo('url'); ?>/solutions/ppm-extenders/milestone-rpm/">Milestone RPM<sup>&trade;</sup></a></p>
												<p>Simplify resource management and gain true resource visibility.</p>
											</li>
											<li>
												<p><a href="<?php bloginfo('url'); ?>/solutions/ppm-extenders/milestone-project-bulk-editor/">Milestone Project Bulk Editor<sup>&trade;</sup></a></p>
												<p>Eliminate busywork in Microsoft Project.</p>
											</li>
											<li>
												<p><a href="<?php bloginfo('url'); ?>/solutions/ppm-extenders/milestone-timesheet-advantage">Milestone Timesheet Advantage<sup>&trade;</sup></a></p>
												<p>Easier—and more accurate—time collection.</p>
											</li>
										</ul>
										<a class="mobile-only" href="<?php bloginfo('url'); ?>/<?php echo($me); ?>">All Solutions</a>
									</div>
									<div class="col-12 col-lg-2 feature">
										<div class="feature-container">
											<?php foreach ($featureds as $featured) {
												if ($featured['nav_group'] == $me) {
													$image = $featured['content']['image'];
													$copy = $featured['content']['copy'];
													$button = $featured['button'];
												}
												if ($button['settings']['type'] == 'page') {
													$link = $button['destination']['page'];
												} else if ($button['settings']['type'] == 'url') {
													$link = $button['destination']['url'];
												}
												// echo('<pre>'); print_r($button); echo('</pre>');
											} ?>
											<div class="image-container">
												<img src="<?php echo($image['url']); ?>" alt="<?php echo($image['alt']); ?>"/>
											</div>
											<div class="copy-container">
												<?php echo($copy); ?>
												<a class="tile-click" href="<?php echo($link); ?>"></a>
												<p><a href="<?php echo($link); ?>"><?php echo($button['destination']['text']); ?></a></p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</li>
					<li class="<?php $me = 'training'; echo($me); ?> has-submenu<?php if ($slug == $me || $me == $section) { ?> current<?php } ?>">
						<a class="mobile-toggle" href="<?php bloginfo('url'); ?>/<?php echo($me); ?>">Training</a>
						<div class="mega drop">
							<div class="container">
								<div class="row">
									<div class="mouse-hit"></div>
									<div class="col-12 col-lg-5 column training">
										<p>Featured Classes</p>
										<ul class="nav-container">
											<li>
												<p><a href="<?php bloginfo('url'); ?>/training/the-yjtj-secret/">The YJTJ™ Secret</a></p>
												<p>How to Make Microsoft Project Work For You (Not Against You)</p>
											</li>
											<li>
												<p><a href="<?php bloginfo('url'); ?>/training/mastering-microsoft-project-with-yjtj/">Mastering Microsoft Project–with YJTJ™</a></p>
												<p>Scheduling Techniques that Will Change Your Life (and Simplify Your Work)</p>
											</li>
											<li>
												<p><a href="<?php bloginfo('url'); ?>/training/project-admin-bootcamp/">Project Admin Bootcamp</a></p>
												<p>Everything You Need to Know as a Microsoft Project Server Administrator</p>
											</li>
										</ul>
										<a href="<?php bloginfo('url'); ?>/<?php echo($me); ?>">Full List of Classes</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="http://milestonetraining.milestoneconsultinggroup.com/login" target="_blank">Training Login</a>
									</div>
									<div class="col-12 col-lg-2 feature">
										<div class="feature-container">
											<?php foreach ($featureds as $featured) {
												if ($featured['nav_group'] == $me) {
													$image = $featured['content']['image'];
													$copy = $featured['content']['copy'];
													$button = $featured['button'];
												}
												if ($button['settings']['type'] == 'page') {
													$link = $button['destination']['page'];
												} else if ($button['settings']['type'] == 'url') {
													$link = $button['destination']['url'];
												}
												// echo('<pre>'); print_r($button); echo('</pre>');
											} ?>
											<div class="image-container">
												<img src="<?php echo($image['url']); ?>" alt="<?php echo($image['alt']); ?>"/>
											</div>
											<div class="copy-container">
												<?php echo($copy); ?>
												<a class="tile-click" href="<?php echo($link); ?>"></a>
												<p><a href="<?php echo($link); ?>"><?php echo($button['destination']['text']); ?></a></p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</li>
					<li class="<?php $me = 'support'; echo($me); ?> has-submenu<?php if ($slug == $me || $me == $section) { ?> current<?php } ?>">
						<a class="mobile-toggle" href="<?php bloginfo('url'); ?>/<?php echo($me); ?>">Support</a>
						<div class="mega drop">
							<div class="container">
								<div class="row">
									<div class="mouse-hit"></div>
									<div class="col-12 col-lg-5 column support">
										<p>Support Options</p>
										<ul class="nav-container">
											<li>
												<p><a href="<?php bloginfo('url'); ?>/support/#first-ticket">Submit Your First Ticket for Free</a></p>
											</li>
											<li>
												<p><a href="<?php bloginfo('url'); ?>/support/#pay-as-you-go">Pay-As-You-Go-Support</a></p>
												<p>Get Fast, Expert Support on Any Microsoft PPM Issue</p>
											</li>
											<li>
												<p><a href="<?php bloginfo('url'); ?>/support/#priority-support-monthly-packages">Monthly Support Packages</a></p>
												<p>Get Priority Support at Reduced Rates—Packages Start at 3 Hours/Month</p>
											</li>
										</ul>
										<a href="<?php bloginfo('url'); ?>/<?php echo($me); ?>">All Support Options</a>
									</div>
									<div class="col-12 col-lg-2 feature">
										<div class="feature-container">
											<?php foreach ($featureds as $featured) {
												if ($featured['nav_group'] == $me) {
													$image = $featured['content']['image'];
													$copy = $featured['content']['copy'];
													$button = $featured['button'];
												}
												if ($button['settings']['type'] == 'page') {
													$link = $button['destination']['page'];
												} else if ($button['settings']['type'] == 'url') {
													$link = $button['destination']['url'];
												}
												// echo('<pre>'); print_r($button); echo('</pre>');
											} ?>
											<div class="image-container">
												<img src="<?php echo($image['url']); ?>" alt="<?php echo($image['alt']); ?>"/>
											</div>
											<div class="copy-container">
												<?php echo($copy); ?>
												<a class="tile-click" href="<?php echo($link); ?>"></a>
												<p><a href="<?php echo($link); ?>"><?php echo($button['destination']['text']); ?></a></p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</li>
					<li class="<?php $me = 'success-stories'; echo($me); ?><?php if ($slug == $me || $me == $section) { ?> current<?php } ?>"><a href="<?php bloginfo('url'); ?>/<?php echo($me); ?>/">Success Stories</a></li>
					<li class="<?php $me = 'about'; echo($me); ?><?php if ($slug == $me || $me == $section) { ?> current<?php } ?>"><a href="<?php bloginfo('url'); ?>/<?php echo($me); ?>/">About</a></li>
					<li class="<?php $me = 'contact'; echo($me); ?><?php if ($slug == $me || $me == $section) { ?> current<?php } ?>"><a href="<?php bloginfo('url'); ?>/<?php echo($me); ?>/">Contact</a></li>
					<li class="<?php $me = 'login'; echo($me); ?><?php if ($slug == $me || $me == $section) { ?> current<?php } ?>"><a href="http://milestonetraining.milestoneconsultinggroup.com/login" target="_blank">Login</a></li>
				</ul>
			</div>
		</div>
	</div>
</header>