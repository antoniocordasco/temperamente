<?php	include('header.php');?>		<div data-role="header" id="header">		<p><a href="/">Temperamente - recensioni letterarie</a></p>		<h1><?php the_title(); ?></h1>	</div><!-- /header -->		<div  data-role="content"><?php if(is_month()) {	query_posts(array('cat'=>'-'.$GLOBALS['temperamente']['hidden_category_ids']['sidebar-links'], 'numberposts'=>999, 'posts_per_page'=>12, 'year'=>get_the_date('Y'), 'monthnum'=>get_the_date('m'), 'order'=>'DESC', 'orderby'=>'post_date'));}if (have_posts()) : ?>		<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>		<?php /* If this is a category archive */ if (is_category()) { ?>		<h2 class="pagetitle">Archivio per la categoria &#8220;<?php single_cat_title(); ?>&#8221;</h2>		<?php 				/* If this is a tag archive */ } elseif( is_tag() ) { ?>		<h2 class="pagetitle">Posts Tagged &#8220;<?php single_tag_title(); ?>&#8221;</h2>		<?php /* If this is a daily archive */ } elseif (is_day()) { ?>		<h2 class="pagetitle">Archive for <?php the_time('F jS, Y'); ?></h2>		<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>		<h2 class="pagetitle">Archive for <?php the_time('F, Y'); ?></h2>		<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>		<h2 class="pagetitle">Archive for <?php the_time('Y'); ?></h2>		<?php /* If this is an author archive */ } elseif (is_author()) { ?>		<h2 class="pagetitle">Author Archive</h2>		<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>		<h2 class="pagetitle">Blog Archives</h2>		<?php } ?>	<?php while (have_posts()) : the_post(); ?>		<a data-role="button" href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>				<?php endwhile; ?>	<?php if(function_exists('wp_pagenavi')) : ?>		<?php wp_pagenavi('<span id="space"></span><div id="wp-pagenavi-wrapper">', '</div><!-- /wp-pagenavi-wrapper -->') ?>	<?php else: ?>		<div class="navigation clearfix">			<div class="alignleft"><?php next_posts_link('&laquo; Post precedenti') ?></div>			<div class="alignright"><?php previous_posts_link('Post successivi &raquo;') ?></div>		</div>	<?php endif ?><?php else: ?>		<h2 class="center">Nessuna recensione</p><?php endif;?>	</div><?php		include('footer.php');