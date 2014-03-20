<?php 
	if(owni_is_mobile()) {
		include('mobile/archive.php');
		die;
	}




get_header() ?>
	<div id="content">
<?php 
if(is_month()) {
	query_posts(array('cat'=>'-'.$GLOBALS['temperamente']['hidden_category_ids']['sidebar-links'], 'numberposts'=>999, 'posts_per_page'=>12, 'year'=>get_the_date('Y'), 'monthnum'=>get_the_date('m'), 'order'=>'DESC', 'orderby'=>'post_date'));
}

if (have_posts()) : ?>
		<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
		<?php /* If this is a category archive */ if (is_category()) { ?>
		<h2 class="pagetitle">Archivio per la categoria &#8220;<?php single_cat_title(); ?>&#8221;</h2>
		
<?php 
	$cat_description = category_description();
	if($cat_description != '') {
		echo str_replace('<p', '<p class="page_description" ',$cat_description) . "\n";
	}
		
		
		/* If this is a tag archive */ } elseif( is_tag() ) { ?>
		<h2 class="pagetitle">Posts Tagged &#8220;<?php single_tag_title(); ?>&#8221;</h2>
		<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
		<h2 class="pagetitle">Archive for <?php the_time('F jS, Y'); ?></h2>
		<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		<h2 class="pagetitle">Archive for <?php the_time('F, Y'); ?></h2>
		<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
		<h2 class="pagetitle">Archive for <?php the_time('Y'); ?></h2>
		<?php /* If this is an author archive */ } elseif (is_author()) { ?>
		<h2 class="pagetitle">Author Archive</h2>
		<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<h2 class="pagetitle">Blog Archives</h2>
		<?php } ?>
	<?php while (have_posts()) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" class="post single">
			<h3 class="title"><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
			<div class="meta">
				<span class="date-post"><?php the_time('F j, Y') ?></span>
				<span class="categories-post">Postato in: <?php the_category(', ') ?> </span>&nbsp;
				<a class="read-more" href="<?php the_permalink() ?>#more-<?php the_ID(); ?>" title="">Continua a leggere</a>
			</div>
			<div class="entry">
				<?php the_excerpt(); ?>
			</div>
		</div>
		<?php endwhile; ?>
	<?php if(function_exists('wp_pagenavi')) : ?>
		<?php wp_pagenavi('<span id="space"></span><div id="wp-pagenavi-wrapper">', '</div><!-- /wp-pagenavi-wrapper -->') ?>
	<?php else: ?>
		<div class="navigation clearfix">
			<div class="alignleft"><?php next_posts_link('&laquo; Post precedenti') ?></div>
			<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
		</div>
	<?php endif ?>
<?php else: ?>
		<h2 class="center">Nessuna recensione</p>
<?php endif ?>
<?php
	get_sidebar(); 
?>
	</div>
<?php
	get_footer(); 

