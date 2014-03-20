<?php 

	$amazon_res = af_get_book_details_by_search($_GET['s'], true);
	get_header() ?>
	<div id="content">
<?php if (have_posts()) { ?>
		<h2 class="pagetitle">Search Results</h2>
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
<?php } else { ?>
		<p>Il titolo che hai cercato non e` ancora stato recensito</p>		
<?php } 
	if($amazon_res) {
		echo '<h2  class="pagetitle">Risultati ricerca su Amazon.it</h2>';
		foreach($amazon_res as $item) {
			if(isset($item['title']) && isset($item['url']) && isset($item['medium-image'])) {
				echo '<div class="post single"><h3 class="title"><a rel="nofollow" class="amazon-link" href="'.$item['url'].'" title="'.$item['title'].'">'.$item['title'].'</a></h3>';
				echo '<div class="search-result-left">	';
				if(	isset($item['medium-image'])) {
					echo '<a rel="nofollow" class="amazon-link" href="'.$item['url'].'" title="'.$item['title'].'"><img src="'.$item['medium-image'].'" alt="copertina '.$item['title'].'" /></a>';
				}
				echo '</div><div class="search-result-right">	';
				if(isset($item['author'])) {
					echo '<p>Autore: '.$item['author'].'</p>';
				}
				if(isset($item['release-date'])) {
					echo '<p>Data di pubblicazione: '.date('d/m/Y', strtotime($item['release-date'])).'</p>';
				}
				if(isset($item['price'])) {
					echo '<p>Prezzo di listino: <span>'.($item['price']/100).' euro</span></p>';
				}
				if(isset($item['url']) && isset($item['lowest-price'])) {
					echo '<p class="amazon-logo">Prezzo su <a class="amazon-link" href="'.$item['url'].'" rel="nofollow" ><img src="/wp-content/themes/Owni/images/amazon/amazon.it-logo-59x17.png" alt="logo amazon.it"/></a>: <span>'. ($item['lowest-price']/100) .' euro</span> (<a class="amazon-link" href="'.$item['url'].'">acquistalo subito</a>)</p>';
				}	
				echo '</div>';
				echo '</div>';
			}
		}
	}
 
 
 
 ?>
<?php 
	get_sidebar(); 
?>
	</div>
<?php
	get_footer(); 
