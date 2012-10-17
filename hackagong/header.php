<!DOCTYPE html>

<!-- OPEN html -->
<!--[if lt IE 7 ]><html class="ie ie6" lang="<?php language_attributes(); ?>"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="<?php language_attributes(); ?>"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="<?php language_attributes(); ?>"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->

<!-- Ability - A Responsive One Page AJAX Wordpress Theme designed and coded by Ed Cousins | www.swiftpsd.com / www.edcousins.com -->

	<!-- OPEN head -->
	<head>
		<?php global $data; ?>
		
		<!-- Site Meta -->
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="fb-app" value="<?=FACEBOOK_APP_ID?>">
		
		<!-- Site Title -->
		<title>
			<?php
			global $page, $paged;
			// Add the blog name.
			bloginfo( 'name' );
			wp_title( '|', true, 'left' );
			// Add the blog description for the home/front page.
			$site_description = get_bloginfo( 'description', 'display' );
			if ( $site_description && ( is_home() || is_front_page() ) )
				echo " | $site_description";
			// Add a page number if necessary:
			if ( $paged >= 2 || $page >= 2 )
				echo ' | ' . sprintf( __( 'Page %s', 'ability' ), max( $paged, $page ) );
			?>
		</title>

		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
		
		<!-- Pingback & Favicon -->
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		<?php $favicon = $data['ab_custom_favicon'];?>
		<?php if ($favicon) { ?>
		<link rel="shortcut icon" href="<?php echo $favicon; ?>" />
		<?php } ?>
		
		<!-- LOAD Stylesheets -->
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo get_bloginfo('template_directory'); ?>/css/social.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo get_bloginfo('template_directory'); ?>/css/base.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo get_bloginfo('template_directory'); ?>/css/skeleton.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo get_bloginfo('template_directory'); ?>/css/flexslider.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo get_bloginfo('template_directory'); ?>/css/prettyPhoto.css" />
		<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_bloginfo('template_directory'); ?>/js/jplayer/skin/swiftpsd/jplayer.swiftpsd.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo get_bloginfo( 'stylesheet_url' ); ?>" />
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo get_bloginfo('template_directory'); ?>/css/layout.css" />
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>

		<?php $skin = $data['ab_skin_select']; ?>
		<?php if ($skin) { ?>
 			<link rel="stylesheet" type="text/css" media="screen" href="<?php echo get_bloginfo('template_directory'); ?>/css/skins/<?php echo $skin; ?>.php" />
 		<?php } else { ?>
 		 	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo get_bloginfo('template_directory'); ?>/css/skins/light.php" />
 		<?php } ?>

	    <!-- Legacy HTML5 Support -->
	    <!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		
		<!-- WordPress Hook -->
		<?php wp_head(); ?>
	
	<!-- CLOSE head -->
	</head>
		
	<!-- OPEN Body -->
	<body <?php body_class(); ?>>

	<noscript>
		<div class="no-js-alert">
			<?php echo do_shortcode(stripslashes($data['ab_no_js_message'])); ?>
		</div>
	</noscript>
	
	<?php
   if ( is_front_page() ) { 
      print hg_facebook_user_tiles();
	?>

	<?php
	if(have_posts()) : while(have_posts()) : the_post(); ?>
	<div id="hero-section">
		<?php the_content(); ?>
	</div>
	<?php endwhile; endif;
	}
	// END CUSTOM HEADER
	?>	

		<div id="header-section">

			<div class="container">
        <!-- USER REGISTRATION -->
        <div id="social-wrap">
          <?php if($_SESSION['SOCIAL_AUTH'] == true): ?>
            <div class="social-loggedin">
              <ul>
                <li><a href="/messages">Messages</a></li>
                <li><a href="/account"><img src="<?php echo $_SESSION['SOCIAL_PROFILE_URL'] ?>" class="social_user_icon"> Account</a></li>
              </ul>
            </div>
          <?php else: ?>
            <div class="social-login">
              <ul>
                <li><a href="#" id="social_login_fb"><img src="<?bloginfo('template_url');?>/images/social/facebook_32.png" class="social_icon"></a></li>
                <li><a href="#" id="social_login_tw"><img src="<?bloginfo('template_url');?>/images/social/twitter_32.png" class="social_icon"></a></li>
                <li><a href="#" id="social_login_go"><img src="<?bloginfo('template_url');?>/images/social/google_32.png" class="social_icon"></a></li>
                <li>Register</li>
              </ul>
            </div>
          <?php endif; ?>
        </div>
        
        
				<header class="sixteen columns">
					
					<!-- <div id="logo" class="four columns alpha">
					                    <a href="<?php bloginfo('url'); ?>">
					                      <?php $logo = $data['ab_logo_upload'];?>
					                      <?php if ($logo) { ?>
					                      <img src="<?php echo $logo; ?>" alt="<?php bloginfo( 'name' ); ?>" />
					                      <?php } else { ?>
					                      <img src="<?php echo get_bloginfo('template_directory'); ?>/images/logo-light.png" alt="<?php bloginfo( 'name' ); ?>" />
					                      <?php } ?>
					                    </a>
					                  </div> -->

					<div class="nav-wrap sixteen columns omega">
	
						<!-- OPEN #main-navigation -->
						<nav id="main-navigation">

							<?php
							if(function_exists('wp_nav_menu')) {
							wp_nav_menu(array(
							'theme_location' => 'Main_Navigation',
							'fallback_cb' => ''
							)); }
							?>

						<!-- CLOSE #main-navigation -->
						</nav>
		
						<!-- OPEN #mobile-navigation -->
						<nav id="mobile-navigation">
						<?php
							dropdown_menu( array(

								'theme_location' => 'Main_Navigation',

							    // You can alter the blanking text eg. "- Navigate -" using the following
							    'dropdown_title' => '-- Menu --',

							    // indent_string is a string that gets output before the title of a
							    // sub-menu item. It is repeated twice for sub-sub-menu items and so on
							    'indent_string' => '- ',

							    // indent_after is an optional string to output after the indent_string
							    // if the item is a sub-menu item
							    'indent_after' => ''

							) );
						?>
						<!-- CLOSE #mobile-navigation -->
						</nav>
					
					</div>

					<div class="site-loading loading"></div>
				
				</header>

			</div>

			<div class="sub-navigation"></div>

		</div>

		<!-- OPEN #main-container -->
		<div id="main-container">

			<!-- OPEN #content -->
			<div id="content">