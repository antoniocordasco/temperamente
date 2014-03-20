<?php 

/*
Template Name: Posts by author list
*/





get_header() ?>
	<div id="content">
	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" class="post single"><div class="post-top"><div class="post-bottom"><div class="post-in">
			<h3 class="title"><?php the_title(); ?></h3>
			<!--div class="meta clearfix">
				<span class="date-post">Postato il <?php the_time('d/m/Y') ?></span> <span class="author-post">da <?php the_author() ?></span>
			</div-->
			<div class="entry">
				<?php the_content(''); ?>
			</div>
			<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
		</div></div></div></div>
		<?php endwhile; ?>
<?php 
	
	$auth_id =get_post_meta($post->ID,'tpi_posts_list_author',true); 		
	
	if($auth_id>0)
	{
		$authors_posts = $GLOBALS['wpdb']->get_results("SELECT * FROM {$GLOBALS['table_prefix']}posts WHERE post_type='post' AND post_status='publish' AND post_author = ".$auth_id.' ORDER BY ID DESC');	
				
		foreach($authors_posts as $current_post) 
		{
?>
			<div class="post single" id="post-<?php echo $current_post->ID; ?>">
				<h3 class=""><a title="<?php echo get_the_title($current_post->ID); ?>" href="<?php echo get_permalink($current_post->ID); ?>"><?php echo get_the_title($current_post->ID); ?></a></h3>
				<div class="meta">
					<span class="date-post">Postato il <?php echo get_the_time( 'd/m/Y', $current_post->ID); ?></span>
					<span class="categories-post"> in: <?php the_category(', ','',$current_post->ID) ?> </span>&nbsp;
					<a title="" href="<?php echo get_permalink($current_post->ID); ?>#more-<?php echo $current_post->ID; ?>" class="read-more">Continua a leggere</a>
				</div>				
			</div>

<?php		
		}
		if(isset($GLOBALS['temperamente']['gplus-author-links'][$auth_id]))	{ 
			echo '<p><a href="https://plus.google.com/'.$GLOBALS['temperamente']['gplus-author-links'][$auth_id].'/about" rel="me" title="profilo google+ di '.get_the_author().'" >profilo google+ di '.get_the_author().'</a></p>';
		}
	}
	
	
		
		
		
		
?>
	<?php else: ?>
		<h2 class="center">Not Found</h2>
		<p class="center">Sorry, but you are looking for something that isn't here.</p>
	<?php endif ?>
<?php
	get_sidebar(); 
?>
	</div>
<?php
	get_footer(); 

