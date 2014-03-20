<?php

ob_start();



?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
	<head profile="http://gmpg.org/xfn/11">
		<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
		<meta name="author" content="misbah" />
<?php
	include('includes/title.php');
	
?>

		<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
		<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom Feed" href="<?php bloginfo('atom_url'); ?>" />
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>?v=20" type="text/css" media="screen" />
		<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/default.css?v=3" type="text/css" media="screen" />
		<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/jcarousel/skins/tango/skin.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/js/lightbox/css/jquery.lightbox-0.5.css" type="text/css" media="screen" />

		<!--[if IE 6]>
		<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/css/style-ie.css" />
		<![endif]-->

<?php 
	wp_enqueue_script('jquery');
	
	wp_deregister_script( 'jqueryui' );
    wp_register_script( 'jqueryui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js');
    wp_enqueue_script( 'jqueryui' );
	
	remove_action( 'wp_head','adjacent_posts_rel_link_wp_head' );
	wp_head(); 
?>
		<? /*
		script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jquery.easing.min.js"></script
		was giving conflicts with latest jquery. shouldn't be needed anymore anyway
		*/
		?>
		<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jquery.jcarousel.min.js"></script>
		<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/slide.noconflict.js"></script>
		<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/lightbox/js/jquery.lightbox-0.5.min.js"></script>
		<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jquery.hoverIntent.minified.js"></script>
		<script type="text/javascript">
			var root_folder_url = '<?php bloginfo('url'); ?>/';
		</script>
		<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/functions.js?v=21"></script>



		<link href="<?php bloginfo('url'); ?>/favicon.ico" rel="shortcut icon" type="image/x-icon" />
	</head>
	<body>
		<div id="header" class="clearfix">
<?php
	include(ABSPATH.'wp-content/themes/Owni/includes/supheader.php');
?>
			
			<div id="navigation" class="menu clearfix">	</div><!-- /navigation -->
		
			<div id="header-left">
				<div id="header_title">
				<?php if(is_single()){ ?>
					<h4 id="site-title"><a href="<?php echo get_option('home') ?>/" title="<?php bloginfo('name') ?>" rel="home"><?php bloginfo('name') ?></a></h4>
				<?php }else{ ?>
					<h3 id="site-title"><a href="<?php echo get_option('home') ?>/" title="<?php bloginfo('name') ?>" rel="home"><?php bloginfo('name') ?></a></h3>
				<?php } ?>
				
				<?php if (is_home()): ?>
				<h1 id="site-description"><?php bloginfo('description') ?></h1>
				<?php else: ?>
				<h2 id="site-description"><?php bloginfo('description') ?></h2>
				<?php endif ?>
				</div>
			</div>
	
<?php
	include(ABSPATH.'wp-content/themes/Owni/includes/subheader.php');
?>		
		</div><!-- /header -->
			
			
			
		<div id="container" class="clearfix">