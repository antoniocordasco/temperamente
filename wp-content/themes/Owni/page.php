<?php get_header() ?>
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
		<?php comments_template(); ?>
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

