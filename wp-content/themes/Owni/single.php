<?php 
	if(owni_is_mobile()) {
		include('mobile/single.php');
		die;
	}
	
	
	
	get_header();
?>
	<div id="content">
	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post();
?>
		<div id="post-<?php the_ID(); ?>" class="post single"><div class="post-top"><div class="post-bottom"><div class="post-in">
			<div class="title-wrapper">
				<h1 class="title"><?php the_title(); ?></h1>
<?php
	include('includes/goodreads-rating-etc.php');
?>
			</div>

<?php	
	include('includes/post-info.php');
?>
			<div class="entry">
				<?php the_content(''); ?>								
			</div>
			<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
		</div></div></div></div>
		<?php endwhile; 
			 
		include(ABSPATH.'/wp-content/themes/Owni/includes/related_contents.php');
	
	
	
	if (function_exists('tfb_get_access_token')) {  
		include(ABSPATH.'/wp-content/themes/Owni/includes/facebook_images.php');
	}	 
		?>

	
	
		<?php  comments_template(); ?>

	<?php else: ?>
		<h2 class="center">Contenuti non trovati</h2>
	<?php endif ?>
<?php 
	get_sidebar(); 
?>
	</div>
<?php
	get_footer(); 
	
	